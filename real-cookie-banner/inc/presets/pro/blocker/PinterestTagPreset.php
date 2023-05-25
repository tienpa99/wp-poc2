<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\PinterestTagPreset as PresetsPinterestTagPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Pinterest Tag blocker preset.
 */
class PinterestTagPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsPinterestTagPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Pinterest Tag';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*pintrk*', '*pinimg.com/ct/core.js*', '*ct.pinterest.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/pinterest.png')];
    }
}
