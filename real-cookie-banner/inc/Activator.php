<?php

namespace DevOwl\RealCookieBanner;

use DevOwl\RealCookieBanner\Vendor\DevOwl\DeliverAnonymousAsset\AnonymousAssetBuilder;
use DevOwl\RealCookieBanner\Vendor\DevOwl\DeliverAnonymousAsset\DeliverAnonymousAsset;
use DevOwl\RealCookieBanner\Vendor\DevOwl\Multilingual\AbstractSyncPlugin;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\presets\CookiePresets;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
use DevOwl\RealCookieBanner\presets\Presets;
use DevOwl\RealCookieBanner\scanner\Persist;
use DevOwl\RealCookieBanner\settings\CookieGroup;
use DevOwl\RealCookieBanner\settings\Revision;
use DevOwl\RealCookieBanner\templates\StorageHelper;
use DevOwl\RealCookieBanner\view\blocker\Plugin;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Activator as UtilsActivator;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * The activator class handles the plugin relevant activation hooks: Uninstall, activation,
 * deactivation and installation. The "installation" means installing needed database tables.
 */
class Activator
{
    use UtilsProvider;
    use UtilsActivator;
    const OPTION_NAME_INSTALLATION_DATE = RCB_OPT_PREFIX . '-installation-date';
    const OPTION_NAME_NEEDS_DEFAULT_CONTENT = RCB_OPT_PREFIX . '-needs-default-content';
    /**
     * Any plugin got deactivated / activated so let's recalculate the presets cache.
     */
    public function anyPluginToggledState()
    {
        \wp_rcb_invalidate_presets_cache();
    }
    /**
     * Method gets fired when the user activates the plugin.
     */
    public function activate()
    {
        // Your implementation...
    }
    /**
     * Method gets fired when the user deactivates the plugin.
     */
    public function deactivate()
    {
        // Your implementation...
    }
    /**
     * Add initial content after first installation. It also supports multiple languages (e.g. WPML and PolyLang).
     */
    public function addInitialContent()
    {
        // Only create in admin backend (e. g. PolyLang currentLanguage is not available in REST API requests)
        if (!\is_admin() && \current_user_can(\DevOwl\RealCookieBanner\Core::MANAGE_MIN_CAPABILITY)) {
            return;
        }
        $compLanguage = \DevOwl\RealCookieBanner\Core::getInstance()->getCompLanguage();
        $allLanguages = $compLanguage->getActiveLanguages();
        $missingLanguages = Hooks::getInstance()->getLanguagesWithoutEssentialGroup();
        $createdLanguages = \array_values(\array_diff($allLanguages, $missingLanguages));
        $doCopyToOtherLanguages = \count($missingLanguages) > 0 && \count($allLanguages) > 1 && \count($createdLanguages) > 0;
        if ($doCopyToOtherLanguages && $compLanguage instanceof AbstractSyncPlugin) {
            $compLanguage->getSync()->startCopyProcess()->copyAll($createdLanguages[0], $missingLanguages);
            // Reload page to avoid javascript errors when in config page
            $configPage = \DevOwl\RealCookieBanner\Core::getInstance()->getConfigPage();
            if ($configPage->isVisible()) {
                \wp_safe_redirect($configPage->getUrl());
                exit;
            }
        } else {
            // Initial content should be available in the current language
            $tempTd = Hooks::getInstance()->createTemporaryTextDomain();
            // Create default groups
            $createdDefaultGroup = CookieGroup::getInstance()->ensureEssentialGroupCreated();
            // Create default cookies (RCB)
            if ($createdDefaultGroup) {
                (new CookiePresets())->createFromPreset(PresetIdentifierMap::REAL_COOKIE_BANNER, CookieGroup::getInstance()->getEssentialGroup(\true)->term_id);
            }
            \delete_site_option(self::OPTION_NAME_NEEDS_DEFAULT_CONTENT);
            $tempTd->teardown();
        }
    }
    /**
     * Detect first installation and conditionally create initial content.
     */
    public function detectFirstInstallation()
    {
        // Is it the first installation?
        if (empty(\get_option(self::OPTION_NAME_INSTALLATION_DATE))) {
            // Default content needs to be inserted in `init` action, and can not ensured at this time (e. g. WP CLI activation)
            \add_site_option(self::OPTION_NAME_NEEDS_DEFAULT_CONTENT, \true);
            // Add option initially of first installation date
            \add_option(self::OPTION_NAME_INSTALLATION_DATE, \gmdate('Y-m-d'));
        }
    }
    /**
     * Install tables, stored procedures or whatever in the database.
     * This method is always called when the version bumps up or for
     * the first initial activation.
     *
     * @param boolean $errorlevel If true throw errors
     */
    public function dbDelta($errorlevel)
    {
        global $wpdb;
        $this->detectFirstInstallation();
        $max_index_length = $this->getMaxIndexLength();
        // wp_rcb_revision
        $table_name = $this->getTableName(Revision::TABLE_NAME);
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE {$table_name} (\n            id mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT,\n            json_revision text NOT NULL,\n            `hash` char(32) NOT NULL,\n            created datetime NOT NULL,\n            PRIMARY KEY  (id),\n            UNIQUE KEY `hash` (`hash`)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_stats
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\Stats::TABLE_NAME_TERMS);
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            context varchar(200) NOT NULL,\n            `day` date NOT NULL,\n            term_name varchar(70) NOT NULL,\n            term_id bigint(20) UNSIGNED NOT NULL,\n            accepted tinyint(1) NOT NULL,\n            `count` int UNSIGNED,\n            PRIMARY KEY  (id),\n            UNIQUE KEY `dayGroup` (`context`({$max_index_length}), `day`, `term_id`, `accepted`)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_stats_buttons_clicked
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\Stats::TABLE_NAME_BUTTONS_CLICKED);
        $max_index_length_stats_buttons_clicked = $max_index_length - 32;
        // subtract length of other varchar fields
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            context varchar(200) NOT NULL,\n            `day` date NOT NULL,\n            button_clicked varchar(32) NOT NULL,\n            `count` int UNSIGNED,\n            PRIMARY KEY  (id),\n            UNIQUE KEY `dayGroup` (`context`({$max_index_length_stats_buttons_clicked}), `day`, `button_clicked`)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_stats_custom_bypass
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\Stats::TABLE_NAME_CUSTOM_BYPASS);
        $max_index_length_stats_custom_bypass = $max_index_length - 50;
        // subtract length of other varchar fields
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            context varchar(200) NOT NULL,\n            `day` date NOT NULL,\n            custom_bypass varchar(50) NOT NULL,\n            `count` int UNSIGNED,\n            PRIMARY KEY  (id),\n            UNIQUE KEY `dayGroup` (`context`({$max_index_length_stats_custom_bypass}), `day`, `custom_bypass`)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_revision_independent
        $table_name = $this->getTableName(Revision::TABLE_NAME_INDEPENDENT);
        $sql = "CREATE TABLE {$table_name} (\n            id mediumint(9) UNSIGNED NOT NULL AUTO_INCREMENT,\n            json_revision text NOT NULL,\n            `hash` char(32) NOT NULL,\n            created datetime NOT NULL,\n            PRIMARY KEY  (id),\n            UNIQUE KEY `hash` (`hash`)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_consent
        $table_name = $this->getTableName(\DevOwl\RealCookieBanner\UserConsent::TABLE_NAME);
        /**
         * Stats index:
         *    $max_index_length_stats = $max_index_length - (36 + 32 + 20 + 8  DATETIME created); // subtract length of other fields
         *    Currently configured as strict `40` length as the context is usually not greater
         *        `KEY stats (created, context(40), forwarded, button_clicked, uuid)`
         *    This key is currently not in use
         */
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            plugin_version varchar(20) DEFAULT '0.0.0' NOT NULL,\n            design_version int(4) UNSIGNED DEFAULT 1 NOT NULL,\n            ipv4 int UNSIGNED,\n            ipv6 binary(16),\n            ipv4_hash char(64),\n            ipv6_hash char(64),\n            uuid char(36) NOT NULL,\n            revision char(32) NOT NULL,\n            revision_independent char(32) NOT NULL,\n            previous_decision tinytext NOT NULL,\n            decision_hash char(32) NOT NULL,\n            decision tinytext NOT NULL,\n            blocker bigint(20) UNSIGNED,\n            blocker_thumbnail bigint(20) UNSIGNED,\n            button_clicked varchar(32) NOT NULL,\n            context varchar(40) NOT NULL,\n            viewport_width int UNSIGNED NOT NULL,\n            viewport_height int UNSIGNED NOT NULL,\n            referer tinytext NOT NULL,\n            pure_referer tinytext NOT NULL,\n            url_imprint tinytext NOT NULL,\n            url_privacy_policy tinytext NOT NULL,\n            dnt tinyint(1) UNSIGNED NOT NULL,\n            custom_bypass varchar(50),\n            created datetime NOT NULL,\n            forwarded bigint(20) UNSIGNED,\n            forwarded_blocker tinyint(1) NOT NULL,\n            user_country varchar(5),\n            tcf_string text,\n            recorder text,\n            ui_view varchar(15),\n            PRIMARY KEY  (id),\n            KEY ipFlooding (ipv4_hash, ipv6_hash, created),\n            KEY filters (created, context),\n            KEY revisions (revision, revision_independent)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_asset_seo_redirect (use own table name for backwards compatibility)
        \DevOwl\RealCookieBanner\Core::getInstance()->getAnonymousAssetBuilder()->dbDelta();
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_tcf*
        \DevOwl\RealCookieBanner\Core::getInstance()->getTcfVendorListNormalizer()->dbDelta();
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_p_queue
        \DevOwl\RealCookieBanner\Core::getInstance()->getRealQueue()->dbDelta();
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_presets
        $table_name = $this->getTableName(Presets::TABLE_NAME);
        $max_index_length_presets_identifier = $max_index_length - 70;
        // subtract length of other varchar fields
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            identifier varchar(200) NOT NULL,\n            context varchar(50) NOT NULL,\n            type varchar(20) NOT NULL,\n            version int(11) NOT NULL,\n            description tinytext,\n            logoFile tinytext,\n            name varchar (255),\n            tags text,\n            attributes_name varchar(200),\n            disabled tinyint(1) NOT NULL,\n            hidden tinyint(1) NOT NULL,\n            recommended tinyint(1) NOT NULL,\n            created tinyint(1) NOT NULL,\n            other_meta text,\n            tier varchar(50),\n            PRIMARY KEY  (id),\n            UNIQUE KEY `identifier` (`identifier`({$max_index_length_presets_identifier}), `context`, `type`)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_templates
        $table_name = $this->getTableName(StorageHelper::TABLE_NAME);
        $max_index_length_templates_identifier = $max_index_length - 70;
        // subtract length of other varchar fields
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            identifier varchar(200) NOT NULL,\n            context varchar(50) NOT NULL,\n            type varchar(20) NOT NULL,\n            version int(11) NOT NULL,\n            created_at datetime NOT NULL,\n            headline varchar (255) NOT NULL,\n            sub_headline tinytext NOT NULL,\n            logo_url tinytext NOT NULL,\n            is_disabled tinyint(1) NOT NULL,\n            is_created tinyint(1) NOT NULL,\n            is_hidden tinyint(1) NOT NULL,\n            is_recommended tinyint(1) NOT NULL,\n            is_cloud tinyint(1) NOT NULL,\n            tier varchar(8) NOT NULL,\n            tags text NOT NULL,\n            before_middleware text NOT NULL,\n            after_middleware text NOT NULL,\n            other_meta text NOT NULL,\n            PRIMARY KEY  (id),\n            UNIQUE KEY `identifier` (`identifier`({$max_index_length_templates_identifier}), `context`, `type`)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_scan
        // Mention: Some of the `NOT NULL` values can be interpreted as `NULL`, but due to the fact
        // that it should be used for a `UNIQUE` key it must be handled like an empty string: https://stackoverflow.com/a/40767306/5506547
        $table_name = $this->getTableName(Persist::TABLE_NAME);
        $max_index_length_site_entry = $max_index_length - (32 + 32 + 20 + 32);
        // subtract length of other varchar fields
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            preset varchar(200) NOT NULL,\n            blocked_url text,\n            blocked_url_host tinytext,\n            blocked_url_hash char(32) NOT NULL,\n            markup_hash char(32),\n            tag varchar(20) NOT NULL,\n            post_id bigint(20),\n            source_url tinytext NOT NULL,\n            source_url_hash char(32) NOT NULL,\n            ignored tinyint(1) NOT NULL,\n            created datetime NOT NULL,\n            PRIMARY KEY  (id),\n            UNIQUE KEY `site_entry` (`preset`({$max_index_length_site_entry}), `blocked_url_hash`, `source_url_hash`, `tag`, `markup_hash`)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_scan_markup
        $table_name = $this->getTableName(Persist::TABLE_NAME_MARKUP);
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            markup_hash char(32),\n            markup longtext,\n            PRIMARY KEY  (id),\n            UNIQUE KEY `markup_hash` (`markup_hash`)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        // wp_rcb_blocker_thumbnails
        $table_name = $this->getTableName(Plugin::TABLE_NAME_BLOCKER_THUMBNAILS);
        $sql = "CREATE TABLE {$table_name} (\n            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,\n            embed_id varchar(32) NOT NULL,\n            file_md5 varchar(32) NOT NULL,\n            embed_url tinytext NOT NULL,\n            cache_filename tinytext NOT NULL,\n            title tinytext NOT NULL,\n            width int UNSIGNED NOT NULL,\n            height int UNSIGNED NOT NULL,\n            PRIMARY KEY  (id),\n            UNIQUE KEY `embed` (`embed_id`, `file_md5`)\n        ) {$charset_collate};";
        \dbDelta($sql);
        if ($errorlevel) {
            $wpdb->print_error();
        }
        \wp_rcb_invalidate_presets_cache();
    }
    /**
     * Uninstall our plugin (it does currently not remove any settings!).
     */
    public static function uninstall()
    {
        global $wpdb;
        // Delete anonymous JavaScript files
        $table_name = $wpdb->prefix . RCB_DB_PREFIX . '_' . AnonymousAssetBuilder::TABLE_NAME;
        foreach (['banner', 'blocker'] as $handle) {
            DeliverAnonymousAsset::uninstall($table_name, \sprintf('%s-%s', RCB_SLUG, $handle), ['js']);
        }
    }
}
