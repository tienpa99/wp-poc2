<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\TikTokPixelPreset as ProTikTokPixelPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * TikTok Pixel blocker preset.
 */
class TikTokPixelPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = ProTikTokPixelPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'TikTok Pixel';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'hidden' => \true, 'attributes' => ['rules' => ['*analytics.tiktok.com*'], 'serviceTemplates' => [ProTikTokPixelPreset::IDENTIFIER]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/tiktok.png')];
    }
}
