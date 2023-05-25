<?php

namespace DevOwl\RealCookieBanner\presets\free;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WordPress plugin embed cookie preset.
 */
class WordPressPluginEmbed extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::WORDPRESS_PLUGIN_EMBED;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'WordPress.org Plugin (Embed)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => \admin_url('images/wordpress-logo.svg'), 'attributes' => ['name' => $name, 'group' => \__('Functional', Hooks::TD_FORCED), 'purpose' => \__('WordPress.org allows to display further and latest information about plugins published on wordpress.org. No cookies in the technical sense are set on the client of the user, but technical and personal data such as the IP address will be transmitted from the client to the server of the service provider to make the use of the service possible.', Hooks::TD_FORCED), 'provider' => 'WordPress.org', 'providerPrivacyPolicyUrl' => 'https://wordpress.org/about/privacy/', 'isEmbeddingOnlyExternalResources' => \true, 'ePrivacyUSA' => \true]];
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
