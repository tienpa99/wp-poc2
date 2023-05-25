<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\TaboolaPreset as PresetsTaboolaPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Taboola blocker preset.
 */
class TaboolaPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsTaboolaPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Taboola';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['script[id="taboola-widget"]', '*window._taboola*', '*_taboola.push*', '*cdn.taboola.com*', 'div[class*="widget_taboola"]', 'div[id="taboola-below-article"]', 'div[id="taboola-below-article-second"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/taboola.png')];
    }
}
