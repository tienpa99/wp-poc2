<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\IntercomChatPreset as PresetsIntercomChatPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Intercom (Chat) blocker preset.
 */
class IntercomChatPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsIntercomChatPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Intercom (Chat)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['intercomSettings', '*widget.intercom.io*', 'Intercom(\'shutdown\');']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/intercom.png')];
    }
}
