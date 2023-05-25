<?php

namespace DevOwl\RealCookieBanner\presets\free;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\middleware\DisablePresetByNeedsMiddleware;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
use DevOwl\RealCookieBanner\Utils;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Jetpack Site Stats cookie preset.
 */
class JetpackSiteStatsPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::JETPACK_SITE_STATS;
    const SLUG = 'jetpack';
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Jetpack Site Stats';
        $cookieHostCurrent = Utils::host(Utils::HOST_TYPE_CURRENT);
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/jetpack.png'), 'needs' => self::needs(), 'attributes' => ['name' => $name, 'group' => \__('Statistics', Hooks::TD_FORCED), 'purpose' => \__('Jetpack Site Stats is a service to create detailed statistics about the user behavior on the website. No cookies in the technical sense are set on the client of the user, but technical and personal data such as the IP address will be transmitted from the client to the server of the service provider to make the use of the service possible. This information can be used to track the user on this website and to connect collected data of several page views on this website. the client of the user in the technical sense, but technical data is transferred from the client to the server of the service provider in order to track the user on this website and to connect collected data of several page views on this website.', Hooks::TD_FORCED), 'provider' => 'Automattic Inc.', 'providerPrivacyPolicyUrl' => 'https://automattic.com/privacy/', 'technicalDefinitions' => [['type' => 'http', 'name' => 'NID', 'host' => '.google.com', 'durationUnit' => 'mo', 'isSessionDuration' => \false, 'duration' => 6], ['type' => 'http', 'name' => 'G_ENABLED_IDPS', 'host' => '.public-api.wordpress.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'comment_author_email_*', 'host' => $cookieHostCurrent, 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'comment_author_url_*', 'host' => $cookieHostCurrent, 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'comment_author_*', 'host' => $cookieHostCurrent, 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'comment_author_url_*', 'host' => '.wordpress.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'comment_author_email_*', 'host' => '.wordpress.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'comment_author_*', 'host' => '.wordpress.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'hc_post_as', 'host' => '.wordpress.com', 'duration' => 2, 'durationUnit' => 'd', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'wpc_wpc', 'host' => '.wordpress.com', 'durationUnit' => 'y', 'isSessionDuration' => \true, 'duration' => 0], ['type' => 'http', 'name' => '_wpndash', 'host' => '.wordpress.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'wordpress_logged_in', 'host' => '.wordpress.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'recognized_logins', 'host' => '.wordpress.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'wordpress_test_cookie', 'host' => '.wordpress.com', 'durationUnit' => 'y', 'isSessionDuration' => \true, 'duration' => 0], ['type' => 'http', 'name' => 'wpc_gpc', 'host' => '.wordpress.com', 'duration' => 1, 'durationUnit' => 'd', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SIDCC', 'host' => '.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-3PAPISID', 'host' => '.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-APISID', 'host' => '.google.com', 'duration' => 1, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-SSID', 'host' => '.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-HSID', 'host' => '.google.com', 'duration' => 1, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SAPISID', 'host' => '.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SSID', 'host' => '.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'HSID', 'host' => '.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SID', 'host' => '.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-3PSID', 'host' => '.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'CONSENT', 'host' => '.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '1P_JAR', 'host' => '.google.com', 'duration' => 1, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'G_AUTHUSER_H', 'host' => '.public-api.wordpress.com', 'durationUnit' => 'y', 'isSessionDuration' => \true, 'duration' => 0], ['type' => 'http', 'name' => '__Host-GAPS', 'host' => 'accounts.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'GAPS', 'host' => 'accounts.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'LSID', 'host' => 'accounts.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Host-3PLSID', 'host' => 'accounts.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'user_id', 'host' => 'accounts.google.com', 'durationUnit' => 'y', 'isSessionDuration' => \true, 'duration' => 0], ['type' => 'http', 'name' => 'LSOLH', 'host' => 'accounts.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'local', 'name' => 'oauth2_ss::https://public-api.wordpress.com::1::DEFAULT::_ss_', 'host' => 'https://accounts.google.com', 'durationUnit' => 'y', 'isSessionDuration' => \false, 'duration' => 0], ['type' => 'local', 'name' => 'promo', 'host' => 'https://accounts.google.com', 'durationUnit' => 'y', 'isSessionDuration' => \false, 'duration' => 0], ['type' => 'http', 'name' => 'wpc_tc', 'host' => '.wordpress.com', 'durationUnit' => 'y', 'isSessionDuration' => \true, 'duration' => 0], ['type' => 'session', 'name' => 'oauth2_cs::https://public-api.wordpress.com::*', 'host' => 'https://accounts.google.com', 'durationUnit' => 'y', 'isSessionDuration' => \false, 'duration' => 0], ['type' => 'http', 'name' => 'wpc_fbc', 'host' => '.wordpress.com', 'durationUnit' => 'y', 'isSessionDuration' => \true, 'duration' => 0]], 'technicalHandlingNotice' => \__('There is no need for an opt-in script because the site stats feature is loaded by the Jetpack WordPress plugin. In addition to the cookie, you must create a content blocker that will block Jetpack Site Stats until the user gives consent to load it.', RCB_TD), 'ePrivacyUSA' => \true]];
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
    // Self-explanatory
    public static function needs()
    {
        return DisablePresetByNeedsMiddleware::generateNeedsForSlugs([self::SLUG]);
    }
}
