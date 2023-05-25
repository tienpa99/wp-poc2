<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
use DevOwl\RealCookieBanner\presets\pro\KlaviyoPreset as ProKlaviyoPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Active Campaign with Google reCAPTCHA blocker preset.
 */
class KlaviyoPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetIdentifierMap::KLAVIYO;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => 'Klaviyo', 'attributes' => ['rules' => ['*static.klaviyo.com*', '*wp-content/plugins/klaviyo/*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/klaviyo.png'), 'needs' => ProKlaviyoPreset::needs()];
    }
}
