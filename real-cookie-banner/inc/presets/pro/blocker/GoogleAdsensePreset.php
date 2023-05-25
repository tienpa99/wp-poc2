<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\GoogleAdSensePreset as ProGoogleAdSensePreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google Adsense blocker preset.
 */
class GoogleAdsensePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = ProGoogleAdSensePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google AdSense';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'hidden' => \true, 'attributes' => ['rules' => ['*pagead/js/adsbygoogle.js*', 'ins[class="adsbygoogle"]', 'adsbygoogle', '*pagead2.googlesyndication.com*'], 'serviceTemplates' => [ProGoogleAdSensePreset::IDENTIFIER]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google-a-dsense.png')];
    }
}
