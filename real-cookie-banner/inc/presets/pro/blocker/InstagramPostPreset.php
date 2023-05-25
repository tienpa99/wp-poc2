<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\InstagramPostPreset as PresetsInstagramPostPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Instgram (post) blocker preset.
 */
class InstagramPostPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsInstagramPostPreset::IDENTIFIER;
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Instagram (Post)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => [
            '*instagram.com*',
            '*cdninstagram.com*',
            'blockquote[class="instagram-media"]',
            // [Plugin Comp] https://wordpress.org/plugins/meks-easy-instagram-widget/
            'div[class="meks-instagram-widget"]',
            // [Theme Comp] https://themeforest.net/item/woodmart-woocommerce-wordpress-theme/20264492
            'div[style*="cdninstagram.com"]',
            'div[class*="instagram-widget"]',
            // [Theme Comp] https://tagdiv.com/
            'div[class*="td-instagram-wrap"]',
            'a[style*="cdninstagram.com"]',
            // [Plugin Comp] https://wordpress.org/plugins/insta-gallery/
            '*/wp-content/plugins/insta-gallery/assets/frontend/js/*',
            '*/wp-content/plugins/insta-gallery/build/frontend/js/*',
            'div[class*="insta-gallery-feed"]',
        ]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/instagram.png')];
    }
}
