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
 * Analytify preset -> Google Analytics (Analytics 4) cookie preset.
 */
class Analytify4Preset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::ANALYTIFY_4;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\GoogleAnalytics4Preset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Analytify';
        $extendsAttributes = (new \DevOwl\RealCookieBanner\presets\pro\GoogleAnalytics4Preset())->common();
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => $extendsAttributes['description'], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/analytify.png'), 'needs' => \DevOwl\RealCookieBanner\presets\pro\AnalytifyPreset::needs()];
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
