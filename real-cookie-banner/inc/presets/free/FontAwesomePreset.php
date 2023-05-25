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
 * Font Awesome cookie preset.
 */
class FontAwesomePreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::FONTAWESOME;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Font Awesome';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/font-awesome.png'), 'attributes' => ['name' => $name, 'group' => \__('Functional', Hooks::TD_FORCED), 'purpose' => \__('Font Awesome is a service that downloads a custom icon font that are not installed on the client device of the user and embeds them into the website. No cookies in the technical sense are set on the client of the user, but technical and personal data such as the IP address will be transmitted from the client to the server of the service provider to make the use of the service possible.', Hooks::TD_FORCED), 'provider' => 'Fonticons, Inc.', 'providerPrivacyPolicyUrl' => 'https://fontawesome.com/privacy', 'isEmbeddingOnlyExternalResources' => \true, 'technicalHandlingNotice' => \__('There is no need for an opt-in script, because Font Awesome is loaded by some plugin or theme without JavaScript. In addition to this cookie, please create a content blocker that automatically blocks Font Awesome.', RCB_TD), 'ePrivacyUSA' => \true]];
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
