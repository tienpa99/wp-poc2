<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\PopupMakerPreset as PresetsPopupMakerPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Popup Maker blocker preset.
 */
class PopupMakerPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsPopupMakerPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Popup Maker';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*wp-content/plugins/popup-maker*', '*pum-site-scripts.js*', '*pum-site-styles.css*', 'div[class*="pum-overlay"]', 'style[id="popup-maker-site-css"]', 'style[id="popup-maker-site-inline-css"]', 'script[id="popup-maker-site-js-extra"]', 'script[id="popup-maker-site-js"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/popup-maker.png'), 'needs' => PresetsPopupMakerPreset::needs()];
    }
}
