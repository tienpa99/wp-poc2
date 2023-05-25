<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\RankMathGAPreset as PresetsRankMathGAPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * RankMath Google Analytics preset -> Google Analytics blocker preset.
 */
class RankMathGAPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsRankMathGAPreset::IDENTIFIER;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'RankMath Google Analytics';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Universal Analytics', 'attributes' => ['extends' => \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset::IDENTIFIER], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/rank-math.png'), 'needs' => PresetsRankMathGAPreset::needs()];
    }
}
