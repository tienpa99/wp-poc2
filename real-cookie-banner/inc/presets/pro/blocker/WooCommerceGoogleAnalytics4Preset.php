<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\WooCommerceGoogleAnalytics4Preset as PresetsWooCommerceGoogleAnalytics4Preset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\WooCommerceGoogleAnalyticsPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WooCommerce Google Analytics Integration (V4) preset -> Google Analytics (V4) blocker preset.
 */
class WooCommerceGoogleAnalytics4Preset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsWooCommerceGoogleAnalytics4Preset::IDENTIFIER;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'WooCommerce Google Analytics';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Analytics 4', 'attributes' => ['extends' => \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::IDENTIFIER], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/woocommerce-google-analytics-integration.png'), 'needs' => WooCommerceGoogleAnalyticsPreset::needs()];
    }
}
