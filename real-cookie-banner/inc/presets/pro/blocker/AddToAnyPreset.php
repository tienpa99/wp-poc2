<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\AddToAnyPreset as PresetsAddToAnyPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * AddToAny Share Buttons blocker preset.
 */
class AddToAnyPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsAddToAnyPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'AddToAny';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'description' => 'Share Buttons', 'name' => $name, 'attributes' => ['rules' => ['*static.addtoany.com*', 'div[class*="addtoany_share_save_container"]', 'div[class*="addtoany_list"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/add-to-any.png')];
    }
}
