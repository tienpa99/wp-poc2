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
 * Ninja Forms with Google reCAPTCHA blocker preset.
 */
class NinjaFormsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetIdentifierMap::NINJA_FORMS_RECAPTCHA;
    const SLUG = 'ninja-forms';
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => \__('Ninja Forms', RCB_TD), 'description' => \__('with Google reCAPTCHA', RCB_TD), 'attributes' => ['rules' => [[BlockerHostsOptionsMiddleware::EXPRESSION => '*google.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA], [BlockerHostsOptionsMiddleware::EXPRESSION => '*gstatic.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA], [
            // Gutenberg
            BlockerHostsOptionsMiddleware::EXPRESSION => 'div[class="wp-block-ninja-forms-form"]',
            BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER,
        ], [BlockerHostsOptionsMiddleware::EXPRESSION => 'div[class*="nf-form-cont"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER], [BlockerHostsOptionsMiddleware::EXPRESSION => '*wp-content/plugins/ninja-forms/assets/js*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER], [
            // All official plugins of Ninja Forms
            BlockerHostsOptionsMiddleware::EXPRESSION => '*wp-content/plugins/ninja-forms-*/*',
            BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER,
        ]]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/ninja-forms.png'), 'needs' => self::needs()];
    }
    // Self-explanatory
    public static function needs()
    {
        return DisablePresetByNeedsMiddleware::generateNeedsForSlugs([self::SLUG]);
    }
}
