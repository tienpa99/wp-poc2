<?php
/**
 * @category Wordpress-plugin
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 * @link     run_aruba_hispeed_cache
 */


namespace ArubaHiSpeedCache\includes;

use ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs;
use \WP_Admin_Bar;

use \array_unshift;
use \sprintf;

use \wp_enqueue_style;
use \wp_enqueue_script;
use \wp_localize_script;
use \esc_html__;
use \__;
use \is_multisite;
use \is_network_admin;
use \add_submenu_page;
use \network_admin_url;
use \get_site_option;
use \wp_parse_args;
use \current_user_can;
use \is_admin;
use \add_query_arg;
use \wp_nonce_url;
use \wp_is_post_revision;
use \update_site_option;
use \wp_die;
use \wp_redirect;
use \esc_url_raw;
use \do_action;
use \check_admin_referer;
use \user_trailingslashit;
use \home_url;
use \wp_verify_nonce;
use \checked;
use \wp_kses;
use \sanitize_key;
use \wp_unslash;
use \get_site_transient;
use \get_transient;
use \delete_site_transient;
use \delete_transient;

if (! \class_exists('ArubaHiSpeedCache\includes\ArubaHiSpeedCacheAdmin')) {

    /**
     * ArubaHiSpeedCacheAdmin
     */
    class ArubaHiSpeedCacheAdmin
    {

        /**
         * $options
         *
         * @var array
         */
        public $options;

        /**
         * $config
         *
         * @var \ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs
         */
        public $configs;

        /**
         * ArubaHiSpeedCacheAdmin\__construct
         *
         * @param mixed $name
         */
        public function __construct()
        {
            //$this->configs = $configs;
            $this->options = $this->aruba_hispeed_cache_settings();
        }

        /**
         * Enqueue_scripts
         *
         * @param  string $hook the currente acp hookname
         * @return void
         */
        public function enqueue_scripts($hook)
        {
            $has_notice = (\is_multisite()) ?
            \get_site_transient(ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('TRANSIENT_NAME')) :
            \get_transient(ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('TRANSIENT_NAME'))
            ;

            if ($has_notice) {
                \wp_enqueue_style(
                    ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PLUGIN_NAME') . '-notices',
                    ARUBA_HISPEED_CACHE_BASEURL . 'admin/css/aruba-hispeed-cache-admin-notice.css',
                    array(),
                    ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PLUGIN_VERSION')
                );
            }

            if ('settings_page_aruba-hispeed-cache' !== $hook) {
                return;
            }

            \wp_enqueue_style(
                ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PLUGIN_NAME'),
                ARUBA_HISPEED_CACHE_BASEURL . 'admin/css/aruba-hispeed-cache-admin.css',
                array(),
                ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PLUGIN_VERSION')
            );

            \wp_enqueue_script(
                ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PLUGIN_NAME'),
                ARUBA_HISPEED_CACHE_BASEURL . 'admin/js/aruba-hispeed-cache-admin.js',
                array(),
                ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PLUGIN_VERSION')
            );

            \wp_localize_script(
                ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PLUGIN_NAME'),
                'aruba_hispeed_cache',
                array(
                'purge_confirm_string' => \esc_html__('You are about to purge the entire cache. Do you want to continue?', 'aruba-hispeed-cache'),
                )
            );
        }

        /**
         * Aruba_hispeed_cache_admin_menu
         *
         * @return void
         */
        public function aruba_hispeed_cache_admin_menu()
        {
            if (\is_multisite()) {
                \add_submenu_page(
                    'settings.php',
                    __('Aruba HiSpeed Cache', 'aruba-hispeed-cache'),
                    __('Aruba HiSpeed Cache', 'aruba-hispeed-cache'),
                    'manage_options',
                    'aruba-hispeed-cache',
                    array( &$this, 'aruba_hispeed_cache_setting_page_cb' )
                );
            } else {
                \add_submenu_page(
                    'options-general.php',
                    __('Aruba HiSpeed Cache', 'aruba-hispeed-cache'),
                    __('Aruba HiSpeed Cache', 'aruba-hispeed-cache'),
                    'manage_options',
                    'aruba-hispeed-cache',
                    array( &$this, 'aruba_hispeed_cache_setting_page_cb' )
                );
            }
        }

        /**
         * Undocumented function
         *
         * @return void
         */
        public function aruba_hispeed_cache_setting_page_cb()
        {
            if (! \current_user_can('manage_options')) {
                \wp_die(
                    esc_html__('Sorry, you need to be an administrator to use Aruba HiSpeed Cache.', 'aruba-hispeed-cache')
                );
            }

            include ARUBA_HISPEED_CACHE_BASEPATH . 'admin' .AHSC_DS. 'partials' .AHSC_DS. 'admin-display.php';
        }

        /**
         * Aruba_hispeed_cache_toolbar_purge_link
         *
         * @param  WP_Admin_Bar $wp_admin_bar
         * @return void
         */
        public function aruba_hispeed_cache_toolbar_purge_link($wp_admin_bar)
        {
            if (! \current_user_can('manage_options')) {
                return;
            }

            $aruba_hispeed_cache_urls = 'current-url';
            $link_title = __('Purge the page cache', 'aruba-hispeed-cache');

            if (\is_admin()) {
                $aruba_hispeed_cache_urls = 'all';
                $link_title        = __('Purge Cache', 'aruba-hispeed-cache');
            }

            $purge_url = \add_query_arg(
                array(
                    'aruba_hispeed_cache_action' => 'purge',
                    'aruba_hispeed_cache_urls'   => $aruba_hispeed_cache_urls,
                )
            );

            $nonced_url = \wp_nonce_url($purge_url, 'aruba_hispeed_cache-purge_all');

            $wp_admin_bar->add_menu(
                array(
                    'id'    => 'aruba-hispeed-cache-purge-all',
                    'title' => $link_title,
                    'href'  => $nonced_url,
                    'meta'  => array( 'title' => $link_title ),
                )
            );
        }

        /**
         * Check_hispeed_cache_notices
         *
         * @return void
         */
        public function check_hispeed_cache_notices()
        {
            $file_notice = (\is_multisite()) ? \get_site_transient('ahsc_activation_check') : \get_transient('ahsc_activation_check');
            if ($file_notice) {
                include_once ARUBA_HISPEED_CACHE_BASEPATH . 'admin' .AHSC_DS. 'partials' .AHSC_DS. $file_notice .'.php';
            }
        }

        /**
         * Aruba_hispeed_cache_settings
         *
         * @return array $data
         */
        public function aruba_hispeed_cache_settings()
        {
            $options = \get_site_option(ARUBA_HISPEED_CACHE_OPTIONS_NAME);

            $data = \wp_parse_args(
                $options,
                ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_get_default_settings()
            );

            return $data;
        }

        /**
         * Aruba_hispeed_cache_settings_link
         *
         * @param  array $links
         * @return array $links
         */
        public function aruba_hispeed_cache_settings_link(array $links)
        {
            $setting_page = (!\is_network_admin()) ? 'options-general.php' : 'settings.php';

            $settings_link = \sprintf(
                '<a href="%s">%s</a>',
                \network_admin_url($setting_page . '?page=aruba-hispeed-cache'),
                \__('Settings', 'aruba-hispeed-cache')
            );

            $support_link = \sprintf(
                '<a href="%s" target="_blank">%s</a>',
                \ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs::getLocalizedLink('link_assistance'),
                \__('Customer service', 'aruba-hispeed-cache')
            );

            \array_unshift($links, $settings_link, $support_link);

            return $links;
        }

        /**
         * Display_notices
         *
         * @return void
         */
        public function display_notices_settings_saved()
        {
            include_once ARUBA_HISPEED_CACHE_BASEPATH . 'admin' .AHSC_DS. 'partials' .AHSC_DS. 'admin-notice-settings-saved.php';
        }

        /**
         * Display_notices
         *
         * @return void
         */
        public function display_notices_purge_initied()
        {
            include_once ARUBA_HISPEED_CACHE_BASEPATH . 'admin' .AHSC_DS. 'partials' .AHSC_DS. 'admin-notice-purge-completed.php';
        }

        /**
         * _generate_purge_nonce
         *
         * @return wp_nonce_url
         */
        private function _generate_purge_nonce()
        {
            $purge_url  = \add_query_arg(
                array(
                'aruba_hispeed_cache_action' => 'purge',
                'aruba_hispeed_cache_urls'   => 'all',
                )
            );
            return \wp_nonce_url($purge_url, 'aruba_hispeed_cache-purge_all');
        }

        /**
         * private _generate_settings_form_nonce
         *
         * @return string wp_create_nonce
         */
        private function _generate_settings_form_nonce()
        {
            return \wp_create_nonce('smart-http-expire-form-nonce');
        }

        /**
         * private _save_settings
         *
         * @return void
         */
        private function _save_settings()
        {
            //get the current option stored in db.
            $current_options = $this->aruba_hispeed_cache_settings();

            //prepare the filter to escape the inputs
            $form_options = \array_merge(
                ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('OPTIONS'),
                array('is_submit', 'smart_http_expire_save', 'smart_http_expire_form_nonce')
            );
            $form_options = \array_fill_keys($form_options, FILTER_SANITIZE_STRING);

            /**
             * @see https://www.php.net/manual/en/function.filter-input-array.php
             */
            $all_inputs = filter_input_array(INPUT_POST, $form_options, false); // for php 5.4

            if (isset($all_inputs['smart_http_expire_save'])
                && \wp_verify_nonce(\sanitize_key(\wp_unslash($all_inputs['smart_http_expire_form_nonce'])), 'smart-http-expire-form-nonce')
            ) {
                unset($all_inputs['smart_http_expire_save']);
                unset($all_inputs['smart_http_expire_form_nonce']);
                unset($all_inputs['is_submit']);

                $new_settings = \wp_parse_args(
                    $all_inputs,
                    $current_options
                );

                if (\update_site_option(ARUBA_HISPEED_CACHE_OPTIONS_NAME, $new_settings)) {
                    // \add_action('admin_notices', array( &$this, 'display_notices_settings_saved' ));
                    // \add_action('network_admin_notices', array( &$this, 'display_notices_settings_saved' ));
                    $this->display_notices_settings_saved();
                }
            }
        }

        private function _check_hispeed_cache_services_realtime()
        {
            $has_notice = (\is_multisite()) ?
                \get_site_transient(ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('TRANSIENT_NAME')) :
                \get_transient(ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('TRANSIENT_NAME'))
            ;

            if ($has_notice) {
                if (\is_multisite()) {
                    \delete_site_transient(ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('TRANSIENT_NAME'));
                } else {
                    \delete_transient(ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('TRANSIENT_NAME'));
                }
            }

            $checker = new HiSpeedCacheServiceChecker();
            $notices_file = null;

            switch ($checker->check()) {
                case 'available':
                    $notices_file = 'admin-notice-service-available';
                    break;
                case 'unavailable':
                    $notices_file = 'admin-notice-service-unavailable';
                    break;
                case 'no-aruba-server':
                    $notices_file = 'admin-notice-not-aruba-server';
                    break;
                }

            if ($notices_file != null) {
                include_once ARUBA_HISPEED_CACHE_BASEPATH . 'admin' .AHSC_DS. 'partials' .AHSC_DS. $notices_file .'.php';
            }

            if ('1' === \filter_input(INPUT_GET, 'debug', FILTER_SANITIZE_STRING)) {
                return $checker->debugger();
            }

            return false;
        }

        /**
         * private _settings_manager()
         *
         * @return array settings
         */
        private function _settings_manager()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->_save_settings();
            }
            return $this->aruba_hispeed_cache_settings();
        }

        /**
         * private function _form_fields
         *
         * @return array $fieldsSets
         */
        private function _form_fields()
        {
            $fieldsSets = array();

            $fieldsSets['ahsc_enable_purge'] = array(
                    'th' => \esc_html__('Purging options', 'aruba-hispeed-cache'),
                    'legend_text' => \esc_html__('Purging options', 'aruba-hispeed-cache'),
                    'fields' => array(
                        'ahsc_enable_purge' => array(
                            'type' => 'checkbox',
                            'id' => 'ahsc_enable_purge',
                            'name' => 'ahsc_enable_purge',
                            'lable_for' => 'ahsc_enable_purge',
                            'lable_text' => \esc_html__('Enable automatic purge', 'aruba-hispeed-cache'),
                        )
                    )
                );

            /**
             * @see https://developer.wordpress.org/reference/functions/is_network_admin/
             * True if inside WordPress network administration pages
             *
             * @see https://developer.wordpress.org/reference/functions/is_multisite/
             * True if Multisite is enabled, false otherwise.
             */
            if (! (!\is_network_admin() && \is_multisite())) {
                $settings = $this->aruba_hispeed_cache_settings(); // for php 5.3
                if ($settings['ahsc_enable_purge'] != 0) {

                    //infos string
                    $fieldsSets['ahsc_info'] = array(
                        'title' => \esc_html__('Automatically purge entire cache when:', 'aruba-hispeed-cache'),
                    );

                    //fieldsSets ahsc_purge_homepage
                    $fieldsSets['ahsc_purge_homepage'] =  array(
                        'th' => \esc_html__('Home page:', 'aruba-hispeed-cache'),
                        'legend_text' => \esc_html__('Home page:', 'aruba-hispeed-cache'),
                        'fields' => array(
                            'ahsc_purge_homepage_on_edit' => array(
                                'type' => 'checkbox',
                                'id' => 'ahsc_purge_homepage_on_edit',
                                'name' => 'ahsc_purge_homepage_on_edit',
                                'lable_for' => 'ahsc_purge_homepage_on_edit',
                                'lable_text' => \wp_kses(__('a <strong>post</strong> (or page/custom post) is <strong>modified</strong> or <strong>added</strong>.', 'aruba-hispeed-cache'), array( 'strong' => array() ))
                            ),
                            'ahsc_purge_homepage_on_del' => array(
                                'type' => 'checkbox',
                                'id' => 'ahsc_purge_homepage_on_del',
                                'name' => 'ahsc_purge_homepage_on_del',
                                'lable_for' => 'ahsc_purge_homepage_on_del',
                                'lable_text' => wp_kses(__('a <strong>published post</strong> (or page/custom post) is <strong>cancelled</strong>.', 'aruba-hispeed-cache'), array( 'strong' => array() ))
                            )
                        )
                    );

                    //fieldsSets ahsc_purge_page
                    $fieldsSets['ahsc_purge_page'] = array(
                        'th' => \esc_html__('Post/page/custom post type:', 'aruba-hispeed-cache'),
                        'legend_text' => \esc_html__('Post/page/custom post type:', 'aruba-hispeed-cache'),
                        'fields' => array(
                            'ahsc_purge_page_on_mod' => array(
                                'type' => 'checkbox',
                                'id' => 'ahsc_purge_page_on_mod',
                                'name' => 'ahsc_purge_page_on_mod',
                                'lable_for' => 'ahsc_purge_page_on_mod',
                                'lable_text' => \wp_kses(__('a <strong>post</strong> is <strong>published</strong>.', 'aruba-hispeed-cache'), array( 'strong' => array() ))
                            ),
                            'ahsc_purge_page_on_new_comment' => array(
                                'type' => 'checkbox',
                                'id' => 'ahsc_purge_page_on_new_comment',
                                'name' => 'ahsc_purge_page_on_new_comment',
                                'lable_for' => 'ahsc_purge_page_on_new_comment',
                                'lable_text' => \wp_kses(__('a <strong>comment</strong> is <strong>approved/published</strong>.', 'aruba-hispeed-cache'), array( 'strong' => array() ))
                            ),
                            'ahsc_purge_page_on_deleted_comment' => array(
                                'type' => 'checkbox',
                                'id' => 'ahsc_purge_page_on_deleted_comment',
                                'name' => 'ahsc_purge_page_on_deleted_comment',
                                'lable_for' => 'ahsc_purge_page_on_deleted_comment',
                                'lable_text' => \wp_kses(__('a <strong>comment</strong> is <strong>unapproved/deleted</strong>.', 'aruba-hispeed-cache'), array( 'strong' => array() ))
                            )
                        )
                    );

                    //fieldsSets ahsc_purge_archive
                    $fieldsSets['ahsc_purge_archive'] = array(
                        'th' => \esc_html__('Archives:', 'aruba-hispeed-cache'),
                        'small' => \esc_html__('(date, category, tag, author, custom taxonomies)', 'aruba-hispeed-cache'),
                        'legend_text' => \esc_html__('Archives:', 'aruba-hispeed-cache'),
                        'fields' => array(
                            'ahsc_purge_archive_on_edit' => array(
                                'type' => 'checkbox',
                                'id' => 'ahsc_purge_archive_on_edit',
                                'name' => 'ahsc_purge_archive_on_edit',
                                'lable_for' => 'ahsc_purge_archive_on_edit',
                                'lable_text' => \wp_kses(__('a <strong>post</strong> (or page/custom post) is <strong>modified</strong> or <strong>added</strong>.', 'aruba-hispeed-cache'), array( 'strong' => array() ))
                            ),
                            'ahsc_purge_archive_on_del' => array(
                                'type' => 'checkbox',
                                'id' => 'ahsc_purge_archive_on_del',
                                'name' => 'ahsc_purge_archive_on_del',
                                'lable_for' => 'ahsc_purge_archive_on_del',
                                'lable_text' => \wp_kses(__('a <strong>published post</strong> (or page/custom post) is <strong>cancelled</strong>.', 'aruba-hispeed-cache'), array( 'strong' => array() ))
                            )
                        )
                    );

                    //fieldsSets ahsc_purge_archive_on_comment
                    $fieldsSets['ahsc_purge_archive_on_comment'] = array(
                        'th' => \esc_html__('Comments:', 'aruba-hispeed-cache'),
                        'legend_text' => \esc_html__('Comments:', 'aruba-hispeed-cache'),
                        'fields' => array(
                            'ahsc_purge_archive_on_new_comment' => array(
                                'type' => 'checkbox',
                                'id' => 'ahsc_purge_archive_on_new_comment',
                                'name' => 'ahsc_purge_archive_on_new_comment',
                                'lable_for' => 'ahsc_purge_archive_on_new_comment',
                                'lable_text' => \wp_kses(__('a <strong>comment</strong> is <strong>approved/published</strong>.', 'aruba-hispeed-cache'), array( 'strong' => array() ))
                            ),
                            'ahsc_purge_archive_on_deleted_comment' => array(
                                'type' => 'checkbox',
                                'id' => 'ahsc_purge_archive_on_deleted_comment',
                                'name' => 'ahsc_purge_archive_on_deleted_comment',
                                'lable_for' => 'ahsc_purge_archive_on_deleted_comment',
                                'lable_text' => \wp_kses(__('a <strong>comment</strong> is <strong>unapproved/deleted</strong>.', 'aruba-hispeed-cache'), array( 'strong' => array() ))
                            )
                        )
                    );
                }
            }

            return $fieldsSets;
        }
    }
}
