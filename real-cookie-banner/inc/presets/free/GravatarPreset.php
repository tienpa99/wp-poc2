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
 * Gravatar Avatar cookie preset.
 */
class GravatarPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::GRAVATAR;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Gravatar (Avatar images)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => 'Gravatar', 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/gravatar.png'), 'attributes' => ['name' => $name, 'group' => \__('Functional', Hooks::TD_FORCED), 'purpose' => \__('Gravatar is a service where people can associate their email address with an avatar image that is for example loaded in the comment area. No cookies in the technical sense are set on the client of the user, but technical and personal data such as the IP address will be transmitted from the client to the server of the service provider to make the use of the service possible.', Hooks::TD_FORCED), 'provider' => 'Automattic Inc.', 'providerPrivacyPolicyUrl' => 'https://automattic.com/privacy/', 'isEmbeddingOnlyExternalResources' => \true, 'technicalHandlingNotice' => \__('There is no need for an opt-in script, because Gravatar images are loaded by WordPress or some plugin without JavaScript. In addition to this cookie, please create a content blocker that automatically blocks images from Gravatar e. g. in the comment section.', RCB_TD), 'ePrivacyUSA' => \true]];
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
