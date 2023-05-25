<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\FacebookSharePreset as PresetsFacebookSharePreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\middleware\BlockerHostsOptionsMiddleware;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Facebook (share button) blocker preset.
 */
class FacebookSharePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsFacebookSharePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Facebook (Share)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => \array_merge([[BlockerHostsOptionsMiddleware::EXPRESSION => '*facebook.com/plugins/share_button.php*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME], [BlockerHostsOptionsMiddleware::EXPRESSION => 'div[class="fb-share-button"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME]], \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_SCRIPT)], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/facebook.png')];
    }
}
