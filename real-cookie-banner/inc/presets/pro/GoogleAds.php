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
 * Google Ads cookie preset.
 */
class GoogleAds extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::GOOGLE_ADS;
    const UNIQUE_WRITE_GTAG = 'gtag';
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => 'Google Ads', 'description' => \__('Conversion Tracking and Remarketing', RCB_TD), 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google-a-ds.png')];
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
