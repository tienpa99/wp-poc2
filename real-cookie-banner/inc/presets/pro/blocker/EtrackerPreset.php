<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\EtrackerPreset as PresetsEtrackerPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Etracker blocker preset.
 */
class EtrackerPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsEtrackerPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'etracker';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => \__('Tracking without consent', RCB_TD), 'attributes' => ['rules' => ['script[src*="code.etracker.com"][data-block-cookies="true"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/et-racker.png')];
    }
}
