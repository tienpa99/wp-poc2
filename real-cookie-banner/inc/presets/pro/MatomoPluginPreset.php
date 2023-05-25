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
 * Matomo Plugin cookie preset.
 */
class MatomoPluginPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::MATOMO_PLUGIN;
    const SLUG = PresetsPluginIntegrations::SLUG_MATOMO_PLUGIN;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Matomo';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'WordPress Plugin', 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/matomo.png'), 'needs' => self::needs()];
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
        return DisablePresetByNeedsMiddleware::generateNeedsForSlugs([self::SLUG]);
    }
}
