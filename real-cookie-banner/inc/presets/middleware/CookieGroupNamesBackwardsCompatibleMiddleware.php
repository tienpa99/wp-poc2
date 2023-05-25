<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\settings\CookieGroup;
use WP_Term;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Example: After we have introduced Continuous Localization the `Statistic` wording got changed
 * to `Statistics`. This middleware fixes the `Statistics` wording and check if a term for `Statistic`
 * exists and fallback to this wording as we do not change the original term while updating the plugin.
 */
class CookieGroupNamesBackwardsCompatibleMiddleware
{
    private $getOriginalAndLegacyName;
    /**
     * C'tor.
     *
     * @param callable $getOriginalAndLegacyName A function which returns an `[originalName: string, legacyName: string]` array.
     */
    public function __construct($getOriginalAndLegacyName)
    {
        $this->getOriginalAndLegacyName = $getOriginalAndLegacyName;
    }
    /**
     * See class description.
     *
     * @param array $preset
     */
    public function middleware(&$preset)
    {
        list($originalTermName, $legacyTermName) = \call_user_func($this->getOriginalAndLegacyName);
        if (isset($preset['attributes'], $preset['attributes']['group']) && $preset['attributes']['group'] === $originalTermName) {
            $legacyTerm = \get_term_by('name', $legacyTermName, CookieGroup::TAXONOMY_NAME);
            if ($legacyTerm instanceof WP_Term) {
                $preset['attributes']['group'] = $legacyTermName;
            }
        }
        return $preset;
    }
    // Undocumented
    public static function createMiddlewareStatisticStatics()
    {
        return new \DevOwl\RealCookieBanner\presets\middleware\CookieGroupNamesBackwardsCompatibleMiddleware(function () {
            return [\__('Statistics', RCB_TD), \__('Statistic', RCB_TD)];
        });
    }
}
