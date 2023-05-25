<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\AdInserterPreset as PresetsAdInserterPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Ad Inserter plugin blocker preset.
 */
class AdInserterPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsAdInserterPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Ad Inserter';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['div[class*="ad-inserter"]', 'div[class*="code-block"]', '*plugins/ad-inserter*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/a-inserter.png'), 'needs' => PresetsAdInserterPreset::needs()];
    }
}
