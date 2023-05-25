<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\AppleMusicPreset as PresetsAppleMusicPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Apple Music blocker preset.
 */
class AppleMusicPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsAppleMusicPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Apple Music';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*embed.music.apple.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/apple-music.png')];
    }
}
