<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\GiphyPreset as PresetsGiphyPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Giphy blocker preset.
 */
class GiphyPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGiphyPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Giphy';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*giphy.com/embed*', 'a[href*="giphy.com/gifs"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/giphy.png')];
    }
}
