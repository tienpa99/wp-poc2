<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\AnchorFmPreset as PresetsAnchorFmPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Anchor.fm blocker preset.
 */
class AnchorFmPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsAnchorFmPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Anchor.fm';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*anchor.fm/*/embed/*', '*anchor.fm/*/embed']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/anchor-fm.png')];
    }
}
