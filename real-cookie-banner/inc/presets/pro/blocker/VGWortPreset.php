<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\VGWortPreset as PresetsVGWortPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * VG WORT blocker preset.
 */
class VGWortPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsVGWortPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'VG WORT';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*met.vgwort.de*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/vg-wort.png')];
    }
}
