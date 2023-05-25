<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\IssuuPreset as PresetsIssuuPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Issuu blocker preset.
 */
class IssuuPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsIssuuPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Issuu';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*e.issuu.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/issuu.png')];
    }
}
