<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\ImgurPreset as PresetsImgurPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Imgur blocker preset.
 */
class ImgurPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsImgurPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Imgur';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*s.imgur.com*', 'blockquote[class="imgur-embed-pub"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/imgur.png')];
    }
}
