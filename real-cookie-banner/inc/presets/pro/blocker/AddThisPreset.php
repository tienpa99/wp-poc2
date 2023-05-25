<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\AddThisPreset as PresetsAddThisPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * AddThis Share Buttons blocker preset.
 */
class AddThisPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsAddThisPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'AddThis (Share)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*addthis.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/addthis.png')];
    }
}
