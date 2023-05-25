<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\settings\General;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Is a privacy policy page set?
 */
class PrivacyPolicy extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'privacy-policy';
    // Documented in AbstractChecklistItem
    public function isChecked()
    {
        return \get_option(General::SETTING_PRIVACY_POLICY_ID) > 0 || \get_option(General::SETTING_PRIVACY_POLICY_IS_EXTERNAL_URL) && !empty(\get_option(General::SETTING_PRIVACY_POLICY_EXTERNAL_URL));
    }
    // Documented in AbstractChecklistItem
    public function getTitle()
    {
        return \__('Set privacy policy page', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('Legally required pages must be accessible even if the cookie banner covers your website. Therefore, the privacy policy should be linked directly in the cookie banner.', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/settings';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Set privacy policy page', RCB_TD);
    }
}
