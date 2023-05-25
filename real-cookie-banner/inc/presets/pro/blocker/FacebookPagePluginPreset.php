<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\FacebookPagePluginPreset as PresetsFacebookPagePluginPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\middleware\BlockerHostsOptionsMiddleware;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Facebook Page Plugin blocker preset.
 */
class FacebookPagePluginPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsFacebookPagePluginPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Facebook Page Plugin';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => \array_merge([
            [BlockerHostsOptionsMiddleware::EXPRESSION => '*facebook.com/plugins/page.php*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME],
            [BlockerHostsOptionsMiddleware::EXPRESSION => '*fbcdn.net*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME],
            [BlockerHostsOptionsMiddleware::EXPRESSION => 'div[class="fb-page"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME],
            // [Plugin Comp] Elementor PRO
            [BlockerHostsOptionsMiddleware::EXPRESSION => 'div[class*="elementor-widget-facebook-page"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME],
        ], \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_SCRIPT)], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/facebook.png')];
    }
}
