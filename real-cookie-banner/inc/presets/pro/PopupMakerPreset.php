<?php

namespace DevOwl\RealCookieBanner\presets\pro;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\middleware\DisablePresetByNeedsMiddleware;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Popup Maker cookie preset.
 */
class PopupMakerPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::POPUP_MAKER;
    const SLUG = 'popup-maker';
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Popup Maker';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/popup-maker.png'), 'needs' => self::needs()];
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
    // Self-explanatory
    public static function needs()
    {
        return DisablePresetByNeedsMiddleware::generateNeedsForSlugs([self::SLUG]);
    }
}
