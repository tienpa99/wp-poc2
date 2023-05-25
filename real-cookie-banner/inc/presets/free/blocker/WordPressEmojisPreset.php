<?php

namespace DevOwl\RealCookieBanner\presets\free\blocker;

use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\free\WordPressEmojisPreset as FreeWordPressEmojisPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WordPress Emojis blocker preset.
 */
class WordPressEmojisPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = FreeWordPressEmojisPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'WordPress Emojis';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => \admin_url('images/wordpress-logo.svg'), 'attributes' => ['name' => $name, 'rules' => ['*s.w.org/images/core/emoji*', 'window._wpemojiSettings', 'link[href="//s.w.org"]'], 'serviceTemplates' => [FreeWordPressEmojisPreset::IDENTIFIER], 'isVisual' => \false]];
    }
}
