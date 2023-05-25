<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\middleware\BlockerHostsOptionsMiddleware;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * CleverReach with Google reCAPTCHA blocker preset.
 */
class CleverReachRecaptchaPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetIdentifierMap::CLEVERREACH_RECAPTCHA;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => \__('CleverReach', RCB_TD), 'description' => \__('with Google reCAPTCHA', RCB_TD), 'attributes' => ['rules' => [[BlockerHostsOptionsMiddleware::EXPRESSION => '*google.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA], [BlockerHostsOptionsMiddleware::EXPRESSION => '*gstatic.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA], [BlockerHostsOptionsMiddleware::EXPRESSION => 'form[action*="cleverreach.com"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER], 'img[src*="cloudfront.net/cr3_images/badget"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/clever-reach.png')];
    }
}
