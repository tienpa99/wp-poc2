<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\ZendeskChatPreset as PresetsZendeskChatPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Zendesk (Chat) blocker preset.
 */
class ZendeskChatPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsZendeskChatPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Zendesk Chat';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*static.zdassets.com*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/zendesk-chat.png')];
    }
}
