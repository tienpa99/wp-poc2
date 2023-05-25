<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\DiscordWidgetPreset as PresetsDiscordWidgetPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Discord (Widget) blocker preset.
 */
class DiscordWidgetPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsDiscordWidgetPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Discord (Widget)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*discord.com/widget*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/discord.png')];
    }
}
