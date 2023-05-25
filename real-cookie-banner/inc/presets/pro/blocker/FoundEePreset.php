<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\FoundEePreset as ProFoundEePreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * found.ee blocker preset.
 */
class FoundEePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = ProFoundEePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Found.ee DMP Pixel';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'hidden' => \true, 'attributes' => ['rules' => ['*found.ee/dmp/pixel.js*'], 'serviceTemplates' => [ProFoundEePreset::IDENTIFIER]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/found.ee.png')];
    }
}
