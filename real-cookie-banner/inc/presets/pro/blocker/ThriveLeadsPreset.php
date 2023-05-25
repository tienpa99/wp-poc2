<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\ThriveLeadsPreset as PresetsThriveLeadsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Thrive Leads blocker preset.
 */
class ThriveLeadsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsThriveLeadsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Thrive Leads';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*plugins/thrive-leads*', '*tve_leads*', 'window.TL_Front', 'div[data-tl-type="ribbon"]', 'div[class*="tve-leads-screen-filler"]', 'a[data-tcb-events*="thrive_leads":keepAttributes(value=data-tcb-events),delegateClick()]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/thrive-leads.png'), 'needs' => PresetsThriveLeadsPreset::needs()];
    }
}
