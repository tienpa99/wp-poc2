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
 * Feeds for YouTube (YouTube video, channel, and gallery plugin) preset.
 */
class FeedsForYoutubePreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::FEEDS_FOR_YOUTUBE;
    const SLUG_PRO = 'youtube-feed-pro';
    const SLUG_FREE = 'feeds-for-youtube';
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Feeds for YouTube';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'YouTube Video, Channel, Gallery Plugin by Smash Balloon', 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/smash-balloon-feeds-for-youtube.png'), 'needs' => self::needs()];
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
