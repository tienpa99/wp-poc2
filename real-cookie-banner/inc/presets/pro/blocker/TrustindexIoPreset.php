<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\TrustindexIoPreset as PresetsTrustindexIoPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * trustindex.io blocker preset.
 */
class TrustindexIoPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsTrustindexIoPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Trustindex.io';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => [
            '*cdn.trustindex.io/loader.js*',
            'div[src*="cdn.trustindex.io"]',
            // [Plugin Comp] https://wordpress.org/plugins/wp-reviews-plugin-for-google/
            '*cdn.trustindex.io*',
            '*wp-content/uploads/trustindex-google-widget.css*',
            'div[class*="ti-widget"]',
        ]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/trustindex-io.png')];
    }
}
