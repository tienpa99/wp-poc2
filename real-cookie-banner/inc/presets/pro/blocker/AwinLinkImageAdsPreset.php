<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\AwinLinkImageAdsPreset as PresetsAwinLinkImageAdsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Awin (Link and image ads) blocker preset.
 */
class AwinLinkImageAdsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsAwinLinkImageAdsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Awin';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*awin1.com*', '*dwin2.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/awin.png')];
    }
}
