<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\settings\Blocker;
use WP_Post;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Middleware that provides button text for specific content types.
 */
class BlockerContentTypeButtonTextMiddleware
{
    /**
     * See class description.
     *
     * @param array $preset
     * @param AbstractBlockerPreset|AbstractCookiePreset $unused0
     * @param WP_Post[] $unused1
     * @param WP_Post[] $unused2
     * @param array $result
     */
    public function middleware(&$preset, $unused0, $unused1, $unused2, &$result)
    {
        if (isset($preset['attributes'])) {
            $buttonText = \__('Load content', Hooks::TD_FORCED);
            $contentType = $preset['attributes'][Blocker::META_NAME_VISUAL_CONTENT_TYPE] ?? null;
            if (\in_array($contentType, ['video-player', 'audio-player'], \true)) {
                $buttonText = null;
            } elseif ($contentType === 'map') {
                $buttonText = \__('Load map', Hooks::TD_FORCED);
            } elseif ($contentType === 'generic' && \stripos($preset['id'], 'form') !== \false) {
                $buttonText = \__('Load form', Hooks::TD_FORCED);
            }
            if (!empty($buttonText)) {
                $preset['attributes'][Blocker::META_NAME_VISUAL_HERO_BUTTON_TEXT] = $buttonText;
            }
        }
        return $preset;
    }
}
