<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\XingEventsPreset as PresetsXingEventsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Xing Events blocker preset.
 */
class XingEventsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsXingEventsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Xing Events';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*xing-events.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/xing.png')];
    }
}
