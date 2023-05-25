<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\TawkToChatPreset as PresetsTawkToChatPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Tawk.to Chat blocker preset.
 */
class TawkToChatPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsTawkToChatPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'tawk.to (Chat)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*embed.tawk.to*', 'Tawk_API']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/tawk.png')];
    }
}
