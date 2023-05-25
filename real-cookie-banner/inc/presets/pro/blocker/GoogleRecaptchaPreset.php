<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\GoogleRecaptchaPreset as PresetsGoogleRecaptchaPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google reCAPTCHA blocker preset.
 */
class GoogleRecaptchaPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGoogleRecaptchaPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google reCAPTCHA';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*google.com/recaptcha*', '*gstatic.com/recaptcha*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/recaptcha.png')];
    }
}
