<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\base\UtilsProvider;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Middleware to add `uniqueName` to `attributes`.
 */
class CookiesSetUniqueName
{
    use UtilsProvider;
    /**
     * See class description.
     *
     * @param array $preset
     */
    public function middleware(&$preset)
    {
        if (isset($preset['attributes']) && empty($preset['attributes']['uniqueName'])) {
            $preset['attributes']['uniqueName'] = $preset['id'];
        }
        return $preset;
    }
}
