<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\MyFontsPreset as PresetsMyFontsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * MyFonts.net blocker preset.
 */
class MyFontsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsMyFontsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'MyFonts';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*myfonts.net*', '*myfonts.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/my-fonts.png')];
    }
}
