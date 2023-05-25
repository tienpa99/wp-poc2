<?php

namespace DevOwl\RealCookieBanner\settings;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\lite\settings\Consent as LiteConsent;
use DevOwl\RealCookieBanner\overrides\interfce\settings\IOverrideConsent;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Settings > Consent.
 */
class Consent implements IOverrideConsent
{
    use LiteConsent;
    use UtilsProvider;
    const OPTION_GROUP = 'options';
    const SETTING_ACCEPT_ALL_FOR_BOTS = RCB_OPT_PREFIX . '-accept-all-for-bots';
    const SETTING_RESPECT_DO_NOT_TRACK = RCB_OPT_PREFIX . '-respect-do-not-track';
    const SETTING_COOKIE_DURATION = RCB_OPT_PREFIX . '-cookie-duration';
    const SETTING_COOKIE_VERSION = RCB_OPT_PREFIX . '-cookie-version';
    const SETTING_SAVE_IP = RCB_OPT_PREFIX . '-save-ip';
    const SETTING_EPRIVACY_USA = RCB_OPT_PREFIX . '-eprivacy-usa';
    const SETTING_AGE_NOTICE = RCB_OPT_PREFIX . '-age-notice';
    const SETTING_LIST_SERVICES_NOTICE = RCB_OPT_PREFIX . '-list-services-notice';
    const SETTING_CONSENT_DURATION = RCB_OPT_PREFIX . '-consent-duration';
    const DEFAULT_ACCEPT_ALL_FOR_BOTS = \true;
    const DEFAULT_RESPECT_DO_NOT_TRACK = \false;
    const DEFAULT_COOKIE_DURATION = 365;
    const DEFAULT_SAVE_IP = \false;
    const DEFAULT_EPRIVACY_USA = \false;
    const DEFAULT_AGE_NOTICE = \true;
    const DEFAULT_LIST_SERVICES_NOTICE = \true;
    const DEFAULT_CONSENT_DURATION = 120;
    const TRANSIENT_SCHEDULE_CONSENTS_DELETION = RCB_OPT_PREFIX . '-schedule-consents-deletion';
    /**
     * Search the coding for difference.
     */
    const COOKIE_VERSION_1 = 1;
    const COOKIE_VERSION_2 = 2;
    const COOKIE_VERSION_3 = 3;
    const DEFAULT_COOKIE_VERSION = self::COOKIE_VERSION_3;
    /**
     * Singleton instance.
     *
     * @var Consent
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden.
    }
    /**
     * Initially `add_option` to avoid autoloading issues.
     */
    public function enableOptionsAutoload()
    {
        \DevOwl\RealCookieBanner\settings\General::enableOptionAutoload(self::SETTING_ACCEPT_ALL_FOR_BOTS, self::DEFAULT_ACCEPT_ALL_FOR_BOTS, 'boolval');
        \DevOwl\RealCookieBanner\settings\General::enableOptionAutoload(self::SETTING_RESPECT_DO_NOT_TRACK, self::DEFAULT_RESPECT_DO_NOT_TRACK, 'boolval');
        \DevOwl\RealCookieBanner\settings\General::enableOptionAutoload(self::SETTING_COOKIE_DURATION, self::DEFAULT_COOKIE_DURATION, 'intval');
        \DevOwl\RealCookieBanner\settings\General::enableOptionAutoload(self::SETTING_COOKIE_VERSION, self::DEFAULT_COOKIE_VERSION, 'intval');
        \DevOwl\RealCookieBanner\settings\General::enableOptionAutoload(self::SETTING_SAVE_IP, self::DEFAULT_SAVE_IP, 'boolval');
        \DevOwl\RealCookieBanner\settings\General::enableOptionAutoload(self::SETTING_AGE_NOTICE, self::DEFAULT_AGE_NOTICE, 'boolval');
        \DevOwl\RealCookieBanner\settings\General::enableOptionAutoload(self::SETTING_LIST_SERVICES_NOTICE, self::DEFAULT_LIST_SERVICES_NOTICE, 'boolval');
        \DevOwl\RealCookieBanner\settings\General::enableOptionAutoload(self::SETTING_CONSENT_DURATION, self::DEFAULT_CONSENT_DURATION, 'intval');
        $this->overrideEnableOptionsAutoload();
    }
    /**
     * Register settings.
     */
    public function register()
    {
        \register_setting(self::OPTION_GROUP, self::SETTING_ACCEPT_ALL_FOR_BOTS, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_RESPECT_DO_NOT_TRACK, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_COOKIE_DURATION, ['type' => 'number', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_COOKIE_VERSION, ['type' => 'number', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_SAVE_IP, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_AGE_NOTICE, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_LIST_SERVICES_NOTICE, ['type' => 'boolean', 'show_in_rest' => \true]);
        \register_setting(self::OPTION_GROUP, self::SETTING_CONSENT_DURATION, ['type' => 'number', 'show_in_rest' => \true]);
        $this->overrideRegister();
    }
    /**
     * Check if bots should acceppt all cookies automatically.
     *
     * @return boolean
     */
    public function isAcceptAllForBots()
    {
        return \get_option(self::SETTING_ACCEPT_ALL_FOR_BOTS);
    }
    /**
     * Check if "Do not Track" header is respected.
     *
     * @return boolean
     */
    public function isRespectDoNotTrack()
    {
        return \get_option(self::SETTING_RESPECT_DO_NOT_TRACK);
    }
    /**
     * Check if IPs should be saved in plain in database.
     *
     * @return boolean
     */
    public function isSaveIpEnabled()
    {
        return \get_option(self::SETTING_SAVE_IP);
    }
    /**
     * Check if age notice hint is enabled
     *
     * @return boolean
     */
    public function isAgeNoticeEnabled()
    {
        return \get_option(self::SETTING_AGE_NOTICE);
    }
    /**
     * Check if list-services notice hint is enabled
     *
     * @return boolean
     */
    public function isListServicesNoticeEnabled()
    {
        return \get_option(self::SETTING_LIST_SERVICES_NOTICE);
    }
    /**
     * Get the cookie duration for the consent cookies.
     *
     * @return int
     */
    public function getCookieDuration()
    {
        return \get_option(self::SETTING_COOKIE_DURATION);
    }
    /**
     * Get the cookie version for the consent cookies.
     *
     * @return int
     */
    public function getCookieVersion()
    {
        return \get_option(self::SETTING_COOKIE_VERSION);
    }
    /**
     * Get the consent duration.
     *
     * @return int
     */
    public function getConsentDuration()
    {
        return \get_option(self::SETTING_CONSENT_DURATION);
    }
    /**
     * The cookie duration may not be greater than 365 days.
     *
     * @param mixed $value
     * @since 1.10
     */
    public function option_cookie_duration($value)
    {
        // Use `is_numeric` as it can be a string
        if (\is_numeric($value) && \intval($value) > 365) {
            return 365;
        }
        return $value;
    }
    /**
     * The consent duration may not be greater than 120 months.
     *
     * @param mixed $value
     */
    public function option_consent_duration($value)
    {
        // Use `is_numeric` as it can be a string
        if (\is_numeric($value) && \intval($value) > 120) {
            return 120;
        }
        return $value;
    }
    /**
     *  Delete transient when settings are updated
     */
    public function update_option_consent_transient_deletion()
    {
        \delete_transient(self::TRANSIENT_SCHEDULE_CONSENTS_DELETION);
    }
    /**
     * Get singleton instance.
     *
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\settings\Consent() : self::$me;
    }
    /**
     * Deactivate "Naming of all services in first view" as it should not be activated automatically for already existing users.
     *
     * @param string|false $installed
     */
    public static function new_version_installation_after_2_17_3($installed)
    {
        if (Core::versionCompareOlderThan($installed, '2.17.3', ['2.17.4', '2.18.0'])) {
            \update_option(self::SETTING_LIST_SERVICES_NOTICE, '');
        }
    }
    /**
     * Revert to cookie version 1 for users already using RCB.
     *
     * @param string|false $installed
     */
    public static function new_version_installation_after_3_0_1($installed)
    {
        if (Core::versionCompareOlderThan($installed, '3.0.1', ['3.0.2', '3.1.0'])) {
            \update_option(self::SETTING_COOKIE_VERSION, self::COOKIE_VERSION_1);
        }
    }
}
