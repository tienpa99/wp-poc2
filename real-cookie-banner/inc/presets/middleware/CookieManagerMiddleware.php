<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\settings\General;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Create manager arguments (GTM, MTM) and tag.
 */
class CookieManagerMiddleware
{
    /**
     * Create manager arguments (GTM, MTM) and tag.
     *
     * @param array $preset
     * @param AbstractCookiePreset $instance
     */
    public function middleware(&$preset, $instance)
    {
        if ($instance === null) {
            return $preset;
        }
        $activeManager = General::getInstance()->getSetCookiesViaManager();
        switch ($activeManager) {
            case 'none':
                $managerExtends = $instance->managerNone();
                if ($managerExtends !== \false) {
                    $preset = \array_merge_recursive($preset, $managerExtends);
                }
                break;
            case 'googleTagManager':
                $managerExtends = $instance->managerGtm();
                if ($managerExtends !== \false) {
                    $preset['tags']['GTM'] = \sprintf(
                        // translators:
                        \__('This cookie template is optimized to work with %s.', RCB_TD),
                        'Google Tag Manager'
                    );
                    $preset = \array_merge_recursive($preset, $managerExtends);
                }
                break;
            case 'matomoTagManager':
                $managerExtends = $instance->managerMtm();
                if ($managerExtends !== \false) {
                    $preset['tags']['MTM'] = \sprintf(
                        // translators:
                        \__('This cookie template is optimized to work with %s.', RCB_TD),
                        'Matomo Tag Manager'
                    );
                    $preset = \array_merge_recursive($preset, $managerExtends);
                }
                break;
            default:
                break;
        }
        return $preset;
    }
}
