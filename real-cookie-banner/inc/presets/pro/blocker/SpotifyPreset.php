<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\SpotifyPreset as PresetsSpotifyPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Spotify blocker preset.
 */
class SpotifyPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsSpotifyPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Spotify';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*open.spotify.com/embed*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/spotify.png')];
    }
}
