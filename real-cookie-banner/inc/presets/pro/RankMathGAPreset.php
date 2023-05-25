<?php

namespace DevOwl\RealCookieBanner\presets\pro;

use DevOwl\RealCookieBanner\comp\PresetsPluginIntegrations;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\middleware\DisablePresetByNeedsMiddleware;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * RankMath Google Analytics preset -> Google Analytics cookie preset.
 */
class RankMathGAPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::RANKMATH_GA;
    const SLUG_PRO = PresetsPluginIntegrations::SLUG_RANKMATH_SEO_PRO;
    const SLUG_FREE = PresetsPluginIntegrations::SLUG_RANKMATH_SEO_FREE;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\GoogleAnalyticsPreset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'RankMath Google Analytics';
        $extendsAttributes = (new \DevOwl\RealCookieBanner\presets\pro\GoogleAnalyticsPreset())->common();
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => $extendsAttributes['description'], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/rank-math.png'), 'needs' => self::needs()];
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
    // Self-explanatory
    public static function needs()
    {
        return DisablePresetByNeedsMiddleware::generateNeedsForSlugs([self::SLUG_PRO, self::SLUG_FREE]);
    }
}
