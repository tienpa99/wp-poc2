<?php

namespace DevOwl\RealCookieBanner\scanner;

use DevOwl\RealCookieBanner\base\UtilsProvider;
use DevOwl\RealCookieBanner\settings\General;
use DevOwl\RealCookieBanner\Core;
use DevOwl\RealCookieBanner\Vendor\MatthiasWeb\Utils\Service;
use DevOwl\RealCookieBanner\Utils;
use DevOwl\RealCookieBanner\view\Checklist;
use DevOwl\RealCookieBanner\view\checklist\Scanner;
// @codeCoverageIgnoreStart
\defined('ABSPATH') or die('No script kiddies please!');
// Avoid direct file request
// @codeCoverageIgnoreEnd
/**
 * Scanner notices management
 */
class ScannerNotices
{
    use UtilsProvider;
    const OPTION_NAME_ANY_PLUGIN_TOGGLE_STATE = RCB_OPT_PREFIX . '-any-plugin-toggle-state';
    const DEFAULT_ANY_PLUGIN_TOGGLE_STATE = \false;
    const NOTICE_TYPES = ['toggle-plugin-state' => self::OPTION_NAME_ANY_PLUGIN_TOGGLE_STATE];
    /**
     * Singleton instance.
     *
     * @var ScannerNotices
     */
    private static $me = null;
    /**
     * C'tor.
     */
    private function __construct()
    {
        // Silence is golden
    }
    /**
     * Get a config URL pointing to a given route (react-router).
     *
     * @param string $route
     */
    public function getConfigUrl($route)
    {
        $configUrl = Core::getInstance()->getConfigPage()->getUrl();
        return \sprintf('%s#%s', $configUrl, $route);
    }
    /**
     * Initially `add_option` to avoid autoloading issues.
     */
    public function enableOptionsAutoload()
    {
        General::enableOptionAutoload(self::OPTION_NAME_ANY_PLUGIN_TOGGLE_STATE, self::DEFAULT_ANY_PLUGIN_TOGGLE_STATE, 'boolval');
    }
    /**
     * Add option to show scanner notice if plugin is activated/deactivated
     *
     * @param string $plugin Plugin slug
     * @param bool $network_wide Is it activated network wide
     */
    public function togglePluginStateNotice($plugin, $network_wide)
    {
        $isScannerChecked = Checklist::getInstance()->isChecked(Scanner::IDENTIFIER);
        if (!Utils::startsWith($plugin, RCB_SLUG) && $isScannerChecked) {
            if ($network_wide) {
                $network_blogs = \get_sites(['number' => 0, 'fields' => 'ids']);
                foreach ($network_blogs as $blog) {
                    $blogId = \intval($blog);
                    \add_blog_option($blogId, self::OPTION_NAME_ANY_PLUGIN_TOGGLE_STATE, \true);
                }
            } else {
                \add_option(self::OPTION_NAME_ANY_PLUGIN_TOGGLE_STATE, \true);
            }
        }
    }
    /**
     * Delete option row that indicates notice as an active
     *
     * @param string $notice_type
     */
    public function dismiss($notice_type)
    {
        if (self::NOTICE_TYPES[$notice_type]) {
            return \update_option(self::NOTICE_TYPES[$notice_type], \false);
        }
        return \false;
    }
    /**
     * Add js handler to the button
     *
     * @param string $notice_type
     * @param string $redirect
     */
    protected function dismissOnClickHandler($notice_type, $redirect = \false)
    {
        $rest_url = Service::getUrl(Core::getInstance());
        $redirectStr = $redirect ? \sprintf('window.location.href= "%s";', $redirect) : '';
        return \join('', ['jQuery(this).parents(".notice").remove();', \sprintf('window.fetch("%s").then(function(response){ %s });', \add_query_arg(['_method' => 'DELETE', '_wpnonce' => \wp_create_nonce('wp_rest'), 'notice_type' => $notice_type], \sprintf('%sscanner/result/notice-dismissed', $rest_url)), $redirectStr)]);
    }
    /**
     * Check if notice should be shown for plugin state (activated / deactivated), see also `self::admin_notices_any_plugin_change_state`.
     */
    public function isNoticeAnyPluginChangeStateVisible()
    {
        return \get_option(self::OPTION_NAME_ANY_PLUGIN_TOGGLE_STATE);
    }
    /**
     * Creates an admin notice when plugins are activated/deactivated.
     */
    public function admin_notices_any_plugin_change_state()
    {
        if ($this->isNoticeAnyPluginChangeStateVisible()) {
            echo \sprintf('<div class="notice notice-warning" style="position:relative"><p>%s &bull; <a onClick="%s" href="#">%s</a></p>%s</div>', \__('You have enabled or disabled plugins on your website, which may require your cookie banner to be adjusted. Please scan your website again as soon as you have finished the changes!', RCB_TD), \esc_js($this->dismissOnClickHandler('toggle-plugin-state', $this->getConfigUrl('/scanner?start=1'))), \__('Scan website again', RCB_TD), \sprintf('<button type="button" class="notice-dismiss" onClick="%s"></button>', \esc_js($this->dismissOnClickHandler('toggle-plugin-state'))));
        }
    }
    /**
     * Get singleton instance.
     *
     * @return ScannerNotices
     * @codeCoverageIgnore
     */
    public static function getInstance()
    {
        return self::$me === null ? self::$me = new \DevOwl\RealCookieBanner\scanner\ScannerNotices() : self::$me;
    }
}
