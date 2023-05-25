<?php

namespace DevOwl\RealCookieBanner\presets\pro\blocker;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractBlockerPreset;
use DevOwl\RealCookieBanner\presets\pro\ZohoBookingsPreset as PresetsZohoBookingsPreset;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Zoho Bookings blocker preset.
 */
class ZohoBookingsPreset extends AbstractBlockerPreset
{
    const IDENTIFIER = PresetsZohoBookingsPreset::IDENTIFIER;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => 'Zoho Bookings', 'attributes' => ['rules' => ['*.zohobookings.*']], 'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/zoho.png')];
    }
}
