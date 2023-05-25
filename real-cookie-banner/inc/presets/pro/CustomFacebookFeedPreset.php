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
 * Custom Facebook Feed (Smash Balloon Social Post Feed) preset.
 */
class CustomFacebookFeedPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::CUSTOM_FACEBOOK_FEED;
    const SLUG_PRO = 'custom-facebook-feed-pro';
    const SLUG_FREE = 'custom-facebook-feed';
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Custom Facebook Feed';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Smash Balloon Social Post Feed', 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/smash-balloon-social-post-feed.png'), 'needs' => self::needs()];
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
        return DisablePresetByNeedsMiddleware::generateNeedsForSlugs([self::SLUG_PRO, self::SLUG_FREE]);
    }
}
