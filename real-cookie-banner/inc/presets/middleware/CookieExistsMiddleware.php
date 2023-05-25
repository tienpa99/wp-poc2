<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\settings\Blocker;
use WP_Post;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Middleware to add a tag with label when the preset already exists.
 */
class CookieExistsMiddleware
{
    /**
     * See class description.
     *
     * @param array $preset
     * @param AbstractCookiePreset $instance Preset instance
     * @param WP_Post[] $existingCookies
     */
    public function middleware(&$preset, $instance, $existingCookies)
    {
        foreach ($existingCookies as $cookie) {
            if ($cookie->metas[Blocker::META_NAME_PRESET_ID] === $preset['id']) {
                $labelAlreadyCreated = \__('Already created', RCB_TD);
                $tooltipAlreadyCreated = \__('You have already created a cookie with this template.', RCB_TD);
                $preset['tags'][$labelAlreadyCreated] = $tooltipAlreadyCreated;
                $preset['created'] = \true;
                break;
            }
        }
        return $preset;
    }
}
