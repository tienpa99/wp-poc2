<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\AmazonAssociatesWidgetPreset as ProAmazonAssociatesWidgetPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Amazon Associates Widget blocker preset.
 */
class AmazonAssociatesWidgetPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = ProAmazonAssociatesWidgetPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Amazon Widget';
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'hidden' => \true, 'attributes' => ['rules' => ['*amzn_assoc_ad_type*', '*amazon-adsystem.com/widgets*'], 'serviceTemplates' => [ProAmazonAssociatesWidgetPreset::IDENTIFIER]], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/amazon-associates.png')];
    }
}
