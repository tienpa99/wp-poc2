<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\GoogleTranslatePreset as PresetsGoogleTranslatePreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google Translate blocker preset.
 */
class GoogleTranslatePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGoogleTranslatePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google Translate';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => [
            '*translate.google.com*',
            '*translate.googleapis.com*',
            // [Plugin Comp] https://wordpress.org/plugins/gtranslate/
            'div[id="gtranslate_wrapper"]',
            'li[class*="menu-item-gtranslate"]',
            // [Plugin Comp] https://wordpress.org/plugins/google-language-translator/
            'div[id="glt-translate-trigger"]',
            'link[id="google-language-translator-css"]',
            'link[id="glt-toolbar-styles-css"]',
            '*plugins/google-language-translator*',
        ]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google-translate.png')];
    }
}
