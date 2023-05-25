<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Active Campaign with Google reCAPTCHA blocker preset.
 */
class ActiveCampaignFormPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetIdentifierMap::ACTIVE_CAMPAIGN_RECAPTCHA;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => \__('ActiveCampaign Form', RCB_TD), 'description' => \__('with Google reCAPTCHA', RCB_TD), 'attributes' => ['rules' => ['*activehosted.com/f/embed.php*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/activecampaign.png')];
    }
}
