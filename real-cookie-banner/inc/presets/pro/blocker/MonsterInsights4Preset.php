<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\MonsterInsights4Preset as PresetsMonsterInsights4Preset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\MonsterInsightsPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * MonsterInsights preset -> Google Analytics (V4) blocker preset.
 */
class MonsterInsights4Preset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsMonsterInsights4Preset::IDENTIFIER;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'MonsterInsights';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Analytics 4', 'attributes' => ['extends' => \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::IDENTIFIER], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/monster-insights.png'), 'needs' => MonsterInsightsPreset::needs()];
    }
}
