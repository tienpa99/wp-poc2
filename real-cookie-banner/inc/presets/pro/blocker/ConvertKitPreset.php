<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\ConvertKitPreset as PresetsConvertKitPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * ConvertKit blocker preset.
 */
class ConvertKitPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsConvertKitPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'ConvertKit';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*/wp-content/plugins/convertkit*', '*ck.page*', 'script[id="convertkit-js-js-extra"]', 'script[id="convertkit-js-js"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/convertkit.png')];
    }
}
