<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\TikTokPreset as PresetsTikTokPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * TikTok blocker preset.
 */
class TikTokPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsTikTokPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'TikTok';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*tiktok.com/embed.js*', 'blockquote[class="tiktok-embed"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/tiktok.png')];
    }
}
