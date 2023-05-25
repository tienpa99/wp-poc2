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
 * Tawk.to Chat cookie preset.
 */
class TawkToChatPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::TAWK_TO_CHAT;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'tawk.to (Chat)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/tawk.png')];
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
