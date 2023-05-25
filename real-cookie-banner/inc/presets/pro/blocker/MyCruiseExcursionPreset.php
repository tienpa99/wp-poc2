<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\MyCruiseExcursionPreset as PresetsMyCruiseExcursionPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * My Cruise ExcursionPreset blocker preset.
 */
class MyCruiseExcursionPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsMyCruiseExcursionPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = \__('My Cruise Excursion', RCB_TD);
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['div[id="widget-main-tripup"]', '*widget.meine-landausfluege.de*', '*widget.mycruiseexcursion.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/my-cruise-excursion.png')];
    }
}
