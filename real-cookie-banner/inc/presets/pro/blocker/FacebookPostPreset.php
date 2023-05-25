<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\FacebookPostPreset as PresetsFacebookPostPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\middleware\BlockerHostsOptionsMiddleware;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Facebook (Post) blocker preset.
 */
class FacebookPostPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsFacebookPostPreset::IDENTIFIER;
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Facebook (Post)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => \__('includes videos', RCB_TD), 'attributes' => ['rules' => \array_merge(
            // [Plugin Comp] Jetpack Facebook Embed
            ['*/wp-content/plugins/jetpack/_inc/build/facebook-embed*'],
            [[BlockerHostsOptionsMiddleware::EXPRESSION => '*facebook.com/plugins/post.php*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME], [BlockerHostsOptionsMiddleware::EXPRESSION => '*facebook.com/plugins/video.php*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME], [BlockerHostsOptionsMiddleware::EXPRESSION => '*fbcdn.net*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME], [BlockerHostsOptionsMiddleware::EXPRESSION => 'div[class="fb-post"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME]],
            \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_SCRIPT
        )], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/facebook.png')];
    }
}
