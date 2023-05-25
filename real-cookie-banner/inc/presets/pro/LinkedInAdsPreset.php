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
 * LinkedIn Ads cookie preset.
 */
class LinkedInAdsPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::LINKEDIN_ADS;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'LinkedIn Ads';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'description' => 'LinkedIn Insight-Tag', 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/linkedin.png')];
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
