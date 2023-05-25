<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\FacebookForWooCommercePreset as PresetsFacebookForWooCommercePreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Facebook For WooCommerce preset -> Google Analytics blocker preset.
 */
class FacebookForWooCommercePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsFacebookForWooCommercePreset::IDENTIFIER;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Facebook for WooCommerce';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Facebook Pixel', 'attributes' => ['extends' => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::IDENTIFIER], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/facebook.png'), 'needs' => PresetsFacebookForWooCommercePreset::needs()];
    }
}
