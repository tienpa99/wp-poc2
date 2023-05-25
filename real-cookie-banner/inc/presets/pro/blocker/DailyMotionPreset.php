<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\DailyMotionPreset as PresetsDailyMotionPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Dailymotion blocker preset.
 */
class DailyMotionPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsDailyMotionPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Dailymotion';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*dailymotion.com/embed*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/dailymotion.png')];
    }
}
