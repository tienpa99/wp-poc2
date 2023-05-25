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
 * Klarna Checkout for WooCommerce cookie preset.
 */
class KlarnaCheckoutWooCommercePreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::KLARNA_CHECKOUT_WOOCOMMERCE;
    const SLUG = 'klarna-checkout-for-woocommerce';
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Klarna Checkout for WooCommerce';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/klarna-checkout-for-woocommerce.png'), 'recommended' => \true, 'needs' => self::needs()];
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
