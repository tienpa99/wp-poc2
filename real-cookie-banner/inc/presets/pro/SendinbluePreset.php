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
 * Sendinblue cookie preset.
 */
class SendinbluePreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::SENDINBLUE;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Sendinblue';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'description' => \__('former Newsletter2Go', RCB_TD), 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/sendinblue.png')];
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
