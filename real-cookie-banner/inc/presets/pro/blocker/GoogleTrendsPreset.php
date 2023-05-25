<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\GoogleTrendsPreset as PresetsGoogleTrendsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google Trends blocker preset.
 */
class GoogleTrendsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGoogleTrendsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google Trends';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*gstatic.com/trends_nrtr*', '*trends.embed.render*', '*google.com/trends*', '*google.de/trends*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google-trends.png')];
    }
}
