<?php

namespace DevOwl\RealCookieBanner\presets\pro;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\middleware\DisablePresetByNeedsMiddleware;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WooCommerce Stripe Payment Gateway preset -> Stripe cookie preset.
 */
class WooCommerceGatewayStripePreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::WOOCOMMERCE_GATEWAY_STRIPE;
    const SLUG = 'woocommerce-gateway-stripe';
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\StripePreset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'WooCommerce Stripe Payment Gateway';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/woocommerce-gateway-stripe.png'), 'recommended' => \true, 'needs' => self::needs()];
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
