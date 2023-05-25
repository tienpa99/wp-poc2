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
 * WooCommerce (Geolocation) cookie preset.
 */
class WooCommerceGeolocationPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::WOOCOMMERCE_GEOLOCATION;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'WooCommerce (Geolocation)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/woocommerce.png'), 'needs' => \DevOwl\RealCookieBanner\presets\pro\WooCommercePreset::needs()];
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
