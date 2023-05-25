<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\FlickrPreset as PresetsFlickrPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Flickr blocker preset.
 */
class FlickrPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsFlickrPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Flickr (Images)';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*live.staticflickr.com*', '*embedr.flickr.com*', '*widgets.flickr.com*', '*staticflickr.com*', 'a[data-flickr-embed="true"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/flickr.png')];
    }
}
