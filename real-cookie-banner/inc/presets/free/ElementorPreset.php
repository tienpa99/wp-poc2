<?php

namespace DevOwl\RealCookieBanner\presets\free;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\middleware\DisablePresetByNeedsMiddleware;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\Utils;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Elementor page builder cookie preset.
 */
class ElementorPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::ELEMENTOR;
    const SLUG_FREE = 'elementor';
    const SLUG_PRO = 'elementor-pro';
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Elementor';
        $cookieHost = Utils::host(Utils::HOST_TYPE_CURRENT);
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/elementor.png'), 'needs' => self::needs(), 'recommended' => \true, 'attributes' => ['name' => $name, 'group' => \__('Essential', Hooks::TD_FORCED), 'purpose' => \__('Elementor is a software used to create the layout of this website. Cookies are used to store the number of page views and active sessions of the user. The collected data is not used for analysis purposes, but only to ensure that, for example, hidden elements are not displayed again during multiple active sessions.', Hooks::TD_FORCED), 'provider' => \get_bloginfo('name'), 'providerPrivacyPolicyUrl' => General::getInstance()->getPrivacyPolicyUrl(''), 'providerLegalNoticeUrl' => General::getInstance()->getImprintPageUrl(''), 'technicalDefinitions' => [['type' => 'local', 'name' => 'elementor', 'host' => $cookieHost, 'durationUnit' => 'y', 'isSessionDuration' => \false, 'duration' => 0], ['type' => 'session', 'name' => 'elementor', 'host' => $cookieHost, 'durationUnit' => 'y', 'isSessionDuration' => \false, 'duration' => 0]], 'technicalHandlingNotice' => \sprintf('<a href="%s" target="_blank">%s</a>', \esc_attr(\__('https://devowl.io/2021/elementor-cookie-gdpr/', RCB_TD)), \__('Learn, how to use Elementor in the most GDPR-compliant way possible.', RCB_TD))]];
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
        return DisablePresetByNeedsMiddleware::generateNeedsForSlugs([self::SLUG_PRO, self::SLUG_FREE]);
    }
}
