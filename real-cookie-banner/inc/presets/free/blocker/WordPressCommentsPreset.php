<?php

namespace DevOwl\RealCookieBanner\presets\free\blocker;

use DevOwl\RealCookieBanner\presets\free\WordPressCommentsPreset as PresetsWordPressCommentsPreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WordPress Comments blocker preset.
 */
class WordPressCommentsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsWordPressCommentsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = \__('WordPress Comments', RCB_TD);
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => \admin_url('images/wordpress-logo.svg'), 'hidden' => \true, 'attributes' => ['rules' => ['form[action*="wp-comments-post.php"]'], 'serviceTemplates' => [PresetsWordPressCommentsPreset::IDENTIFIER]]];
    }
}
