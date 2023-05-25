<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\SendinbluePreset as PresetsSendinbluePreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Sendinblue blocker preset.
 */
class SendinbluePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsSendinbluePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'description' => \__('former Newsletter2Go', RCB_TD), 'name' => 'Sendinblue', 'attributes' => ['rules' => ['*wp-content/plugins/newsletter2go*', 'script[id="n2g_script"]', '*static.newsletter2go.com*', '*sibforms.com/serve*', '*assets.sendinblue.com*', '*sib-container*', '*sibforms.com/forms*', 'div[class*="sib-form"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/sendinblue.png')];
    }
}
