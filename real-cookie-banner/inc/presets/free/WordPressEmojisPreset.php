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
 * WordPress Emojis cookie preset.
 */
class WordPressEmojisPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::WORDPRESS_EMOJIS;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'WordPress Emojis';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => \admin_url('images/wordpress-logo.svg'), 'attributes' => ['name' => 'Emojis', 'group' => \__('Functional', Hooks::TD_FORCED), 'purpose' => \__('WordPress Emoji is an emoji set that is loaded from wordpress.org. No cookies in the technical sense are set on the client of the user, but technical and personal data such as the IP address will be transmitted from the client to the server of the service provider to make the use of the service possible.', Hooks::TD_FORCED), 'isEmbeddingOnlyExternalResources' => \true, 'provider' => 'WordPress.org', 'providerPrivacyPolicyUrl' => \__('https://wordpress.org/about/privacy/', Hooks::TD_FORCED), 'ePrivacyUSA' => \true, 'technicalHandlingNotice' => \join('<br /><br />', [\__('Almost all browsers and operating systems released after 2015 can easily display emojis without the WordPressa Emoji Script (for which you must obtain this consent).', RCB_TD), \sprintf(
            // translators:
            \__('Unless you need to support particularly old browsers and operating systems, we recommend that you <a href="%s" target="_blank">disable the WordPress emoji script</a> rather than asking for consent. How to do that, we have explained in our blog!', RCB_TD),
            \__('https://devowl.io/2022/disable-emojis-wordpress/', RCB_TD)
        )])]];
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
