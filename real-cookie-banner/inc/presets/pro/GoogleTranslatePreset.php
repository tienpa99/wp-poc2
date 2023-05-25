<?php

namespace DevOwl\RealCookieBanner\presets\pro;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google Translate cookie preset.
 */
class GoogleTranslatePreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::GOOGLE_TRANSLATE;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google Translate';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google-translate.png')];
    }
    // Documented in AbstractPreset
    public function managerNone()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerGtm()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerMtm()
    {
        return \false;
    }
}
