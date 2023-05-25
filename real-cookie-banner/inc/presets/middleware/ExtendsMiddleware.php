<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\Presets;
use DevOwl\RealCookieBanner\Utils;
use WP_Post;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Middleware to enable `attributes.extends` in cookie and content blocker presets.
 */
class ExtendsMiddleware
{
    /**
     * `attributes` can contain a magic key `extends` with a class name. Let's extend
     * from that attributes.
     *
     * @param array $preset
     * @param AbstractBlockerPreset|AbstractCookiePreset $unused0
     * @param WP_Post[] $unused1
     * @param WP_Post[] $unused2
     * @param array $result
     * @param Presets $presetsInstance
     */
    public function middleware(&$preset, $unused0, $unused1, $unused2, &$result, $presetsInstance)
    {
        if (isset($preset['attributes']) && isset($preset['attributes']['extends'])) {
            $parentIdentifier = $preset['attributes']['extends'];
            $parent = $result[$parentIdentifier] ?? null;
            // Parent identifier does not exist
            if (!isset($parent)) {
                // Check if we can request it
                $parent = $presetsInstance->getWithAttributes($parentIdentifier);
                if ($parent === \false) {
                    return $preset;
                }
            }
            $preset['attributes'] = \array_merge($parent['attributes'], $preset['attributes']);
            unset($preset['attributes']['extends']);
            // Mark this preset as `extended` so we can use this e.g. in the scanner to prioritize this
            // over the parent. Example: MonsterInsights > Google Analytics
            $preset['extended'] = $parent['id'];
            // Allow extending single properties (useful for arrays)
            foreach ($preset['attributes'] as $extendsKey => $extendsValue) {
                if (Utils::startsWith($extendsKey, 'extends')) {
                    foreach ($preset['attributes'] as $key => $value) {
                        if ($extendsKey === \sprintf('extends%sStart', \ucfirst($key))) {
                            $preset['attributes'][$key] = \array_merge($extendsValue, $value);
                        } elseif ($extendsKey === \sprintf('extends%sEnd', \ucfirst($key))) {
                            $preset['attributes'][$key] = \array_merge($value, $extendsValue);
                        } else {
                            continue;
                        }
                        unset($preset['attributes'][$extendsKey]);
                    }
                }
            }
            // Allow overwriting single properties completely
            foreach ($preset['attributes'] as $overwriteKey => $overwriteValue) {
                if (Utils::startsWith($overwriteKey, 'overwrite')) {
                    foreach ($preset['attributes'] as $key => $value) {
                        if ($overwriteKey === \sprintf('overwrite%s', \ucfirst($key))) {
                            $preset['attributes'][$key] = $overwriteValue;
                        } else {
                            continue;
                        }
                        unset($preset['attributes'][$overwriteKey]);
                    }
                }
            }
        }
        return $preset;
    }
}
