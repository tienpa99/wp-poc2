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
 * Facebook (Post) cookie preset.
 */
class FacebookPostPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::FACEBOOK_POST;
    const VERSION = 3;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Facebook (Post)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => \__('includes videos', RCB_TD), 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/facebook.png')];
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
