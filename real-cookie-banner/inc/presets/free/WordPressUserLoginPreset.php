<?php

namespace DevOwl\RealCookieBanner\presets\free;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\Utils;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WordPress User Login cookie preset.
 */
class WordPressUserLoginPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::WORDPRESS_USER_LOGIN;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $cookieHost = Utils::host(Utils::HOST_TYPE_CURRENT);
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => \__('WordPress User Login', RCB_TD), 'logoFile' => \admin_url('images/wordpress-logo.svg'), 'attributes' => ['name' => \__('WordPress User Login', Hooks::TD_FORCED), 'group' => \__('Essential', Hooks::TD_FORCED), 'purpose' => \__('WordPress is the content management system for this website and allows registered users to log in to the system. The cookies store the credentials of a logged-in user as hash, login status and user ID as well as user-related settings for the WordPress backend.', Hooks::TD_FORCED), 'provider' => \get_bloginfo('name'), 'providerPrivacyPolicyUrl' => General::getInstance()->getPrivacyPolicyUrl(''), 'providerLegalNoticeUrl' => General::getInstance()->getImprintPageUrl(''), 'technicalDefinitions' => [['type' => 'http', 'name' => 'wordpress_*', 'host' => $cookieHost, 'duration' => 0, 'durationUnit' => 'y', 'isSessionDuration' => \true], ['type' => 'http', 'name' => 'wordpress_logged_in_*', 'host' => $cookieHost, 'duration' => 0, 'durationUnit' => 'y', 'isSessionDuration' => \true], ['type' => 'http', 'name' => 'wp-settings-*-*', 'host' => $cookieHost, 'duration' => 1, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'wordpress_test_cookie', 'host' => $cookieHost, 'duration' => 0, 'durationUnit' => 'y', 'isSessionDuration' => \true]], 'deleteTechnicalDefinitionsAfterOptOut' => \false, 'createContentBlockerNotice' => \join('<br /><br />', [\__('You can block the login form to your WordPress (backend) login until consent. This is useful if you want external services like Google reCAPTCHA to load additionally at the login.', RCB_TD), \__('Note: In this case, you must classify this service as non-essential (e.g. functional). Also, you should create a content blocker that not only waits for consent for this service, but also for consent for all third-party services (like Google reCAPTCHA in the example).', RCB_TD)]), 'shouldUncheckContentBlockerCheckbox' => \true]];
    }
    // Documented in AbstractPreset
    public function managerNone()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerGtm()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerMtm()
    {
        return \false;
    }
}
