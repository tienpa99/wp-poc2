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
 * etracker with consent cookie preset.
 */
class EtrackerWithConsentPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::ETRACKER_WITH_CONSENT;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'etracker';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => \__('Tracking with consent', RCB_TD), 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/et-racker.png')];
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
