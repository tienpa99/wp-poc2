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
 * Microsoft Advertising Universal Event Tracking (UET) Tag (Bing Ads) cookie preset.
 */
class BingAdsPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::BING_ADS;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Microsoft Advertising Universal Event Tracking (UET) Tag (Bing Ads)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/microsoft.png')];
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
