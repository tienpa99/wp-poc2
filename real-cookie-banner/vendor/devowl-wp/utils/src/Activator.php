<?php

namespace DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils;

\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request.
/**
 * The activator class handles the plugin relevant activation hooks: Uninstall, activation,
 * deactivation and installation. The "installation" means installing needed database tables.
 */
trait Activator
{
    /**
     * Install tables, stored procedures or whatever in the database.
     * This method is always called when the version bumps up or for
     * the first initial activation.
     *
     * @param boolean $errorlevel If true throw errors.
     */
    public abstract function dbDelta($errorlevel);
    /**
     * Indexes have a maximum size of 767 bytes. Historically, we haven't need to be concerned about that.
     * As of 4.2, however, we moved to utf8mb4, which uses 4 bytes per character. This means that an index which
     * used to have room for floor(767/3) = 255 characters, now only has room for floor(767/4) = 191 characters.
     *
     * @see https://github.com/WordPress/WordPress/blob/5f9cf0141e2e32f47ae7f809b7a6bbc0d4bd4ef2/wp-admin/includes/schema.php#L48-L53
     * @codeCoverageIgnore
     */
    public function getMaxIndexLength()
    {
        return 191;
    }
    /**
     * Run an installation or dbDelta within a callable.
     *
     * @param boolean $errorlevel Set true to throw errors.
     * @param callable $installThisCallable Set a callable to install this one instead of the default.
     */
    public function install($errorlevel = \false, $installThisCallable = null)
    {
        global $wpdb;
        // @codeCoverageIgnoreStart
        if (!\defined('PHPUNIT_FILE')) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }
        // @codeCoverageIgnoreEnd
        // Check if we've attempted to run this migration in the past 10 minutes. If so, it may still be running.
        if ($installThisCallable === null) {
            if ($this->isMigrationLocked()) {
                return;
            }
            \update_option($this->getPluginConstant(Constants::PLUGIN_CONST_OPT_PREFIX) . '_db_migration', \time());
        }
        // Avoid errors printed out.
        if ($errorlevel === \false) {
            $show_errors = $wpdb->show_errors(\false);
            $suppress_errors = $wpdb->suppress_errors(\false);
            $errorLevel = \error_reporting(0);
        }
        if ($installThisCallable === null) {
            $this->dbDelta($errorlevel);
        } else {
            \call_user_func($installThisCallable);
        }
        if ($errorlevel === \false) {
            $wpdb->show_errors($show_errors);
            $wpdb->suppress_errors($suppress_errors);
            \error_reporting($errorLevel);
        }
        if ($installThisCallable === null) {
            $this->persistPreviousVersion();
            \update_option($this->getPluginConstant(Constants::PLUGIN_CONST_OPT_PREFIX) . '_db_version', $this->getPluginConstant(Constants::PLUGIN_CONST_VERSION));
            \update_option($this->getPluginConstant(Constants::PLUGIN_CONST_OPT_PREFIX) . '_db_migration', 0);
        }
    }
    /**
     * Check if the migration is locked. It uses a time span of 10 minutes (like Yoast SEO plugin).
     *
     * @see https://github.com/Yoast/wordpress-seo/blob/a5fd83173bf56bf7841d72bb6d3d33ecc4caa825/src/config/migration-status.php#L34-L46
     */
    public function isMigrationLocked()
    {
        $latestMigration = \intval(\get_option($this->getPluginConstant(Constants::PLUGIN_CONST_OPT_PREFIX) . '_db_migration', 0));
        if ($latestMigration > 0) {
            return $latestMigration > \strtotime('-10 minutes');
        } else {
            return \false;
        }
    }
    /**
     * Get the current persisted database version.
     */
    public function getDatabaseVersion()
    {
        return \get_option($this->getPluginConstant(Constants::PLUGIN_CONST_OPT_PREFIX) . '_db_version');
    }
    /**
     * Get a list of previous installed database versions.
     *
     * @return string[]
     */
    public function getPreviousDatabaseVersions()
    {
        return \get_option($this->getPluginConstant(Constants::PLUGIN_CONST_OPT_PREFIX) . '_db_previous_version', []);
    }
    /**
     * Persist the previous installed versions of this plugin so we can e.g. start migrations.
     */
    public function persistPreviousVersion()
    {
        $currentVersion = \get_option($this->getPluginConstant(Constants::PLUGIN_CONST_OPT_PREFIX) . '_db_version');
        if ($currentVersion !== \false) {
            $previousVersionsOptionName = $this->getPluginConstant(Constants::PLUGIN_CONST_OPT_PREFIX) . '_db_previous_version';
            $previousVersions = $this->getPreviousDatabaseVersions();
            // Extract only "real" versioning in semver format (x.y.z), but no prereleases
            \preg_match('/(\\d+\\.\\d+\\.\\d+)/', $currentVersion, $matches, \PREG_OFFSET_CAPTURE, 0);
            $pureVersion = $matches[0][0];
            $previousVersions[] = $pureVersion;
            $previousVersions = \array_unique($previousVersions);
            \update_option($previousVersionsOptionName, $previousVersions);
        }
    }
    /**
     * Remove the previous persisted versions from the saved option. This is useful if you have
     * successfully finished your migration.
     *
     * @param callback $filter
     */
    public function removePreviousPersistedVersions($filter)
    {
        $versions = $this->getPreviousDatabaseVersions();
        $versions = \array_filter($versions, $filter);
        return \update_option($this->getPluginConstant(Constants::PLUGIN_CONST_OPT_PREFIX) . '_db_previous_version', $versions);
    }
}
