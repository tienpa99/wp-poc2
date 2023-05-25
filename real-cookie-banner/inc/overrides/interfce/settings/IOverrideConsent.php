<?php

namespace DevOwl\RealCookieBanner\overrides\interfce\settings;

// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
interface IOverrideConsent
{
    /**
     * Initially add PRO-only options.
     */
    public function overrideEnableOptionsAutoload();
    /**
     * Register PRO-only options.
     */
    public function overrideRegister();
    /**
     * Check if ePrivacy hints for USA is enabled
     *
     * @return boolean
     */
    public function isEPrivacyUSAEnabled();
}
