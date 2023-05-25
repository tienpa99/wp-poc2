<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\middleware\BlockerHostsOptionsMiddleware;
use DevOwl\RealCookieBanner\presets\middleware\DisablePresetByNeedsMiddleware;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Contact Form 7 with Google reCAPTCHA blocker preset.
 */
class ContactForm7RecaptchaPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetIdentifierMap::CONTACT_FORM_7_RECAPTCHA;
    const SLUG = 'contact-form-7';
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => \__('Contact Form 7', RCB_TD), 'description' => \__('with Google reCAPTCHA', RCB_TD), 'attributes' => ['rules' => [[BlockerHostsOptionsMiddleware::EXPRESSION => '*google.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA], [BlockerHostsOptionsMiddleware::EXPRESSION => '*gstatic.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA], [BlockerHostsOptionsMiddleware::EXPRESSION => '*wp-content/plugins/contact-form-7/*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER], 'div[class*="wpcf7"]', 'link[href="//www.google.com"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/contact-form-7.png'), 'needs' => self::needs()];
    }
    // Self-explanatory
    public static function needs()
    {
        return DisablePresetByNeedsMiddleware::generateNeedsForSlugs([self::SLUG]);
    }
}
