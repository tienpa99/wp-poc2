<?php

namespace DevOwl\RealCookieBanner\view\checklist;

use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\view\Checklist;
use WP_Post;
use WP_REST_Request;
use WP_REST_Response;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Mention Real Cookie Banner usage in privacy policy.
 */
class PrivacyPolicyMentionUsage extends \DevOwl\RealCookieBanner\view\checklist\AbstractChecklistItem
{
    const IDENTIFIER = 'privacy-policy-mention-usage';
    // Documented in AbstractChecklistItem
    public function isChecked()
    {
        return $this->getFromOption(self::IDENTIFIER);
    }
    // Documented in AbstractChecklistItem
    public function toggle($state)
    {
        return $this->persistStateToOption(self::IDENTIFIER, $state);
    }
    // Documented in AbstractChecklistItem
    public function getTitle()
    {
        return \__('Explain data processing of cookie banner in privacy policy', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getDescription()
    {
        return \__('You must explain to your website visitors in your privacy policy which data Real Cookie Banner processes and stores to obtain consent. We provide you with text suggestion that you can copy and paste into your privacy policy.', RCB_TD);
    }
    // Documented in AbstractChecklistItem
    public function getLink()
    {
        return '#/settings';
    }
    // Documented in AbstractChecklistItem
    public function getLinkText()
    {
        return \__('Copy suggested text', RCB_TD);
    }
    /**
     * Settings got saved, let's check the checklist item.
     *
     * @param WP_REST_Response $response
     * @param WP_REST_Request $request
     */
    public static function settings_updated($response, $request)
    {
        if ($request->get_param(General::SETTING_BANNER_ACTIVE) === \true) {
            Checklist::getInstance()->toggle(self::IDENTIFIER, \true);
        }
        return $response;
    }
    /**
     * Privacy policy got updated, let's update the checklist accordingly.
     *
     * @param int $postId
     */
    public static function recalculate($postId)
    {
        $toggleState = \false;
        if ($postId > 0) {
            $content = \get_post($postId);
            if ($content instanceof WP_Post) {
                $content = $content->post_content;
                if (\stripos($content, 'real cookie banner') !== \false) {
                    $toggleState = \true;
                }
            }
        }
        Checklist::getInstance()->toggle(self::IDENTIFIER, $toggleState);
    }
    /**
     * Automatically check the checklist item if "Real Cookie Banner" is already mentioned in current
     * privacy policy.
     *
     * @see https://app.clickup.com/t/2vqpmwj
     * @param string|false $installed
     */
    public static function new_version_installation_after_3_4_13($installed)
    {
        if (Core::versionCompareOlderThan($installed, '3.4.13', ['3.4.14', '3.5.0'])) {
            self::recalculate(General::getInstance()->getPrivacyPolicyPageId());
        }
    }
}
