<?php

namespace DevOwl\RealCookieBanner\presets\pro;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Stripe cookie preset.
 */
class StripePreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::STRIPE;
    const VERSION = 1;
    // Documented in AbstractPreset
    public function common()
    {
        $name = 'Stripe';
        return [
            'id' => self::IDENTIFIER,
            'version' => self::VERSION,
            'name' => $name,
            'logoFile' => Core::getInstance()->getBaseAssetsUrl('logos/stripe.png'),
            // Stripe preset should be used only for `extends` presets as it does not provide a standalone script
            'hidden' => \true,
        ];
    }
    // Documented in AbstractPreset
    public function managerNone()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerGtm()
    {
        return \false;
    }
    // Documented in AbstractPreset
    public function managerMtm()
    {
        return \false;
    }
}
