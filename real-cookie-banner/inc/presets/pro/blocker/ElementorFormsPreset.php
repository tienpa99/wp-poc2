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
 * Elementor Forms with Google reCAPTCHA blocker preset.
 */
class ElementorFormsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetIdentifierMap::ELEMENTOR_FORMS_RECAPTCHA;
    const SLUG = 'elementor-pro';
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => \__('Elementor Forms', RCB_TD), 'description' => \__('with Google reCAPTCHA', RCB_TD), 'attributes' => ['rules' => [[BlockerHostsOptionsMiddleware::EXPRESSION => '*google.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA], [BlockerHostsOptionsMiddleware::EXPRESSION => '*gstatic.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA], [BlockerHostsOptionsMiddleware::EXPRESSION => 'div[class*="elementor-widget-form"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER]]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/elementor.png'), 'needs' => self::needs()];
    }
    // Self-explanatory
    public static function needs()
    {
        return DisablePresetByNeedsMiddleware::generateNeedsForSlugs([self::SLUG]);
    }
}
