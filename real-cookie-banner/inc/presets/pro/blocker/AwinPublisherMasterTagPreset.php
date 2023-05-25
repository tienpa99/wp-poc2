<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\AwinPublisherMasterTagPreset as ProAwinPublisherMasterTagPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Awin (Publisher MasterTag) blocker preset.
 */
class AwinPublisherMasterTagPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = ProAwinPublisherMasterTagPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Awin';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'description' => 'Publisher MasterTag', 'hidden' => \true, 'attributes' => ['rules' => ['*dwin2.com/pub.*.min.js*'], 'serviceTemplates' => [ProAwinPublisherMasterTagPreset::IDENTIFIER]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/awin.png')];
    }
}
