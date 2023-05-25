<?php

namespace DevOwl\RealCookieBanner\presets\free\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\free\GravatarPreset as PresetsGravatarPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Gravatar Avatar blocker preset.
 */
class GravatarPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGravatarPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Gravatar';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/gravatar.png'), 'attributes' => ['name' => $name, 'rules' => ['*gravatar.com/avatar*', '*.gravatar.com'], 'serviceTemplates' => [PresetsGravatarPreset::IDENTIFIER], 'isVisual' => \false]];
    }
}
