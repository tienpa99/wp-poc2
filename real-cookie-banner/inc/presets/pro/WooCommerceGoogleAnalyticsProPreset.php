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
 * WooCommerce Google Analytics Pro preset -> Google Analytics cookie preset.
 */
class WooCommerceGoogleAnalyticsProPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::WOOCOMMERCE_GOOGLE_ANALYTICS_PRO;
    const SLUG = PresetsPluginIntegrations::SLUG_WOOCOMMERCE_GOOGLE_ANALYTICS_PRO;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\GoogleAnalyticsPreset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'WooCommerce Google Analytics Pro';
        $extendsAttributes = (new \DevOwl\RealCookieBanner\presets\pro\GoogleAnalyticsPreset())->common();
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => $extendsAttributes['description'], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/woocommerce-ga-pro.png'), 'needs' => self::needs()];
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
