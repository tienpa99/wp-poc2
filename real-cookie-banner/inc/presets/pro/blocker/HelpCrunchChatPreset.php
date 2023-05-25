<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\HelpCrunchChatPreset as ProHelpCrunchChatPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * HelpCrunch Chat blocker preset.
 */
class HelpCrunchChatPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = ProHelpCrunchChatPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Helpcrunch (Chat)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'hidden' => \true, 'attributes' => ['rules' => ['*widget.helpcrunch.com*', 'HelpCrunch'], 'serviceTemplates' => [ProHelpCrunchChatPreset::IDENTIFIER]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/helpcrunch.png')];
    }
}
