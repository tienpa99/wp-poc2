<?php

namespace DevOwl\RealCookieBanner\presets;

use DevOwl\RealCookieBanner\settings\Blocker;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\free\blocker\FontAwesomePreset;
use DevOwl\RealCookieBanner\presets\free\blocker\GravatarPreset;
use DevOwl\RealCookieBanner\presets\free\blocker\GoogleFontsPreset;
use DevOwl\RealCookieBanner\presets\free\blocker\YoutubePreset;
use DevOwl\RealCookieBanner\presets\free\blocker\JetPackSiteStatsPreset;
use DevOwl\RealCookieBanner\presets\free\blocker\JetPackCommentsPreset;
use DevOwl\RealCookieBanner\presets\free\blocker\WordPressCommentsPreset;
use DevOwl\RealCookieBanner\presets\free\blocker\WordPressEmojisPreset;
use DevOwl\RealCookieBanner\presets\free\blocker\WordPressPluginEmbed;
use DevOwl\RealCookieBanner\presets\free\blocker\WordPressUserLoginPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\AmazonAssociatesWidgetPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\AwinPublisherMasterTagPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\BingAdsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FoundEePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\FreshchatPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAdsensePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAdsPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\GtmPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\HCaptchaPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\HelpCrunchChatPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\HelpScoutChatPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\LuckyOrangePreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MatomoPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\MtmPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\PaddleComPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\ReamazeChatPreset;
use DevOwl\RealCookieBanner\presets\pro\blocker\TikTokPixelPreset;
use DevOwl\RealCookieBanner\templates\TemplateConsumers;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Predefined presets for blocker.
 */
class BlockerPresets extends \DevOwl\RealCookieBanner\presets\Presets
{
    const CLASSES = [
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::GOOGLE_FONTS => GoogleFontsPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::YOUTUBE => YoutubePreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::JETPACK_SITE_STATS => JetPackSiteStatsPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::JETPACK_COMMENTS => JetPackCommentsPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::GRAVATAR => GravatarPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::WORDPRESS_EMOJIS => WordPressEmojisPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::FONTAWESOME => FontAwesomePreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::WORDPRESS_USER_LOGIN => WordPressUserLoginPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::WORDPRESS_PLUGIN_EMBED => WordPressPluginEmbed::class,
        // Hidden content blocker just for scanning purposes
        // FREE
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::WORDPRESS_COMMENTS => WordPressCommentsPreset::class,
        // PRO
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::AWIN_PUBLISHER_MASTERTAG => AwinPublisherMasterTagPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::FOUND_EE => FoundEePreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::FRESHCHAT => FreshchatPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::GOOGLE_ADS => GoogleAdsPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::GOOGLE_AD_SENSE => GoogleAdsensePreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::GTM => GtmPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::HCAPTCHA => HCaptchaPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::HELP_CRUNCH_CHAT => HelpCrunchChatPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::HELP_SCOUT_CHAT => HelpScoutChatPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::LUCKY_ORANGE => LuckyOrangePreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::MATOMO => MatomoPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::MTM => MtmPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::BING_ADS => BingAdsPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::PADDLE_COM => PaddleComPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::REAMAZE_CHAT => ReamazeChatPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::AMAZON_ASSOCIATES_WIDGET => AmazonAssociatesWidgetPreset::class,
        \DevOwl\RealCookieBanner\presets\PresetIdentifierMap::TIKTOK_PIXEL => TikTokPixelPreset::class,
    ];
    const PRESETS_TYPE = 'blocker';
    /**
     * C'tor.
     */
    public function __construct()
    {
        parent::__construct(self::PRESETS_TYPE);
    }
    // Documented in Presets
    public function getClassList($force = \false)
    {
        /**
         * Filters available presets for blocker.
         *
         * @hook RCB/Presets/Blocker
         * @param {string} $presets All available presets. `[id => <extends AbstractBlockerPreset>::class]`
         * @returns {string}
         */
        $list = \apply_filters('RCB/Presets/Blocker', self::CLASSES);
        if ($this->needsRecalculation() || $force) {
            $this->persist($this->fromClassList($list));
        }
        return $list;
    }
    // Documented in Presets
    public function persist($items)
    {
        $persist = parent::persist($items);
        // Stress-test our Real Cookie Banner backend, will be removed in future versions
        TemplateConsumers::getCurrentBlockerConsumer()->retrieve(\true);
        if ($persist) {
            /**
             * Available presets for blockers got persisted.
             *
             * @hook RCB/Presets/Blocker/Persisted
             * @param {array[]} $presets All available presets as array
             * @since 2.6.0
             */
            \do_action('RCB/Presets/Blocker/Persisted', $items);
        }
        return $persist;
    }
    // Documented in Presets
    public function getOtherMetaKeys()
    {
        // Make rules always available in cache cause we need this for the scanner
        return ['extended', 'scanOptions'];
    }
    /**
     * Resolve `attributes.cookies` so we can e.g. show created cookies in "Connected cookies"
     * in blocker edit form.
     *
     * @param array $preset Result of `getWithAttributes()`
     */
    public function resolveAvailableCookies(&$preset)
    {
        $cookiePresets = new \DevOwl\RealCookieBanner\presets\CookiePresets();
        $existingCookies = \DevOwl\RealCookieBanner\presets\CookiePresets::getCookiesWithPreset();
        if ($preset !== \false && isset($preset['attributes'], $preset['attributes']['serviceTemplates'])) {
            $newCookies = [];
            foreach ($preset['attributes']['serviceTemplates'] as $cookie) {
                if (\is_string($cookie)) {
                    // It should reference to an existing preset, let's resolve the ID
                    $created = \false;
                    foreach ($existingCookies as $existingCookie) {
                        if ($existingCookie->metas[Blocker::META_NAME_PRESET_ID] === $cookie) {
                            $created = $existingCookie->ID;
                        }
                    }
                    // Cookie preset is available, but does not actually exist as cookie
                    $cookieFromCache = $cookiePresets->getFromCache($cookie);
                    $newCookies[] = ['identifier' => $cookie, 'name' => $cookieFromCache['name'], 'subHeadline' => $cookieFromCache['description'] ?? '', 'version' => $cookieFromCache['version'], 'attributes' => $cookiePresets->getWithAttributes($cookie)['attributes'], 'created' => $created];
                } else {
                    $newCookies[] = $cookie;
                }
            }
            $preset['attributes']['serviceTemplates'] = $newCookies;
        }
        return $preset;
    }
    // Documented in Presets
    public function fromClassList($clazzes)
    {
        $result = [];
        $existingBlockers = self::getBlockerWithPreset();
        $existingCookies = \DevOwl\RealCookieBanner\presets\CookiePresets::getCookiesWithPreset();
        foreach ($clazzes as $id => $clazz) {
            /**
             * Instance.
             *
             * @var AbstractBlockerPreset
             */
            $instance = new $clazz();
            $preset = $instance->common();
            $preset['instance'] = $instance;
            if (!isset($preset['tags'])) {
                $preset['tags'] = [];
            }
            $result[$id] = $preset;
        }
        $this->applyMiddleware($existingCookies, $existingBlockers, $result);
        foreach ($result as &$preset) {
            unset($preset['instance']);
        }
        return $result;
    }
    /**
     * See filter `RCB/Presets/Blocker/MiddlewareCallbacks`.
     *
     * @param WP_Post[] $existingCookies
     * @param WP_Post[] $existingBlockers
     * @param array $result
     */
    public function applyMiddleware($existingCookies, $existingBlockers, &$result)
    {
        /**
         * Inject some middleware directly to the content blocker preset. This can be useful to
         * enhance the preset with functionalities like `extends`.
         *
         * @hook RCB/Presets/Blocker/MiddlewareCallbacks
         * @param {callable[]} $callbacks
         * @returns {callable[]}
         * @since 2.11.1
         */
        $callbacks = \apply_filters('RCB/Presets/Blocker/MiddlewareCallbacks', []);
        foreach ($callbacks as $callback) {
            foreach ($result as &$preset) {
                \call_user_func_array($callback, [&$preset, $preset['instance'] ?? null, $existingBlockers, $existingCookies, &$result, $this]);
            }
        }
    }
    /**
     * Get all available blocker with a preset.
     */
    public static function getBlockerWithPreset()
    {
        return Blocker::getInstance()->getOrdered(\false, \get_posts(Core::getInstance()->queryArguments(['post_type' => Blocker::CPT_NAME, 'numberposts' => -1, 'nopaging' => \true, 'meta_query' => [['key' => Blocker::META_NAME_PRESET_ID, 'compare' => 'EXISTS']], 'post_status' => ['publish', 'private', 'draft']], 'blockerWithPreset')));
    }
}
