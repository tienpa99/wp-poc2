<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use WP_Post;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Middleware that adds a `scanOptions` attribute to the blocker metadata from `rules` options.
 *
 * If you are using this in conjunction with an `extends` middleware, make sure to add this afterwards!
 *
 * See `Rule` for more information.
 */
class BlockerHostsOptionsMiddleware
{
    const EXPRESSION = 'expression';
    const ASSIGNED_TO_GROUPS = 'assignedToGroups';
    const SKIP_IN_GROUP_VALIDATION = 'needsRequiredSiblingRule';
    const QUERY_ARGS = 'queryArgs';
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
        if (isset($preset['attributes'], $preset['attributes']['rules'])) {
            $scanOptions = [];
            foreach ($preset['attributes']['rules'] as $key => $host) {
                if (\is_array($host)) {
                    $scanOptions[] = $host;
                    $preset['attributes']['rules'][$key] = $host[self::EXPRESSION];
                } elseif (\is_string($host)) {
                    $scanOptions[] = [self::EXPRESSION => $host];
                }
            }
            if (\count($scanOptions) > 0) {
                $preset['scanOptions'] = $scanOptions;
            }
            // Unique rules as they can be duplicated in rules settings due to different rules options
            $preset['attributes']['rules'] = \array_values(\array_unique($preset['attributes']['rules']));
        }
        return $preset;
    }
}
