<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\PerfmattersGAPreset as PresetsPerfmattersGAPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Perfmatters Google Analytics preset -> Google Analytics blocker preset.
 */
class PerfmattersGAPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsPerfmattersGAPreset::IDENTIFIER;
    const VERSION = \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset::VERSION;
    const BLOCKABLE_SCRIPTS = [
        // Minimal (inline)
        '*google-analytics.com/collect*',
        // Old scripts
        '*/wp-content/plugins/perfmatters/gtag.js*',
        '*/wp-content/plugins/perfmatters/gtagv4.js*',
        '*/wp-content/plugins/perfmatters/js/analytics.js*',
        '*/wp-content/plugins/perfmatters/js/analytics-minimal.js*',
        '*/wp-content/plugins/perfmatters/js/analytics-minimal-v4.js*',
        // New scripts (>= 1.8)
        '*/wp-content/uploads/perfmatters/*',
    ];
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Perfmatters Google Analytics';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Universal Analytics', 'attributes' => [
            'extends' => \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset::IDENTIFIER,
            // Overwrite rules completely as we do not need any `must` logic here
            'overwriteRules' => \array_merge(self::BLOCKABLE_SCRIPTS, \DevOwl\RealCookieBanner\presets\pro\blocker\GoogleAnalyticsPreset::HOSTS_GROUP_SCRIPT_PROPERTY),
        ], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/perfmatters.png'), 'needs' => PresetsPerfmattersGAPreset::needs()];
    }
}
