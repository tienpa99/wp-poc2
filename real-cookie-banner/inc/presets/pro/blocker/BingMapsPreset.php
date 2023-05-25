<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\BingMapsPreset as PresetsBingMapsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Bing Maps blocker preset.
 */
class BingMapsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsBingMapsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Bing Maps';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => [
            '*bing.com/maps*',
            // [Plugin Comp] https://themify.me/addons/maps-pro
            'div[data-map-provider="bing"]',
        ]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/bing.png')];
    }
}
