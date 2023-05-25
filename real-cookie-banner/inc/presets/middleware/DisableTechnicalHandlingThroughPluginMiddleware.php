<?php

namespace DevOwl\RealCookieBanner\presets\middleware;

use DevOwl\RealCookieBanner\settings\Cookie;
use DevOwl\RealCookieBanner\Vendor\DevOwl\RealProductManagerWpClient\Utils;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Middleware to enable `attributes.shouldRemoveTechnicalHandlingWhenOneOf` in cookie and content blocker presets.
 */
class DisableTechnicalHandlingThroughPluginMiddleware
{
    /**
     * Pass preset metadata with attributes and disable the technical handling attributes when
     * a given plugin is active.
     *
     * @param array $preset
     */
    public function middleware(&$preset)
    {
        if (isset($preset['attributes']) && isset($preset['attributes']['shouldRemoveTechnicalHandlingWhenOneOf'])) {
            $plugins = $preset['attributes']['shouldRemoveTechnicalHandlingWhenOneOf'];
            if (\DevOwl\RealCookieBanner\presets\middleware\DisablePresetByNeedsMiddleware::check($plugins, $preset['id'], 'service', $slug)) {
                // Deactivate all technical handling
                foreach (Cookie::TECHNICAL_HANDLING_META_COLLECTION as $key) {
                    if (isset($preset['attributes'][$key])) {
                        unset($preset['attributes'][$key]);
                    }
                }
                // Show a notice to the user
                $oldNotice = $preset['attributes']['technicalHandlingNotice'] ?? '';
                $preset['attributes']['technicalHandlingNotice'] = \sprintf(
                    // translators:
                    \__('You don\'t have to define a technical handling here, because this is done by the plugin <strong>%s</strong>.', RCB_TD),
                    Utils::getActivePluginsMap()[$slug]
                ) . (empty($oldNotice) ? '' : \sprintf('<br /><br />%s', $oldNotice));
            }
            unset($preset['attributes']['shouldRemoveTechnicalHandlingWhenOneOf']);
        }
        return $preset;
    }
}
