<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\BloomPreset as PresetsBloomPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Bloom blocker preset.
 */
class BloomPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsBloomPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Bloom';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*wp-content/plugins/bloom*', '*bloomSettings*', 'div[class*="et_bloom_popup"]', 'div[class*="et_bloom_optin"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/bloom.png'), 'needs' => PresetsBloomPreset::needs()];
    }
}
