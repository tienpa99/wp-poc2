<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\UserlikePreset as PresetsUserlikePreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Userlike blocker preset.
 */
class UserlikePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsUserlikePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Userlike (Widget)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*userlike-cdn*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/userlike.png')];
    }
}
