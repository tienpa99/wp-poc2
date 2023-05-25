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
 * HappyForms with Google reCAPTCHA blocker preset.
 */
class HappyFormsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetIdentifierMap::HAPPYFORMS_RECAPTCHA;
    const SLUG_FREE = 'happyforms';
    const SLUG_PRO = 'happyforms-upgrade';
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => \__('HappyForms', RCB_TD), 'description' => \__('with Google reCAPTCHA', RCB_TD), 'attributes' => ['rules' => [
            [BlockerHostsOptionsMiddleware::EXPRESSION => '*google.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA],
            [BlockerHostsOptionsMiddleware::EXPRESSION => '*gstatic.com/recaptcha*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => PresetIdentifierMap::GOOGLE_RECAPTCHA],
            [BlockerHostsOptionsMiddleware::EXPRESSION => 'div[class*="happyforms-form"]', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER],
            [BlockerHostsOptionsMiddleware::EXPRESSION => '*/wp-content/plugins/happyforms-upgrade/inc/assets/js/frontend*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER],
            // Newer version
            [BlockerHostsOptionsMiddleware::EXPRESSION => '*/wp-content/plugins/happyforms-upgrade/bundles/js/frontend*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER],
            [BlockerHostsOptionsMiddleware::EXPRESSION => '*/wp-content/plugins/happyforms/inc/assets/js/frontend*', BlockerHostsOptionsMiddleware::ASSIGNED_TO_GROUPS => self::IDENTIFIER],
        ]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/happyforms.png'), 'needs' => self::needs()];
    }
    // Self-explanatory
    public static function needs()
    {
        return DisablePresetByNeedsMiddleware::generateNeedsForSlugs([self::SLUG_FREE, self::SLUG_PRO]);
    }
}
