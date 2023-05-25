<?php

namespace DevOwl\RealCookieBanner\presets\free;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Youtube cookie preset.
 */
class YoutubePreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::YOUTUBE;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'YouTube';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/youtube.png'), 'attributes' => ['name' => $name, 'group' => \__('Functional', Hooks::TD_FORCED), 'purpose' => \__('YouTube allows embedding content posted on youtube.com directly into websites. The cookies are used to collect visited websites and detailed statistics about the user behaviour. This data can be linked to the data of users registered on youtube.com and google.com or localized versions of these services.', Hooks::TD_FORCED), 'provider' => 'Google Ireland Limited', 'providerPrivacyPolicyUrl' => 'https://policies.google.com/privacy', 'technicalDefinitions' => [['type' => 'http', 'name' => 'SIDCC', 'host' => '.youtube.com', 'durationUnit' => 'y', 'isSessionDuration' => \false, 'duration' => 1], ['type' => 'http', 'name' => '__Secure-3PAPISID', 'host' => '.youtube.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-APISID', 'host' => '.youtube.com', 'duration' => 1, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SAPISID', 'host' => '.youtube.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SSID', 'host' => '.youtube.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '1P_JAR', 'host' => '.youtube.com', 'duration' => 1, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SEARCH_SAMESITE', 'host' => '.youtube.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'YSC', 'host' => '.youtube.com', 'durationUnit' => 'y', 'isSessionDuration' => \true, 'duration' => 0], ['type' => 'http', 'name' => 'LOGIN_INFO', 'host' => '.youtube.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'HSID', 'host' => '.youtube.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'VISITOR_INFO1_LIVE', 'host' => '.youtube.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'CONSENT', 'host' => '.youtube.com', 'duration' => 18, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-SSID', 'host' => '.youtube.com', 'duration' => 1, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-HSID', 'host' => '.youtube.com', 'duration' => 1, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'APISID', 'host' => '.youtube.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-3PSID', 'host' => '.youtube.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'PREF', 'host' => '.youtube.com', 'duration' => 8, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SID', 'host' => '.youtube.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 's_gl', 'host' => '.youtube.com', 'durationUnit' => 'y', 'isSessionDuration' => \true, 'duration' => 0], ['type' => 'http', 'name' => 'SIDCC', 'host' => '.google.com', 'duration' => 1, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-3PAPISID', 'host' => '.google.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SAPISID', 'host' => '.google.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'APISID', 'host' => '.google.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SSID', 'host' => '.google.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'HSID', 'host' => '.google.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '__Secure-3PSID', 'host' => '.google.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'SID', 'host' => '.google.com', 'duration' => 2, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'CONSENT', 'host' => '.google.com', 'duration' => 18, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'NID', 'host' => '.google.com', 'duration' => 6, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => '1P_JAR', 'host' => '.google.com', 'duration' => 1, 'durationUnit' => 'mo', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'DV', 'host' => 'www.google.com', 'duration' => 1, 'durationUnit' => 'm', 'isSessionDuration' => \false]], 'technicalHandlingNotice' => \join('<br /><br />', [\__('There is no need for an opt-in script because the YouTube content is usually loaded in an iframe. You must create a content blocker that will block YouTube until the user gives consent to load it.', RCB_TD), \sprintf(
            // translators:
            \__('What do you have to consider when integrating %1$s into your website? <a href="%2$s" target="_blank">Learn more about it in our blog!</a>', RCB_TD),
            $name,
            \esc_attr(\__('https://devowl.io/2022/youtube-website-gdpr/', RCB_TD))
        )]), 'deleteTechnicalDefinitionsAfterOptOut' => \false, 'ePrivacyUSA' => \true]];
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
