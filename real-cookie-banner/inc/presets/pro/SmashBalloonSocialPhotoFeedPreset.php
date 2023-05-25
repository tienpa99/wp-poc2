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
 * Smash Balloon Social Photo Feed preset -> Instagram cookie preset.
 */
class SmashBalloonSocialPhotoFeedPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::SMASH_BALLOON_SOCIAL_PHOTO_FEED;
    const SLUG_PRO = 'instagram-feed-pro';
    const SLUG_FREE = 'instagram-feed';
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\InstagramPostPreset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Smash Balloon Social Photo Feed';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Instagram Feed', 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/smash-balloon-social-post-feed.png'), 'needs' => self::needs()];
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
