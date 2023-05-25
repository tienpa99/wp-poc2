<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\WooCommerceGoogleAnalyticsPreset as PresetsWooCommerceGoogleAnalyticsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WooCommerce Google Analytics Integration preset -> Google Analytics blocker preset.
 */
class WooCommerceGoogleAnalyticsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsWooCommerceGoogleAnalyticsPreset::IDENTIFIER;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'WooCommerce Google Analytics';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Universal Analytics', 'attributes' => ['extends' => \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset::IDENTIFIER], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/woocommerce-google-analytics-integration.png'), 'needs' => PresetsWooCommerceGoogleAnalyticsPreset::needs()];
    }
}
