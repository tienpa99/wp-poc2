<?php

namespace DevOwl\RealCookieBanner\presets\pro;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Active Campaign Site Tracking preset.
 */
class ActiveCampaignSiteTrackingPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::ACTIVE_CAMPAIGN_SITE_TRACKING;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'ActiveCampaign';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'description' => 'Site Tracking', 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/activecampaign.png')];
    }
    // Documented in AbstractPreset
    public function managerNone()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerGtm()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerMtm()
    {
        return \false;
    }
}
