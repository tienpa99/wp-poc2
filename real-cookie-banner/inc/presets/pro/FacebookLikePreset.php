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
 * Facebook (Like button) cookie preset.
 */
class FacebookLikePreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::FACEBOOK_LIKE;
    const VERSION = 2;
    const NONCE_LENGTH = 8;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Facebook (Like)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/facebook.png')];
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
    /**
     * Facebook uses a random generated script nonce for caching purposes.
     */
    public static function createScriptNonce()
    {
        $original_string = \array_merge(\range(0, 9), \range('a', 'z'), \range('A', 'Z'));
        $original_string = \implode('', $original_string);
        return \substr(\str_shuffle($original_string), 0, self::NONCE_LENGTH);
    }
}
