<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\GtmPreset as ProGtmPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google Tag Manager blocker preset.
 */
class GtmPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = ProGtmPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google Tag Manager';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'hidden' => \true, 'attributes' => ['rules' => ['*googletagmanager.com/gtm.js*', '*googletagmanager.com/ns.html*'], 'serviceTemplates' => [ProGtmPreset::IDENTIFIER]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google-tag-manager.png')];
    }
}
