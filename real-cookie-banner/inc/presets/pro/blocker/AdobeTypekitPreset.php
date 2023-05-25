<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\AdobeTypekitPreset as PresetsAdobeTypekitPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\free\blocker\GoogleFontsPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Adobe Typekit blocker preset.
 */
class AdobeTypekitPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsAdobeTypekitPreset::IDENTIFIER;
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Adobe Fonts (Typekit)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*use.typekit.net*', '*p.typekit.net*', GoogleFontsPreset::WEB_FONT_LOADER_URL, '*WebFont.load*typekit*', 'WebFontConfig*typekit*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/adobe-fonts.png')];
    }
}
