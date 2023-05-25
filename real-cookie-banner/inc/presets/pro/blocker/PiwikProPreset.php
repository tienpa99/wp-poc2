<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\PiwikProPreset as PresetsPiwikProPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Piwik PRO blocker preset.
 */
class PiwikProPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsPiwikProPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Piwik PRO';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*.piwik.pro/*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/piwik-pro.png')];
    }
}
