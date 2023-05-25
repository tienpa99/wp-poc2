<?php

namespace DevOwl\RealCookieBanner\presets\pro;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * AddToAny Share Buttons preset.
 */
class AddToAnyPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::ADD_TO_ANY;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'AddToAny';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'description' => 'Share Buttons', 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/add-to-any.png')];
    }
    // Documented in AbstractPreset
    public function managerNone()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerGtm()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerMtm()
    {
        return \false;
    }
}
