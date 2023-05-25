<?php

namespace DevOwl\RealCookieBanner\presets\free\blocker;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\free\WordPressPluginEmbed as FreeWordPressPluginEmbed;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WordPress Emojis blocker preset.
 */
class WordPressPluginEmbed extends AbstractBlockerPreset
{
    const IDENTIFIER = FreeWordPressPluginEmbed::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'WordPress.org Plugin (Embed)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => \admin_url('images/wordpress-logo.svg'), 'attributes' => ['name' => $name, 'description' => \__('WordPress.org would give more information about a WordPress plugin here if you would give your consent.', Hooks::TD_FORCED), 'rules' => ['*wordpress.org/plugins/*/embed/*'], 'serviceTemplates' => [FreeWordPressPluginEmbed::IDENTIFIER], 'isVisual' => \true, 'visualType' => 'default', 'visualContentType' => 'generic', 'shouldForceToShowVisual' => \true]];
    }
}
