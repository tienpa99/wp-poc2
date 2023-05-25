<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\MailPoetPreset as PresetsMailPoetPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * MailPoet blocker preset.
 */
class MailPoetPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsMailPoetPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'MailPoet';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*/wp-content/plugins/mailpoet/assets/dist/js/*', '*/wp-content/plugins/mailpoet-premium/assets/dist/js/*', 'div[class*="mailpoet_form"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/mailpoet.png'), 'needs' => PresetsMailPoetPreset::needs()];
    }
}
