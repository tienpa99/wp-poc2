<?php

namespace DevOwl\RealCookieBanner\presets\free;

use DevOwl\RealCookieBanner\comp\language\Hooks;
use DevOwl\RealCookieBanner\presets\AbstractCookiePreset;
use DevOwl\RealCookieBanner\presets\PresetIdentifierMap;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\Utils;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * WordPress Comments cookie preset.
 */
class WordPressCommentsPreset extends AbstractCookiePreset
{
    const IDENTIFIER = PresetIdentifierMap::WORDPRESS_COMMENTS;
    const VERSION = 2;
    // Documented in AbstractPreset
    public function common()
    {
        $name = \__('WordPress Comments', RCB_TD);
        $cookieHost = Utils::host(Utils::HOST_TYPE_MAIN);
        return ['id' => self::IDENTIFIER, 'version' => self::VERSION, 'name' => $name, 'logoFile' => \admin_url('images/wordpress-logo.svg'), 'attributes' => ['name' => \__('Comments', Hooks::TD_FORCED), 'group' => \__('Functional', Hooks::TD_FORCED), 'purpose' => \__('WordPress as a content management system offers the possibility to write comments under blog posts and similar content. The cookie stores the name, e-mail address and website of a commentator to display it again if the commentator wants to write another comment on this website.', Hooks::TD_FORCED), 'provider' => \get_bloginfo('name'), 'providerPrivacyPolicyUrl' => General::getInstance()->getPrivacyPolicyUrl(''), 'providerLegalNoticeUrl' => General::getInstance()->getImprintPageUrl(''), 'technicalDefinitions' => [['type' => 'http', 'name' => 'comment_author_*', 'host' => $cookieHost, 'duration' => 1, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'comment_author_email_*', 'host' => $cookieHost, 'duration' => 1, 'durationUnit' => 'y', 'isSessionDuration' => \false], ['type' => 'http', 'name' => 'comment_author_url_*', 'host' => $cookieHost, 'duration' => 1, 'durationUnit' => 'y', 'isSessionDuration' => \false]], 'technicalHandlingNotice' => \join('<br /><br />', [\__('Please note that if this service is enabled, the "Save my name, email, and website in this browser for the next time I comment." checkbox in the comment form will disappear. Real Cookie Banner handles the consent to set the cookies as part of the overall cookie consent. The commentary system uses the Gravatar service to display avatars of commentators. You must also create a service for Gravatar as well.', RCB_TD), \sprintf(
            // translators:
            \__('Do you want to use the comment feature on your website at all? If not, we explain in our blog <a href="%s" target="_blank"> how to disable comments in WordPress</a>. Then you can also avoid this consent!', RCB_TD),
            \__('https://devowl.io/2022/deactivate-wordpress-comments/', RCB_TD)
        )]), 'codeOptIn' => '<script>
    var checkboxId = "wp-comment-cookies-consent";
    var checkbox = document.querySelector(\'[name="\' + checkboxId + \'"]\');
    var label = document.querySelector(\'[for="\' + checkboxId + \'"]\') || (checkbox && checkbox.parentElement);

    if (label && checkbox) {
        checkbox.checked = true;
        checkbox.style.display = "none";
        label.style.display = "none";
    }
</script>', 'deleteTechnicalDefinitionsAfterOptOut' => \false]];
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
