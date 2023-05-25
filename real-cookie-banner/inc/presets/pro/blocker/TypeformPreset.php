<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\TypeformPreset as PresetsTypeformPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Typeform blocker preset.
 */
class TypeformPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsTypeformPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Typeform';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*wp-content/plugins/typeform/*', '*typeform.com/to/*', '*embed.typeform.com*', 'div[data-tf-widget]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/typeform.png')];
    }
}
