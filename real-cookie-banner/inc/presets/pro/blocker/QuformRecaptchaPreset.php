<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\QuformPreset as PresetsQuformPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\middleware\BlockerHostsOptionsMiddleware;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Quform with Google reCAPTCHA blocker preset.
 */
class QuformRecaptchaPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsQuformPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Quform';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => \__('with Google reCAPTCHA', RCB_TD), 'attributes' => ['rules' => [[BlockerHostsOptionsMiddleware::EXPRESSION => '*google.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA], [BlockerHostsOptionsMiddleware::EXPRESSION => '*gstatic.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA], [BlockerHostsOptionsMiddleware::EXPRESSION => 'div[class*="quform-form"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER]]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/quform.png'), 'needs' => PresetsQuformPreset::needs()];
    }
}
