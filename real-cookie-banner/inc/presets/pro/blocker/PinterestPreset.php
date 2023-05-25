<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\PinterestPreset as PresetsPinterestPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Pinterest blocker preset.
 */
class PinterestPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsPinterestPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Pinterest (Embed)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*assets.pinterest.com*', '*i.pinimg.com*', '*widgets.pinterest.com*', 'a[data-pin-do]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/pinterest.png')];
    }
}
