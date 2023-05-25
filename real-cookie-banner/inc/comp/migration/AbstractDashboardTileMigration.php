<?php

namespace DevOwl\RealCookieBanner\comp\migration;

use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\AbstractSyncPlugin;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Utils;
use WP_Error;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Allow to define a list of migrations in our dashboard.
 */
abstract class AbstractDashboardTileMigration
{
    use UtilsProvider;
    const OPTION_NAME_CLICKED_ACTIONS = RCB_OPT_PREFIX . '-migration-actions-clicked';
    private $id;
    /**
     * A set of actions which can be made within this migration.
     * See also `$this::addAction`.
     */
    private $actions = [];
    /**
     * C'tor. Register your actions with `$this::addAction` here!
     */
    public function __construct()
    {
        $this->init();
    }
    /**
     * Initialize hooks.
     */
    protected function init()
    {
        if ($this->isActive() && !Core::getInstance()->getConfigPage()->isVisible()) {
            \add_filter('RCB/Menu/ConfigPage/Attention', '__return_true');
        }
        // Allow to dismiss this via action
        \add_action('RCB/Migration/Dismiss/' . $this->getId(), [$this, 'dismiss']);
        // Make the migration available in revision
        \add_filter('RCB/Revision/Current', [$this, 'revisionCurrent']);
        // Initially create this option
        \add_option(self::OPTION_NAME_CLICKED_ACTIONS, []);
    }
    /**
     * Get a unique id for this migration.
     *
     * @return string
     */
    public abstract function getId();
    /**
     * Get a headline for this migration.
     *
     * @return string
     */
    public abstract function getHeadline();
    /**
     * Get a description for this migration.
     *
     * @return string
     */
    public abstract function getDescription();
    /**
     * Check if this migration is active.
     *
     * @return boolean
     */
    public abstract function isActive();
    /**
     * `addAction()` your actions here.
     *
     * @return void
     */
    public abstract function actions();
    /**
     * Dismiss this migration.
     *
     * @return boolean
     */
    public abstract function dismiss();
    /**
     * Register a new action which can be made for this migration.
     *
     * Arguments:
     * - `[linkText] (string)` Can be `null` to disable the link
     * - `[confirmText] (string)` The user needs to confirm this migration with something like "Are you sure?"
     * - `[callback] (callback|string)` Can be `null`, a callback which is executed when clicking the link or a direct URL
     * - `[previewImage] (string)` Absolute URL to preview image
     *
     * @param string $id Unique action ID
     * @param string $title
     * @param string $description
     * @param array $args Arguments, see above
     */
    public function addAction($id, $title, $description, $args = [])
    {
        $args = \wp_parse_args($args, ['linkText' => null, 'linkClasses' => 'button', 'linkDisabled' => \false, 'confirmText' => null, 'callback' => null, 'previewImage' => null, 'forceShowPerformedLabel' => \false, 'needsPro' => \false, 'info' => null]);
        $this->actions[$id] = \array_merge(['id' => $id, 'title' => $title, 'description' => $description, 'performed' => \get_option(self::OPTION_NAME_CLICKED_ACTIONS, [])[$this->getId() . '-' . $id] ?? \false], $args);
        if ($args['needsPro'] && !$this->isPro()) {
            $this->actions[$id]['linkDisabled'] = \true;
        }
        if (\is_callable($args['callback'])) {
            \add_filter('RCB/Migration/' . $this->getId() . '/' . $id, function ($result) use($args, $id) {
                if ($args['needsPro'] && !$this->isPro()) {
                    return new WP_Error('rcb_migration_only_pro', \__('Migration is only available in PRO version!', RCB_TD));
                }
                $result = $args['callback']($result);
                // Save that this action was performed by this user
                if ($result['success']) {
                    $this->saveActionPerformed($id);
                }
                return $result;
            });
        }
        return $this;
    }
    /**
     * Save an action as "performed" so we can show this in the UI.
     *
     * @param string $id
     */
    public function saveActionPerformed($id)
    {
        $clickedActions = \get_option(self::OPTION_NAME_CLICKED_ACTIONS, []);
        $clickedActions[$this->getId() . '-' . $id] = \mysql2date('c', \current_time('mysql'), \false);
        \update_option(self::OPTION_NAME_CLICKED_ACTIONS, $clickedActions);
    }
    /**
     * Get the migration as plain array so it can be consumed in the frontend.
     *
     * @param array $array
     */
    public function revisionCurrent($array)
    {
        if ($this->isActive()) {
            \do_action('RCB/Migration/RegisterActions');
            $array['dashboard_migration'] = ['id' => $this->getId(), 'headline' => $this->getHeadline(), 'description' => $this->getDescription(), 'actions' => $this->actions];
        }
        return $array;
    }
    /**
     * Get a config URL pointing to a given route (react-router).
     *
     * @param string $route
     */
    public function getConfigUrl($route)
    {
        $configUrl = Core::getInstance()->getConfigPage()->getUrl();
        return \sprintf('%s#%s', $configUrl, $route);
    }
    /**
     * Check if a given major version was previously installed.
     *
     * @param int $majorVersion
     */
    public function hasMajorPreviouslyInstalled($majorVersion)
    {
        return \array_search($majorVersion, \array_map(function ($v) {
            return \intval(\explode('.', $v)[0]);
        }, Core::getInstance()->getActivator()->getPreviousDatabaseVersions()), \true) !== \false;
    }
    /**
     * Dismiss the migration by removing a major version from the previously installed versions.
     *
     * @param int $majorVersion
     */
    public function removeMajorVersionFromPreviouslyInstalled($majorVersion)
    {
        Core::getInstance()->getActivator()->removePreviousPersistedVersions(function ($v) use($majorVersion) {
            return \intval(\explode('.', $v)[0]) !== $majorVersion;
        });
    }
    /**
     * Delete customizer texts for given languages and option keys.
     *
     * @param string[] $languages
     * @param string[] $ids
     * @return string[] Deleted option keys
     */
    public function deleteCustomizerTexts($languages, $ids)
    {
        $compLanguage = Core::getInstance()->getCompLanguage();
        // Prepare a list of all languages so we can also consider `LanguageDependingOption` options
        $suffixes = [];
        if ($compLanguage instanceof AbstractSyncPlugin) {
            foreach ($compLanguage->getActiveLanguages() as $locale) {
                foreach ($languages as $langToDelete) {
                    if (Utils::startsWith(\strtolower($locale), $langToDelete)) {
                        $suffixes[] = '-' . $locale;
                        // See `LanguageDependingOption::getOptionName`
                        continue;
                    }
                }
            }
        }
        // Delete customizer options
        $deletedOptionsTexts = [];
        foreach ($ids as $optionName) {
            if (\delete_option($optionName)) {
                $deletedOptionsTexts[] = $optionName;
            }
            foreach ($suffixes as $suffix) {
                $suffixOptionName = $optionName . $suffix;
                if (\delete_option($suffixOptionName)) {
                    $deletedOptionsTexts[] = $suffixOptionName;
                }
            }
        }
        return $deletedOptionsTexts;
    }
    /**
     * Dismiss a migration by ID.
     *
     * @param string $migrationId
     */
    public static function doDismiss($migrationId)
    {
        /**
         * Dismiss a migration by ID.
         *
         * @hook RCB/Migration/Dismiss/$migrationId
         * @since 2.0.0
         */
        \do_action('RCB/Migration/Dismiss/' . $migrationId);
    }
    /**
     * Register all actions with `AbstractDashboardTileMigration#actions()` method.
     */
    public static function doRegisterActions()
    {
        /**
         * Register all actions with `AbstractDashboardTileMigration#actions()` method.
         *
         * @hook RCB/Migration/RegisterActions
         * @since 3.0.0
         */
        return \do_action('RCB/Migration/RegisterActions');
    }
    /**
     * Start a given migration by ID.
     *
     * @param string $migrationId
     * @param string $actionId
     */
    public static function doAction($migrationId, $actionId)
    {
        self::doRegisterActions();
        /**
         * Start a given migration by ID and action ID.
         *
         * You can extend the `$result` with the additional properties:
         *
         * - `redirect`: Redirect to this URL after successful migration.
         *
         * @hook RCB/Migration/$migrationId/$actionId
         * @param {array} $result
         * @return {array|WP_Error}
         * @since 2.0.0
         */
        return \apply_filters('RCB/Migration/' . $migrationId . '/' . $actionId, ['success' => \false]);
    }
}
