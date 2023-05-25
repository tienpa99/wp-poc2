<?php

namespace DevOwl\RealCookieBanner\presets\free\blocker;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\free\JetPackCommentsPreset as FreeJetpackJetPackCommentsPreset;
use DevOwl\RealCookieBanner\presets\free\JetpackSiteStatsPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Jetpack Comments blocker preset.
 */
class JetPackCommentsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = FreeJetpackJetPackCommentsPreset::IDENTIFIER;
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Jetpack Comments';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/jetpack.png'), 'needs' => JetpackSiteStatsPreset::needs(), 'attributes' => ['name' => $name, 'description' => \__('We use the Jetpack Comments service to give you the opportunity to leave a comment. In order to be able to write your own comment, you must allow this service to load.', Hooks::TD_FORCED), 'rules' => ['jetpack_remote_comment', 'iframe[class="jetpack_remote_comment"]'], 'serviceTemplates' => [FreeJetpackJetPackCommentsPreset::IDENTIFIER], 'isVisual' => \true, 'visualType' => 'hero', 'visualContentType' => 'feed-text']];
    }
}
