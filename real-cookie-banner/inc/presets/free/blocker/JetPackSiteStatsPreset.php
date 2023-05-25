<?php

namespace DevOwl\RealCookieBanner\presets\free\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\free\JetpackSiteStatsPreset as FreeJetpackSiteStatsPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Jetpack Site Stats blocker preset.
 */
class JetPackSiteStatsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = FreeJetpackSiteStatsPreset::IDENTIFIER;
    const VERSION = 3;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Jetpack Site Stats';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/jetpack.png'), 'needs' => FreeJetpackSiteStatsPreset::needs(), 'attributes' => ['name' => $name, 'rules' => ['*pixel.wp.com*', '*stats.wp.com*'], 'serviceTemplates' => [FreeJetpackSiteStatsPreset::IDENTIFIER], 'isVisual' => \false]];
    }
}
