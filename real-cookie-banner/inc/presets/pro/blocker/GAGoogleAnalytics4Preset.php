<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\GAGoogleAnalytics4Preset as PresetsGAGoogleAnalytics4Preset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\GAGoogleAnalyticsPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * GA Google Analytics (V4) preset -> Google Analytics (V4) blocker preset.
 */
class GAGoogleAnalytics4Preset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGAGoogleAnalytics4Preset::IDENTIFIER;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'GA Google Analytics';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Analytics 4', 'attributes' => ['extends' => \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::IDENTIFIER], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/ga-google-analytics.png'), 'needs' => GAGoogleAnalyticsPreset::needs()];
    }
}
