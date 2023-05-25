<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\KomootPreset as PresetsKomootPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Komoot blocker preset.
 */
class KomootPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsKomootPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Komoot';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*komoot.de/*/embed*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/komoot.png')];
    }
}
