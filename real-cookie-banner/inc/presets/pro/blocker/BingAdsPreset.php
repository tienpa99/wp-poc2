<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\BingAdsPreset as ProBingAdsPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Microsoft Advertising Universal Event Tracking (UET) Tag (Bing Ads) blocker preset.
 */
class BingAdsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = ProBingAdsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Microsoft Advertising Universal Event Tracking (UET) Tag (Bing Ads)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'hidden' => \true, 'attributes' => ['rules' => ['*bat.bing.com/bat.js*'], 'serviceTemplates' => [ProBingAdsPreset::IDENTIFIER]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/microsoft.png')];
    }
}
