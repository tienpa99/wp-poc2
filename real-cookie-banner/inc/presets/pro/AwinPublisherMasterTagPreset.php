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
 * Awin (Publisher MasterTag) cookie preset.
 */
class AwinPublisherMasterTagPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::AWIN_PUBLISHER_MASTERTAG;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Awin';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'description' => 'Publisher MasterTag', 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/awin.png')];
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
