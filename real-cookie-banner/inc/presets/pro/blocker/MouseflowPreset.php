<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\MouseflowPreset as PresetsMouseflowPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Mouseflow blocker preset.
 */
class MouseflowPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsMouseflowPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Mouseflow';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['_mfq', '*cdn.mouseflow.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/mouseflow.png')];
    }
}
