<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\TwitterTweetPreset as PresetsTwitterTweetPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Twitter (tweet) blocker preset.
 */
class TwitterTweetPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsTwitterTweetPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Twitter (Tweet)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*twitter.com*', 'http://t.co*', 'https://t.co*', '*twimg.com*', 'blockquote[class="twitter-tweet"]', 'a[class*="twitter-timeline"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/twitter.png')];
    }
}
