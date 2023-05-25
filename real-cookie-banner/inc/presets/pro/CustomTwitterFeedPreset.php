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
 * Custom Twitter Feed (Custom Twitter Feeds (Tweets Widget)) preset.
 */
class CustomTwitterFeedPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::CUSTOM_TWITTER_FEED;
    const SLUG_PRO = 'custom-twitter-feeds-pro';
    const SLUG_FREE = 'custom-twitter-feeds';
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Custom Twitter Feeds';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Tweets Widget by Smash Balloon', 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/smash-balloon-custom-twitter-feeds.png'), 'needs' => self::needs()];
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
