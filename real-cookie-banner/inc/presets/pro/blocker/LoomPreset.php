<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\LoomPreset as PresetsLoomPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Loom blocker preset.
 */
class LoomPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsLoomPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Loom';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*loom.com/embed*', '*cdn.loom.com*', 'a[href*="loom.com/share/"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/loom.png')];
    }
}
