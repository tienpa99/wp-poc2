<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\MetricoolPreset as PresetsMetricoolPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Metricool blocker preset.
 */
class MetricoolPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsMetricoolPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Metricool';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*tracker.metricool.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/metricool.png')];
    }
}
