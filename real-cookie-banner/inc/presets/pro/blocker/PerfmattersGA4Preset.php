<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\PerfmattersGA4Preset as PresetsPerfmattersGA4Preset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\PerfmattersGAPreset as ProPerfmattersGAPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Perfmatters Google Analytics Integration (V4) preset -> Google Analytics (V4) blocker preset.
 */
class PerfmattersGA4Preset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsPerfmattersGA4Preset::IDENTIFIER;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::VERSION;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Perfmatters Google Analytics';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Analytics 4', 'attributes' => [
            'extends' => \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::IDENTIFIER,
            // Overwrite rules completely as we do not need any `must` logic here
            'overwriteRules' => \array_merge(\DevOwl\RealCookieBanner\presets\pro\blocker\PerfmattersGAPreset::BLOCKABLE_SCRIPTS, \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalytics4Preset::HOSTS_GROUP_SCRIPT_PROPERTY),
        ], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/perfmatters.png'), 'needs' => ProPerfmattersGAPreset::needs()];
    }
}
