<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\ProvenExpertWidgetPreset as PresetsProvenExpertWidgetPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Proven Expert Widget blocker preset.
 */
class ProvenExpertWidgetPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsProvenExpertWidgetPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Proven Expert (Widget)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => [
            '*images.provenexpert.com*',
            '*provenexpert.com/widget*',
            '*provenexpert.com/css*',
            // https://lp.provenexpert.com/de/provenexpert-pro-seal/
            '*provenexpert.net/seals*',
            'provenExpert.proSeal',
            '*provenexpert.com/badge*',
        ]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/provenexpert.png')];
    }
}
