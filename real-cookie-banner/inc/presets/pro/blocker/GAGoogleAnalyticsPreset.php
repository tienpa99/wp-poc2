<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\GAGoogleAnalyticsPreset as PresetsGAGoogleAnalyticsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * GA Google Analytics preset -> Google Analytics blocker preset.
 */
class GAGoogleAnalyticsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGAGoogleAnalyticsPreset::IDENTIFIER;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'GA Google Analytics';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Universal Analytics', 'attributes' => ['extends' => \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset::IDENTIFIER], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/ga-google-analytics.png'), 'needs' => PresetsGAGoogleAnalyticsPreset::needs()];
    }
}
