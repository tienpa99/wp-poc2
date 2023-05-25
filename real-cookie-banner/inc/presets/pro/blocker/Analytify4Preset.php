<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\Analytify4Preset as PresetsAnalytify4Preset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\AnalytifyPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Analytify preset -> Google Analytics (V4) blocker preset.
 */
class Analytify4Preset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsAnalytify4Preset::IDENTIFIER;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Analytify';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Analytics 4', 'attributes' => ['extends' => \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::IDENTIFIER], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/analytify.png'), 'needs' => AnalytifyPreset::needs()];
    }
}
