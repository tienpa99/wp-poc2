<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\EtrackerWithConsentPreset as PresetsEtrackerWithConsentPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Etracker with consent blocker preset.
 */
class EtrackerWithConsentPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsEtrackerWithConsentPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'etracker';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => \__('Tracking with consent', RCB_TD), 'attributes' => ['rules' => ['script[src*="code.etracker.com"][data-block-cookies="false"]', '_etracker.enableCookies(']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/et-racker.png')];
    }
}
