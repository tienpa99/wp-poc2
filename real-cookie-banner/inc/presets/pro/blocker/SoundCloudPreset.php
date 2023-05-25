<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\SoundCloudPreset as ProSoundCloudPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * SoundCloud blocker preset.
 */
class SoundCloudPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = ProSoundCloudPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'SoundCloud';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*w.soundcloud.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/soundcloud.png')];
    }
}
