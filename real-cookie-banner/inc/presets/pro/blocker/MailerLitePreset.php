<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\MailerLitePreset as PresetsMailerLitePreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * MailerLite blocker preset.
 */
class MailerLitePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsMailerLitePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'MailerLite';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*static.mailerlite.com*', '*cdn.mailerlite.com*', '*assets.mailerlite.com*', '*cloudflare-static/email-decode.min.js*', '*track.mailerlite.com*', '*ml_webform_success*', 'div[class*="ml-form-embedContainer"]', 'div[class*="ml-subscribe-form"]', 'div[class*="ml-form-embed"]', 'div[class*="ml-embedded"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/mailerlite.png')];
    }
}
