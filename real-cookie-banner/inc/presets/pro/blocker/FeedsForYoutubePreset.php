<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\FeedsForYoutubePreset as PresetsFeedsForYoutubePreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\free\blocker\YoutubePreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Feeds for YouTube (YouTube video, channel, and gallery plugin) blocker preset.
 */
class FeedsForYoutubePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsFeedsForYoutubePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Feeds for YouTube';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'YouTube Video, Channel, Gallery Plugin by Smash Balloon', 'attributes' => ['extends' => YoutubePreset::IDENTIFIER, 'extendsRulesStart' => ['div[class*="sb_youtube "]', '*yt3.ggpht.com*'], 'extendsRulesEnd' => ['*wp-content/plugins/feeds-for-youtube/js/*', '*wp-content/plugins/youtube-feed-pro/js/*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/smash-balloon-feeds-for-youtube.png'), 'needs' => PresetsFeedsForYoutubePreset::needs()];
    }
}
