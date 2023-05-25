<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\LinkedInAdsPreset as PresetsLinkedInAdsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * LinkedIn Ads blocker preset.
 */
class LinkedInAdsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsLinkedInAdsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'LinkedIn Ads';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'description' => 'LinkedIn Insight-Tag', 'name' => $name, 'attributes' => ['rules' => ['*_linkedin_partner_id*', '*snap.licdn.com*', '*ads.linkedin.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/linkedin.png')];
    }
}
