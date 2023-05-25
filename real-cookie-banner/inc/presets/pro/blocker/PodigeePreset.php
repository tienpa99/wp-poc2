<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\PodigeePreset as PresetsPodigeePreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Podigee blocker preset.
 */
class PodigeePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsPodigeePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Podigee';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*player.podigee-cdn.net*', 'script[data-configuration*="podigee.io"]', '*.podigee.io/*/embed*', '*podigee.io/embed*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/podigee.png')];
    }
}
