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
 * WP-Matomo Integration (former WP-Piwik) cookie preset.
 */
class MatomoIntegrationPluginPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::MATOMO_INTEGRATION_PLUGIN;
    const SLUG = PresetsPluginIntegrations::SLUG_WP_PIWIK;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'WP-Matomo Integration';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => \__('former WP-Piwik', RCB_TD), 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/wp-matomo-integration.png'), 'recommended' => \true, 'needs' => self::needs()];
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
