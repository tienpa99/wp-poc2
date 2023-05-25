<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\FacebookLikePreset as PresetsFacebookLikePreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\middleware\BlockerHostsOptionsMiddleware;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Facebook (Like button) blocker preset.
 */
class FacebookLikePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsFacebookLikePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Facebook (Like)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => \array_merge([[BlockerHostsOptionsMiddleware::EXPRESSION => '*facebook.com/plugins/like.php*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME], [BlockerHostsOptionsMiddleware::EXPRESSION => 'div[class="fb-like"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_FUNCTION_NAME]], \DevOwl\RealCookieBanner\presets\pro\blocker\FacebookPixelPreset::HOSTS_GROUP_SDK_SCRIPT)], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/facebook.png')];
    }
}
