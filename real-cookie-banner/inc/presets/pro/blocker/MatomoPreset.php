<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\MatomoPreset as ProMatomoPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Matomo blocker preset.
 */
class MatomoPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = ProMatomoPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Matomo';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Hosted / Cloud', 'hidden' => \true, 'attributes' => ['rules' => ['_paq', '*matomo.php*'], 'serviceTemplates' => [ProMatomoPreset::IDENTIFIER]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/matomo.png')];
    }
}
