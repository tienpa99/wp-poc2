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
 * RankMath Google Analytics preset -> Google Analytics cookie preset.
 */
class RankMathGA4Preset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::RANKMATH_GA_4;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\GoogleAnalytics4Preset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'RankMath Google Analytics';
        $extendsAttributes = (new \DevOwl\RealCookieBanner\presets\pro\GoogleAnalytics4Preset())->common();
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => $extendsAttributes['description'], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/rank-math.png'), 'needs' => \DevOwl\RealCookieBanner\presets\pro\RankMathGAPreset::needs()];
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
