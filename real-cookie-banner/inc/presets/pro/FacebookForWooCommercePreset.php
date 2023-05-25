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
 * Facebook For WooCommerce preset -> Facebook Pixel cookie preset.
 */
class FacebookForWooCommercePreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::FACEBOOK_FOR_WOOCOMMERCE;
    const SLUG = 'facebook-for-woocommerce';
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\FacebookPixelPreset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Facebook for WooCommerce';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Facebook Pixel', 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/facebook.png'), 'needs' => self::needs()];
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
