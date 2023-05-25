<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\HotjarPreset as PresetsHotjarPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Hotjar blocker preset.
 */
class HotjarPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsHotjarPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Hotjar';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*static.hotjar.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/hotjar.png')];
    }
}
