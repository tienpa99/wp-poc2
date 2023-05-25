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
 * Google Analytics (Analytics 4) cookie preset.
 */
class GoogleAnalytics4Preset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::GOOGLE_ANALYTICS_4;
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google Analytics';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Analytics 4', 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google-analytics.png')];
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
