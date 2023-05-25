<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\ZohoFormsPreset as PresetsZohoFormsPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Zoho Forms blocker preset.
 */
class ZohoFormsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsZohoFormsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => 'Zoho Forms', 'attributes' => ['rules' => ['*forms.zohopublic.*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/zoho.png')];
    }
}
