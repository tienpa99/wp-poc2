<?php

namespace DevOwl\RealCookieBanner\templates;

use DevOwl\RealCookieBanner\comp\PresetsPluginIntegrations;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\middlewares\AbstractTemplateMiddleware;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\BlockerTemplate;
use DevOwl\RealCookieBanner\Vendor\DevOwl\ServiceCloudConsumer\templates\ServiceTemplate;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Set `recommended` through a hook / filter for both service and blocker templates.
 */
class RecommendedHooksMiddleware extends AbstractTemplateMiddleware
{
    // Documented in AbstractTemplateMiddleware
    public function beforePersistTemplate($template, &$allTemplates)
    {
        $recommended = $template->consumerData['isRecommended'] ?? \false;
        if (!$recommended) {
            $presetsPluginIntegration = PresetsPluginIntegrations::getInstance();
            $legacyPresetObj = ['id' => $template->identifier];
            if ($template instanceof ServiceTemplate) {
                $recommended = $presetsPluginIntegration->presets_cookies_recommended($recommended, $template->identifier);
                /**
                 * Show a cookie service template in the scanner, or hide it depending on a given condition.
                 *
                 * @hook RCB/Presets/Cookies/Recommended
                 * @param {boolean} $recommended
                 * @param {array} $preset The preset passed as reference
                 * @returns {boolean}
                 * @since 2.11.1
                 * @deprecated Use `RCB/Templates/Recommended` instead!
                 */
                $recommended = \apply_filters('RCB/Presets/Cookies/Recommended', $recommended, $legacyPresetObj);
            } elseif ($template instanceof BlockerTemplate) {
                $recommended = $presetsPluginIntegration->presets_blocker_recommended($recommended, $template->identifier);
                /**
                 * Show a content blocker template in the scanner, or hide it depending on a given condition.
                 *
                 * @hook RCB/Presets/Blocker/Recommended
                 * @param {boolean} $recommended
                 * @param {array} $preset The preset passed as reference
                 * @returns {callable[]}
                 * @since 2.11.1
                 * @deprecated Use `RCB/Templates/Recommended` instead!
                 */
                $preset['recommended'] = \apply_filters('RCB/Presets/Blocker/Recommended', $recommended, $legacyPresetObj);
            }
            /**
             * Show a content blocker / service template in the scanner on a given condition without finding markups.
             *
             * @hook RCB/Templates/Recommended
             * @param {boolean} $recommended
             * @param {ServiceTemplate|BlockerTemplate} $template
             * @returns {boolean}
             * @since 3.16.0
             */
            $recommended = \apply_filters('RCB/Templates/Recommended', $recommended, $template);
            $template->consumerData['isRecommended'] = $recommended;
        }
    }
    // Documented in AbstractTemplateMiddleware
    public function beforeUsingTemplate($template)
    {
        // Silence is golden.
    }
}
