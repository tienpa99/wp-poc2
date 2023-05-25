<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\pro\GetYourGuidePreset as PresetsGetYourGuidePreset;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * GetYourGuide blocker preset.
 */
class GetYourGuidePreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsGetYourGuidePreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'GetYourGuide';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'attributes' => ['rules' => ['*widget.getyourguide.com*', '*/wp-content/plugins/getyourguide-widget*', 'div[data-gyg-href*="widget.getyourguide.com"]', 'form[class="gyg-search"]']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/get-your-guide.png')];
    }
}
