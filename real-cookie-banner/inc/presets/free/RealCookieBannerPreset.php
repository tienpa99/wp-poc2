<?php

namespace DevOwl\RealCookieBanner\presets\free;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\MyConsent;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\CookiePresets;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
use DevOwl\RealCookieBanner\settings\Consent;
use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\Utils;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Real Cookie Banner cookie preset.
 */
class RealCookieBannerPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::REAL_COOKIE_BANNER;
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Real Cookie Banner';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => 'real-cookie-banner.svg', 'hidden' => \true, 'attributes' => ['name' => $name, 'group' => \__('Essential', Hooks::TD_FORCED), 'purpose' => \__('Real Cookie Banner asks website visitors for consent to set cookies and process personal data. For this purpose, a UUID (pseudonymous identification of the user) is assigned to each website visitor, which is valid until the cookie expires to store the consent. Cookies are used to test whether cookies can be set, to store reference to documented consent, to store which services from which service groups the visitor has consented to, and, if consent is obtained under the Transparency & Consent Framework (TCF), to store consent in TCF partners, purposes, special purposes, features and special features. As part of the obligation to disclose according to GDPR, the collected consent is fully documented. This includes, in addition to the services and service groups to which the visitor has consented, and if consent is obtained according to the TCF standard, to which TCF partners, purposes and features the visitor has consented, all cookie banner settings at the time of consent as well as the technical circumstances (e.g. size of the displayed area at the time of consent) and the user interactions (e.g. clicking on buttons) that led to consent. Consent is collected once per language.', Hooks::TD_FORCED), 'legalBasis' => Cookie::LEGAL_BASIS_LEGAL_REQUIREMENT, 'provider' => \get_bloginfo('name'), 'providerPrivacyPolicyUrl' => General::getInstance()->getPrivacyPolicyUrl(''), 'providerLegalNoticeUrl' => General::getInstance()->getImprintPageUrl(''), 'technicalDefinitions' => [['type' => 'http', 'name' => MyConsent::COOKIE_NAME_USER_PREFIX . '*', 'host' => Utils::host(Utils::HOST_TYPE_MAIN_WITH_ALL_SUBDOMAINS), 'duration' => Consent::getInstance()->getCookieDuration(), 'durationUnit' => 'd', 'isSessionDuration' => \false], ['type' => 'http', 'name' => MyConsent::COOKIE_NAME_USER_PREFIX . '*-tcf', 'host' => Utils::host(Utils::HOST_TYPE_MAIN_WITH_ALL_SUBDOMAINS), 'duration' => Consent::getInstance()->getCookieDuration(), 'durationUnit' => 'd', 'isSessionDuration' => \false], ['type' => 'http', 'name' => MyConsent::COOKIE_NAME_USER_PREFIX . '-test', 'host' => Utils::host(Utils::HOST_TYPE_MAIN_WITH_ALL_SUBDOMAINS), 'duration' => Consent::getInstance()->getCookieDuration(), 'durationUnit' => 'd', 'isSessionDuration' => \false]], 'deleteTechnicalDefinitionsAfterOptOut' => \false]];
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
    /**
     * We have updated our Real Cookie Banner preset and we need to automatically apply the patch to the
     * Real Cookie Banner service.
     *
     * @param string|false $installed
     * @see https://app.clickup.com/t/1td2xu0
     */
    public static function new_version_installation_after_2_11_0($installed)
    {
        if ($installed && \version_compare($installed, '2.11.0', '<=')) {
            // Lazy it, to be compatible with other plugins like WPML or PolyLang...
            \add_action('init', function () {
                $realCookieBannerService = Cookie::getInstance()->getServiceByIdentifier(self::IDENTIFIER);
                if ($realCookieBannerService !== null) {
                    $td = Hooks::getInstance()->createTemporaryTextDomain();
                    (new CookiePresets())->createFromPreset(self::IDENTIFIER, \false, $realCookieBannerService->ID);
                    $td->teardown();
                }
            }, 20);
        }
    }
}
