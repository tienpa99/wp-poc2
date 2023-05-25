<?php

namespace DevOwl\RealCookieBanner\presets\free\blocker;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\free\WordPressUserLoginPreset as FreeWordPressUserLoginPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WordPress User Login blocker preset.
 */
class WordPressUserLoginPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = FreeWordPressUserLoginPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => \__('WordPress User Login', RCB_TD), 'logoFile' => \admin_url('images/wordpress-logo.svg'), 'attributes' => ['name' => \__('WordPress User Login', Hooks::TD_FORCED), 'description' => \__('In order to log in, you must allow us to load additional services for security and convenience reasons.', Hooks::TD_FORCED), 'rules' => ['form[name="loginform"]'], 'serviceTemplates' => [FreeWordPressUserLoginPreset::IDENTIFIER], 'isVisual' => \true, 'visualType' => 'default', 'visualContentType' => 'generic']];
    }
}
