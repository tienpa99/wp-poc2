<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use ReflectionClass;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Middleware to automatically create `tier` from class namespace.
 */
class AdoptTierFromClassNamespaceMiddleware
{
    const TIER_PRO = 'pro';
    const TIER_FREE = 'free';
    /**
     * Adopt `tier` from class name.
     *
     * @param array $preset
     * @param AbstractCookiePreset|AbstractBlockerPreset $instance
     */
    public function middleware(&$preset, $instance)
    {
        if (!isset($preset['tier']) && $instance !== null) {
            $declaringFileName = (new ReflectionClass(\get_class($instance)))->getFileName();
            // Windows compatibility: consider backslashes as forward-slashes
            $declaringFileName = \str_replace('\\', '/', $declaringFileName);
            $isProFileName = \strpos($declaringFileName, 'overrides/pro/presets') !== \false || \strpos($declaringFileName, 'presets/pro') !== \false;
            $preset['tier'] = !$isProFileName ? self::TIER_FREE : self::TIER_PRO;
        }
        return $preset;
    }
}
