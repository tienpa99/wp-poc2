<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\GoogleUserContentPreset as PresetsGoogleUserContentPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Google User Content blocker preset.
 */
class GoogleUserContentPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGoogleUserContentPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Google User Content';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => [
            '*googleusercontent.com*',
            // [Plugin Comp] https://wordpress.org/plugins/widget-google-reviews/
            'div[class="rplg"]',
            '*/wp-content/plugins/business-reviews-bundle/assets/js/public-main.js*',
        ]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/google.png')];
    }
}
