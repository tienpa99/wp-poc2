<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Middleware to remove some `attributes` in free content blocker presets which are only available in PRO.
 */
class BlockerDisableProFeaturesInFreeMiddleware
{
    use UtilsProvider;
    /**
     * See class description.
     *
     * @param array $preset
     */
    public function middleware(&$preset)
    {
        if (!$this->isPro() && isset($preset['attributes'])) {
            if (isset($preset['attributes']['visualType'])) {
                unset($preset['attributes']['visualType']);
            }
        }
        return $preset;
    }
}
