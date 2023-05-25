<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\MicrosoftClarityPreset as PresetsMicrosoftClarityPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Microsoft Clarity blocker preset.
 */
class MicrosoftClarityPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsMicrosoftClarityPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Microsoft Clarity';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*clarity.ms/tag*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/microsoft.png')];
    }
}
