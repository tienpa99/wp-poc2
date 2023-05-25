<?php

namespace DevOwl\RealCookieBanner\presets\free\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\free\FontAwesomePreset as PresetsFontAwesomePreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Font Awesome blocker preset.
 */
class FontAwesomePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsFontAwesomePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Font Awesome';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/font-awesome.png'), 'attributes' => ['name' => $name, 'rules' => \array_merge(['*use.fontawesome.com*', '*kit.fontawesome.com*', '*bootstrapcdn.com/font-awesome/*'], $this->createHostsForCdn('font-awesome')), 'serviceTemplates' => [PresetsFontAwesomePreset::IDENTIFIER], 'isVisual' => \false]];
    }
}
