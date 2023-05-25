<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\CalendlyPreset as PresetsCalendlyPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Calendly blocker preset.
 */
class CalendlyPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsCalendlyPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Calendly';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['div[class="calendly-inline-widget"]', 'a[onclick*="Calendly.initPopupWidget"]', 'Calendly.initBadgeWidget', 'Calendly.initPopupWidget', '*assets.calendly.com*', '*calendly.com/assets/*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/calendly.png')];
    }
}
