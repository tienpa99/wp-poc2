<?php

namespace DevOwl\RealCookieBanner\scanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\AbstractBlockable;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\HeadlessContentBlocker;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\scanner\BlockableScanner;
use DevOwl\RealCookieBanner\Vendor\DevOwl\HeadlessContentBlocker\plugins\scanner\ScannableBlockable;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Cache;
use DevOwl\RealCookieBanner\comp\ComingSoonPlugins;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\BlockerPresets;
use DevOwl\RealCookieBanner\view\Blocker as BlockerView;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\view\blockable\BlockerPostType;
use DevOwl\RealCookieBanner\view\Scanner as ViewScanner;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\Core as RealQueueCore;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealQueue\queue\Job;
use stdClass;
use WP_User;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * This isn't really a "cookie" scanner, it is a scanner which detects external URLs,
 * scripts, iframes for the current page request. Additionally, it can automatically
 * detect usable content blocker presets which we can recommend to the user.
 */
class Scanner
{
    use UtilsProvider;
    const QUERY_ARG_TOKEN = 'rcb-scan';
    const QUERY_ARG_JOB_ID = 'rcb-scan-job';
    const REAL_QUEUE_TYPE = 'rcb-scan';
    /**
     * See `findByRobots.txt`: This simulates to be the current blog to be public
     * so the `robots.txt` exposes a sitemap and also activates the sitemap.
     */
    const QUERY_ARG_FORCE_SITEMAP = 'rcb-force-sitemap';
    private $query;
    private $onChangeDetection;
    /**
     * C'tor.
     *
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        $this->query = new \DevOwl\RealCookieBanner\scanner\Query();
        $this->onChangeDetection = new \DevOwl\RealCookieBanner\scanner\OnChangeDetection($this);
    }
    /**
     * Force to enable the content blocker even when the content blocker is deactivated.
     *
     * @param boolean $enabled
     */
    public function force_blocker_enabled($enabled)
    {
        return isset($_GET[self::QUERY_ARG_TOKEN]) && \current_user_can(RealQueueCore::MINIMUM_QUERY_CAPABILITY) ? \true : $enabled;
    }
    /**
     * All presets and external URLs got catched, let's persist them to database.
     */
    public function teardown()
    {
        $query = $this->getQuery();
        // Get Job for this scan process
        $jobId = \intval($_GET[self::QUERY_ARG_JOB_ID] ?? 0);
        $job = $jobId > 0 ? Core::getInstance()->getRealQueue()->getQuery()->fetchById($jobId) : null;
        // Memorize currently ignored hosts so we can re-ignore them
        $ignoredHosts = $query->getIgnoredHosts();
        // Memorize all found presets and external URL hosts so we can diff on them
        list($beforePresets, $beforeExternalHosts) = $query->getScannedCombinedResults();
        // Delete all known scan-entries for the current URL
        $deleteSourceUrls = [self::getCurrentSourceUrl()];
        if ($job !== null) {
            // Original URL (e.g. redirects)
            $deleteSourceUrls[] = $job->data['url'];
        }
        $query->removeSourceUrls($deleteSourceUrls);
        /**
         * `BlockableScanner`.
         *
         * @var BlockableScanner
         */
        $contentBlockerScanner = Core::getInstance()->getBlocker()->getHeadlessContentBlocker()->getPluginsByClassName(BlockableScanner::class)[0];
        $contentBlockerScanner->setActive(\false);
        $contentBlockerScanner->filterFalsePositives();
        $contentBlockerScanner->applyIgnoreByHosts($ignoredHosts);
        $scanEntries = $contentBlockerScanner->flushResults();
        // Persist scan entries to database
        $persist = new \DevOwl\RealCookieBanner\scanner\Persist($scanEntries);
        $persist->persist();
        list($afterPresets, $afterExternalHosts) = $query->getScannedCombinedResults();
        $this->doActionAddedRemoved($scanEntries, $beforePresets, $beforeExternalHosts, $afterPresets, $afterExternalHosts);
        // Print result as HTML comment
        \printf('<!--rcb-scan:%s-->', \json_encode($scanEntries));
        // Mark Job as succeed
        if ($job !== null) {
            $job->passthruClientResult(\true, null);
        }
        $this->probablyInvalidateCacheAfterJobExecution($job);
    }
    /**
     * Try to invalidate some caches after every scan process. The cache is invalidated
     * in the following cases:
     *
     * - Scan process was done without job (e.g. calling website with `?rcb-scan`)
     * - Scan process was done for a single website (e.g. after saving a post)
     * - Scan process is within a complete website then, then the following situation is implemented:
     *   Depending on the count of scanned entries, every x. job within the complete website scan
     *   invalidated the cache.
     *
     * @param Job $job
     */
    protected function probablyInvalidateCacheAfterJobExecution($job)
    {
        $doInvalidate = \false;
        if ($job !== null) {
            $isCompleteWebsiteScan = !empty($job->group_position);
            if ($isCompleteWebsiteScan) {
                $scannedEntriesCount = $this->getQuery()->getScannedEntriesCount();
                $invalidateScannedExternalUrlsAfter = 1;
                if ($scannedEntriesCount > 50000) {
                    $invalidateScannedExternalUrlsAfter = 15;
                } elseif ($scannedEntriesCount > 20000) {
                    $invalidateScannedExternalUrlsAfter = 10;
                } elseif ($scannedEntriesCount > 10000) {
                    $invalidateScannedExternalUrlsAfter = 5;
                } elseif ($scannedEntriesCount > 5000) {
                    $invalidateScannedExternalUrlsAfter = 3;
                } elseif ($scannedEntriesCount > 1000) {
                    $invalidateScannedExternalUrlsAfter = 2;
                }
                if ($job->group_position % $invalidateScannedExternalUrlsAfter === 0 || $job->group_position === $job->group_total) {
                    $doInvalidate = \true;
                }
            } else {
                $doInvalidate = \true;
            }
        } else {
            $doInvalidate = \true;
        }
        if ($doInvalidate) {
            \delete_transient(ViewScanner::TRANSIENT_SERVICES_FOR_NOTICE);
            \delete_transient(\DevOwl\RealCookieBanner\scanner\Query::TRANSIENT_SCANNED_EXTERNAL_URLS);
        }
        return $doInvalidate;
    }
    /**
     * `do_action` when a result from the scanner got removed or added.
     *
     * @param ScanEntry[] $results
     * @param string[] $beforePresets
     * @param string[] $beforeExternalHosts
     * @param string[] $afterPresets
     * @param string[] $afterExternalHosts
     */
    protected function doActionAddedRemoved($results, $beforePresets, $beforeExternalHosts, $afterPresets, $afterExternalHosts)
    {
        $changed = \false;
        $addedPresets = [];
        $removedPresets = [];
        $addedExternalHosts = [];
        $removedExternalHosts = [];
        // Check if new preset / external URL host was found
        foreach ($afterPresets as $afterPreset) {
            if (!\in_array($afterPreset, $beforePresets, \true)) {
                /**
                 * A new preset was found for the scanner.
                 *
                 * @param {string} $preset
                 * @param {ScanEntry[]} $scanEntries
                 * @hook RCB/Scanner/Preset/Found
                 * @since 2.6.0
                 */
                \do_action('RCB/Scanner/Preset/Found', $afterPreset, $results);
                $changed = \true;
                $addedPresets[] = $afterPreset;
            }
        }
        foreach ($afterExternalHosts as $afterExternalHost) {
            if (!\in_array($afterExternalHost, $beforeExternalHosts, \true)) {
                /**
                 * A new external host was found for the scanner.
                 *
                 * @param {string} $host
                 * @param {ScanEntry[]} $scanEntries
                 * @hook RCB/Scanner/ExternalHost/Found
                 * @since 2.6.0
                 */
                \do_action('RCB/Scanner/ExternalHost/Found', $afterExternalHost, $results);
                $changed = \true;
                $addedExternalHosts[] = $afterExternalHost;
            }
        }
        // Check if preset / external URL host got removed
        foreach ($beforePresets as $beforePreset) {
            if (!\in_array($beforePreset, $afterPresets, \true)) {
                /**
                 * A preset was removed from the scanner results as it is no longer found on your site.
                 *
                 * @param {string} $preset
                 * @param {ScanEntry[]} $scanEntries
                 * @hook RCB/Scanner/Preset/Removed
                 * @since 2.6.0
                 */
                \do_action('RCB/Scanner/Preset/Removed', $beforePreset, $results);
                $changed = \true;
                $removedPresets[] = $beforePreset;
            }
        }
        foreach ($beforeExternalHosts as $beforeExternalHost) {
            if (!\in_array($beforeExternalHost, $afterExternalHosts, \true)) {
                /**
                 * An external host was removed from the scanner results as it is no longer found on your site.
                 *
                 * @param {string} $host
                 * @param {ScanEntry[]} $scanEntries
                 * @hook RCB/Scanner/ExternalHost/Removed
                 * @since 2.6.0
                 */
                \do_action('RCB/Scanner/ExternalHost/Removed', $beforeExternalHost, $results);
                $changed = \true;
                $removedExternalHosts[] = $beforeExternalHost;
            }
        }
        if ($changed) {
            /**
             * New items (presets and external hosts) got added or removed from the scanner result.
             *
             * @param {string[]} $addedPresets
             * @param {string[]} $addedExternalHosts
             * @param {string[]} $removedPresets
             * @param {string[]} $removedExternalHosts
             * @param {ScanEntry[]} $scanEntries
             * @hook RCB/Scanner/Result/Updated
             * @since 2.6.0
             */
            \do_action('RCB/Scanner/Result/Updated', $addedPresets, $addedExternalHosts, $removedPresets, $removedExternalHosts, $results);
        }
    }
    /**
     * Add all known and non-disabled content blocker presets.
     *
     * @param AbstractBlockable[] $blockables
     * @param HeadlessContentBlocker $headlessContentBlocker
     */
    public function resolve_blockables($blockables, $headlessContentBlocker)
    {
        // Remove all known blockables because we want to show all found services (and label them with "Already created")
        foreach ($blockables as $key => $blockable) {
            if ($blockable instanceof BlockerPostType) {
                unset($blockables[$key]);
            }
        }
        $presets = new BlockerPresets();
        foreach ($presets->getAllFromCache() as $preset) {
            if ($preset['disabled'] || !isset($preset['scanOptions'])) {
                continue;
            }
            $blockables[] = new ScannableBlockable($headlessContentBlocker, $preset['identifier'], $preset['extended'] ?? null, $preset['scanOptions'] ?? []);
        }
        return $blockables;
    }
    /**
     * Check if the current page request should be scanned.
     */
    public function isActive()
    {
        return isset($_GET[self::QUERY_ARG_TOKEN]) && Core::getInstance()->getRealQueue()->currentUserAllowedToQuery();
    }
    /**
     * Force sitemaps in
     */
    public function probablyForceSitemaps()
    {
        if (!isset($_GET[\DevOwl\RealCookieBanner\scanner\Scanner::QUERY_ARG_FORCE_SITEMAP])) {
            return;
        }
        $cbEnableSitemaps = function ($val) {
            return \function_exists('wp_get_current_user') && \current_user_can(Core::MANAGE_MIN_CAPABILITY) ? \true : $val;
        };
        $cbStylesheetUrl = function ($url) {
            return \add_query_arg([self::QUERY_ARG_FORCE_SITEMAP => 1], $url);
        };
        \add_filter('pre_option_blog_public', $cbEnableSitemaps, 999);
        \add_filter('wp_sitemaps_enabled', $cbEnableSitemaps, 999);
        \add_filter('wp_sitemaps_index_entry', function ($entry) {
            if (isset($entry['loc'])) {
                $entry['loc'] = \add_query_arg([self::QUERY_ARG_FORCE_SITEMAP => 1], $entry['loc']);
            }
            return $entry;
        }, \PHP_INT_MAX);
        \add_filter('wp_sitemaps_stylesheet_index_url', $cbStylesheetUrl, \PHP_INT_MAX);
        \add_filter('wp_sitemaps_stylesheet_url', $cbStylesheetUrl, \PHP_INT_MAX);
    }
    /**
     * Add URLs to the queue so they get scanned.
     *
     * @param string[] $urls
     * @param boolean $purgeUnused  If `true`, the difference of the previous scanned URLs gets
     *                              automatically purged if they do no longer exist in the URLs (pass only if you have the complete sitemap!)
     */
    public function addUrlsToQueue($urls, $purgeUnused = \false)
    {
        if ($purgeUnused) {
            $urls = \array_values(\array_merge(ComingSoonPlugins::getInstance()->getComputedUrlsForSitemap(), $urls));
        }
        $queue = Core::getInstance()->getRealQueue();
        $persist = $queue->getPersist();
        $query = $queue->getQuery();
        $persist->startTransaction();
        // Only sitemap crawlers should be grouped
        if ($purgeUnused) {
            $persist->startGroup();
        }
        foreach ($urls as $url) {
            if (\filter_var($url, \FILTER_VALIDATE_URL)) {
                // Check if this URL belongs to our current installation with a loose "contains" check
                // from the original home URL as external URLs could never be scanned due to CORS errors.
                $homeUrl = Utils::getOriginalHomeUrl();
                $scheme = \parse_url($homeUrl, \PHP_URL_SCHEME) ?? '';
                $homeUrl = \trim(\substr($homeUrl, \strlen($scheme)), ':/');
                if (\stripos($url, $homeUrl) === \false) {
                    continue;
                }
                // Check if a Job with this URL already exists
                if (!$purgeUnused) {
                    $found = $query->read(['type' => 'all', 'dataContains' => \json_encode($url)]);
                    if (\count($found) > 0) {
                        continue;
                    }
                }
                $job = new Job($queue);
                $job->worker = Job::WORKER_CLIENT;
                $job->type = self::REAL_QUEUE_TYPE;
                $job->data = new stdClass();
                $job->data->url = $url;
                $job->retries = 3;
                $persist->addJob($job);
            }
        }
        if ($purgeUnused) {
            // This is a complete sitemap
            $this->purgeUnused($urls);
            Cache::getInstance()->invalidate();
        }
        return $persist->commit();
    }
    /**
     * Read a group of all known site URLs and delete them if they no longer exist in the passed URLs.
     *
     * @param string[] $urls
     */
    public function purgeUnused($urls)
    {
        $knownUrls = $this->getQuery()->getScannedSourceUrls();
        $deleted = \array_values(\array_diff($knownUrls, $urls));
        $this->getQuery()->removeSourceUrls($deleted);
        return \count($deleted);
    }
    /**
     * Get human-readable label for RCB queue jobs.
     *
     * @param string $label
     * @param string $originalType
     */
    public function real_queue_job_label($label, $originalType)
    {
        switch ($originalType) {
            case self::REAL_QUEUE_TYPE:
                return \__('Real Cookie Banner: Scan of your pages', RCB_TD);
            case \DevOwl\RealCookieBanner\scanner\AutomaticScanStarter::REAL_QUEUE_TYPE:
                return \__('Real Cookie Banner: Automatic scan of complete website', RCB_TD);
            default:
                return $label;
        }
    }
    /**
     * Get actions for RCB queue jobs.
     *
     * @param array[] $actions
     * @param string $type
     */
    public function real_queue_job_actions($actions, $type)
    {
        switch ($type) {
            case self::REAL_QUEUE_TYPE:
            case \DevOwl\RealCookieBanner\scanner\AutomaticScanStarter::REAL_QUEUE_TYPE:
                $actions[] = ['url' => \__('https://devowl.io/support/', RCB_TD), 'linkText' => \__('Contact support', RCB_TD)];
                $actions[] = ['action' => 'delete', 'linkText' => \__('Cancel scan', RCB_TD)];
                $actions[] = ['action' => 'skip', 'linkText' => \__('Skip failed pages', RCB_TD)];
                break;
            default:
        }
        return $actions;
    }
    /**
     * Get human-readable description for a RCB queue jobs.
     *
     * @param string $description
     * @param string $type
     * @param int[] $remaining
     */
    public function real_queue_error_description($description, $type, $remaining)
    {
        switch ($type) {
            case self::REAL_QUEUE_TYPE:
                return \sprintf(
                    // translators:
                    \__('%1$d pages failed to be scanned.', RCB_TD),
                    $remaining['failure']
                );
            case \DevOwl\RealCookieBanner\scanner\AutomaticScanStarter::REAL_QUEUE_TYPE:
                return \__('Real Cookie Banner tried to automatically scan your entire website for services and external URLs.', RCB_TD);
            default:
                return $description;
        }
    }
    /**
     * Remove some capabilities and roles from the current user for the running page request.
     * For example, some Google Analytics plugins do only print out the analytics code when
     * not `manage_options` (e.g. WooCommerce Google Analytics).
     *
     * @param array $caps
     * @see https://regex101.com/r/3U3miS/1
     */
    public function reduceCurrentUserPermissions()
    {
        global $current_user;
        \add_action('user_has_cap', function ($caps) {
            $caps['administrator'] = \false;
            $caps['manage_options'] = \false;
            if (isset($caps['manage_woocommerce'])) {
                $caps['manage_woocommerce'] = \false;
            }
            return $caps;
        });
        if ($current_user instanceof WP_User && \count($current_user->roles) > 0) {
            $current_user->roles = \array_filter($current_user->roles, function ($role) {
                return !\in_array($role, ['administrator'], \true);
            });
            // We never should write back roles back to database!
            // In general, never update a user meta as it is not needed while scanning a site.
            \add_filter('update_user_metadata', '__return_false', \PHP_INT_MAX);
            \add_filter('add_user_metadata', '__return_false', \PHP_INT_MAX);
        }
        // [Plugin Comp] WoodMart theme (bypass "You do not have access" for `?cms_block=` URLs)
        \add_filter('woodmart_html_block_access', function () {
            return 'subscriber';
        });
        // [Plugin Comp] RankMath
        \add_filter('rank_math/analytics/gtag_exclude_loggedin_roles', function () {
            return [];
        });
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getQuery()
    {
        return $this->query;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getOnChangeDetection()
    {
        return $this->onChangeDetection;
    }
    /**
     * Getter.
     *
     * @codeCoverageIgnore
     */
    public function getExcludeHosts()
    {
        return $this->excludeHosts;
    }
    /**
     * New instance.
     *
     * @codeCoverageIgnore
     */
    public static function instance()
    {
        return new \DevOwl\RealCookieBanner\scanner\Scanner();
    }
    /**
     * Get the current source URL usable for a newly created `ScanEntry`. It gets escaped for database use with
     * the help of `esc_url_raw`.
     */
    public static function getCurrentSourceUrl()
    {
        $result = \remove_query_arg(\DevOwl\RealCookieBanner\scanner\Scanner::QUERY_ARG_TOKEN, Utils::getRequestUrl());
        $result = \remove_query_arg(\DevOwl\RealCookieBanner\scanner\Scanner::QUERY_ARG_JOB_ID, $result);
        $result = \remove_query_arg(BlockerView::FORCE_TIME_COMMENT_QUERY_ARG, $result);
        return $result;
    }
}
