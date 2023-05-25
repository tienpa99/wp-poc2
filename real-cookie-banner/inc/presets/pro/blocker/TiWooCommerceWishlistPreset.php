<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
use DevOwl\RealCookieBanner\presets\pro\TiWooCommerceWishlistPreset as ProTiWooCommerceWishlistPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * TI WooCommerce Wishlist blocker preset.
 */
class TiWooCommerceWishlistPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetIdentifierMap::TI_WOOCOMMERCE_WISHLIST;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => 'TI WooCommerce Wishlist', 'attributes' => ['rules' => ['*wp-content/plugins/ti-woocommerce-wishlist/*', 'span[class*="wishlist_products_counter"]', 'a[class*="tinvwl_add_to_wishlist_button"]', 'div[class*="rey-accountWishlist-wrapper"]', 'script[id="tinvwl-js-after"]', 'script[id="reycore-wishlist-js"]', 'li[class*="woocommerce-MyAccount-navigation-link--tinv_wishlist"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/ti-woocommerce-wishlist.png'), 'needs' => ProTiWooCommerceWishlistPreset::needs()];
    }
}
