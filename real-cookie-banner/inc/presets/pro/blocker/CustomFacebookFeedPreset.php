<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\CustomFacebookFeedPreset as PresetsCustomFacebookFeedPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Custom Facebook Feed (Smash Balloon Social Post Feed) blocker preset.
 */
class CustomFacebookFeedPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsCustomFacebookFeedPreset::IDENTIFIER;
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Custom Facebook Feed';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Smash Balloon Social Post Feed', 'attributes' => ['rules' => [
            'div[class*="cff-wrapper"]',
            '*fbcdn.net*',
            '*fbsbx.com*',
            '*wp-content/plugins/custom-facebook-feed/assets/js/*',
            // old versions + new versions (>= 3.18 => `assets/js`)
            '*wp-content/plugins/custom-facebook-feed-pro/*js/*',
        ]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/smash-balloon-social-post-feed.png'), 'needs' => PresetsCustomFacebookFeedPreset::needs()];
    }
}
