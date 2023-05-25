<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\FacebookGraphPreset as PresetsFacebookGraphPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Facebook Graph blocker preset.
 */
class FacebookGraphPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsFacebookGraphPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Facebook Graph';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*graph.facebook.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/facebook.png')];
    }
}
