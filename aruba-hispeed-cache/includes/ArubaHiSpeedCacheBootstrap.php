<?php
/**
 * ArubaHiSpeedCacheBootstrap - Control center for everything.
 * php version 5.6
 *
 * @category Wordpress-plugin
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 * @link     Null
 * @since    1.0.0
 */

namespace ArubaHiSpeedCache\includes;

use esc_html;
use esc_html__;
use is_multisite;
use version_compare;
use wp_delete_file;

use ArubaHiSpeedCache\includes\ArubaHiSpeedCachei18n;
use ArubaHiSpeedCache\includes\ArubaHiSpeedCacheAdmin;
use ArubaHiSpeedCache\includes\ArubaHiSpeedCacheLoader;
use ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs;
use ArubaHiSpeedCache\includes\HiSpeedCacheServiceChecker;
use ArubaHiSpeedCache\includes\ArubaHispeeCacheLogger as Logger;

if (! class_exists(__NAMESPACE__ . '\ArubaHiSpeedCacheBootstrap')) {
    class ArubaHiSpeedCacheBootstrap
    {
        /**
         * Undocumented variable
         *
         * @var [type]
         */
        protected $loader;

        /**
         * Configs
         *
         * @var [type]
         */
        public static $config;

        /**
         * Undocumented function
         */
        public function __construct()
        {
            // $this->config = $this->getConfigs();

            if (!$this->required_wp_version()) {
                ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_deactivate_me();
            }

            $this->_load_dependencies();
            $this->_set_locale();
            $this->_define_admin_hooks();
        }

        /**
         * Load_dependencies
         *
         * @see    ArubaHiSpeedCache_get_dependencies
         * @return void
         */
        private function _load_dependencies()
        {
            include_once \plugin_dir_path(ARUBA_HISPEED_CACHE_FILE)  . 'includes' .AHSC_DS. 'ArubaHiSpeedCacheLoader.php';
            include_once \plugin_dir_path(ARUBA_HISPEED_CACHE_FILE)  . 'includes' .AHSC_DS. 'ArubaHiSpeedCachei18n.php';
            include_once \plugin_dir_path(ARUBA_HISPEED_CACHE_FILE)  . 'includes' .AHSC_DS. 'ArubaHiSpeedCacheAdmin.php';
            include_once \plugin_dir_path(ARUBA_HISPEED_CACHE_FILE)  . 'includes' .AHSC_DS. 'ArubaHiSpeedCachePurger.php';
            include_once \plugin_dir_path(ARUBA_HISPEED_CACHE_FILE)  . 'includes' .AHSC_DS. 'ArubaHiSpeedCacheWpPurger.php';

            include_once \plugin_dir_path(ARUBA_HISPEED_CACHE_FILE)  . 'includes' .AHSC_DS. 'HiSpeedCacheServiceChecker.php';

            if (\file_exists(\plugin_dir_path(ARUBA_HISPEED_CACHE_FILE)  . 'includes' .AHSC_DS. 'ArubaHispeedCacheLogger.php') && WP_DEBUG) {
                include_once \plugin_dir_path(ARUBA_HISPEED_CACHE_FILE)  . 'includes' .AHSC_DS. 'ArubaHispeedCacheLogger.php';
            } else {
                if (file_exists(\plugin_dir_path(ARUBA_HISPEED_CACHE_FILE) . 'ahscLog.log')) {
                    \wp_delete_file(\plugin_dir_path(ARUBA_HISPEED_CACHE_FILE) . 'ahscLog.log');
                }
            }

            $this->loader = new ArubaHiSpeedCacheLoader();
        }

        /**
         * Set_locale
         *
         * @return void
         */
        private function _set_locale()
        {
            $plugin_i18n = new ArubaHiSpeedCachei18n(ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('PLUGIN_NAME'), ARUBA_HISPEED_CACHE_BASENAME);

            $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
        }

        /**
         * Define_admin_hooks
         *
         * @return void
         */
        private function _define_admin_hooks()
        {
            global $aruba_hispeed_cache_admin, $aruba_hispeed_cache_purger, $pagenow;

            $aruba_hispeed_cache_admin = new ArubaHiSpeedCacheAdmin();
            $aruba_hispeed_cache_purger = new ArubaHiSpeedCacheWpPurger($aruba_hispeed_cache_admin);

            $this->loader->add_action('admin_enqueue_scripts', $aruba_hispeed_cache_admin, 'enqueue_scripts');

            // /**
            //  * Remove option to deactivate the plugin
            //  * @since 2.0.0
            //  */
            // \add_filter('plugin_action_links', function ($actions, $plugin_file, $plugin_data, $context) {
            //     if (\array_key_exists('deactivate', $actions) && ARUBA_HISPEED_CACHE_BASENAME === $plugin_file) {
            //         $actions = [];
            //     }

            //     return $actions;
            // }, 10, 4);

            if (\is_multisite()) {
                $this->loader->add_action('network_admin_menu', $aruba_hispeed_cache_admin, 'aruba_hispeed_cache_admin_menu');
                $this->loader->add_filter('network_admin_plugin_action_links_' . ARUBA_HISPEED_CACHE_BASENAME, $aruba_hispeed_cache_admin, 'aruba_hispeed_cache_settings_link');
            } else {
                $this->loader->add_action('admin_menu', $aruba_hispeed_cache_admin, 'aruba_hispeed_cache_admin_menu');
                $this->loader->add_filter('plugin_action_links_' . ARUBA_HISPEED_CACHE_BASENAME, $aruba_hispeed_cache_admin, 'aruba_hispeed_cache_settings_link');
            }

            /**
             * I check if the 'ahsc_enable_purge' option is activated and in this case I add the hooks
             */
            if (! empty($aruba_hispeed_cache_admin->options['ahsc_enable_purge']) && ARUBA_HISPEED_CACHE_PLUGIN) {
                $this->loader->add_action('admin_bar_menu', $aruba_hispeed_cache_admin, 'aruba_hispeed_cache_toolbar_purge_link', 100);

                // Add actions to purge.
                $this->loader->add_action('wp_insert_comment', $aruba_hispeed_cache_purger, 'ahsc_wp_insert_comment', 200, 2);
                $this->loader->add_action('transition_comment_status', $aruba_hispeed_cache_purger, 'ahsc_transition_comment_status', 200, 3);

                $this->loader->add_action('transition_post_status', $aruba_hispeed_cache_purger, 'ahsc_transition_post_status', 20, 3);

                $this->loader->add_action('edit_term', $aruba_hispeed_cache_purger, 'ahsc_edit_term', 20, 3);
                $this->loader->add_action('delete_term', $aruba_hispeed_cache_purger, 'ahsc_delete_term', 20, 4);

                $this->loader->add_action('check_ajax_referer', $aruba_hispeed_cache_purger, 'ahsc_check_ajax_referer', 20);

                /**
                 * FES support.
                 *
                 * @since 1.2.0
                 */
                if (is_admin()) {
                    //$aruba_hispeed_cache_purger->Logger('wp-json request detected', 'request');
                    $this->loader->add_action('admin_init', $aruba_hispeed_cache_purger, 'ahsc_deferred_purge_by_transient');

                    //
                    $this->loader->add_action('check_admin_referer', $aruba_hispeed_cache_purger, 'ahsc_bulk_manager', 20, 2);

                    //
                    $this->loader->add_action('post_updated', $aruba_hispeed_cache_purger, 'ahsc_post_updated', 200, 3);
                }

                /**
                 * If you are on the menu management page, remove the hooks queuing.
                 *
                 * @since 1.2.1
                 */
                if (is_admin() && 'nav-menus.php' === $pagenow) {
                    $this->loader->remove_action('edit_term');
                    $this->loader->remove_action('transition_post_status');
                    $this->loader->remove_action('post_updated');
                    $this->loader->add_action('wp_update_nav_menu', $aruba_hispeed_cache_purger, 'ahsc_wp_update_nav_menu', 20, 1);
                }

                /**
                 * Plugin actions
                 *
                 * @since 1.2.0
                 */
                $this->loader->add_action('activated_plugin', $aruba_hispeed_cache_purger, 'ahsc_purge_on_plugin_actions', 200, 1);
                $this->loader->add_action('deactivate_plugin', $aruba_hispeed_cache_purger, 'ahsc_purge_on_plugin_actions', 200, 1);
                $this->loader->add_action('delete_plugin', $aruba_hispeed_cache_purger, 'ahsc_purge_on_plugin_actions', 200, 1);

                /**
                 * Theme action
                 *
                 * @since 1.2.0
                 */
                $this->loader->add_action('switch_theme', $aruba_hispeed_cache_purger, 'ahsc_purge_on_theme_actions', 200, 3);
            }

            $this->loader->add_action('admin_bar_init', $aruba_hispeed_cache_purger, 'ahsc_admin_bar_init');

            //the chech on activation of plugin
            $this->loader->add_action('admin_notices', $aruba_hispeed_cache_admin, 'check_hispeed_cache_notices');

            //init
            $this->loader->add_action('admin_init', '\ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs', 'ArubaHiSpeedCache_update_plugins_db');
            
            if ( version_compare( get_bloginfo( 'version' ), '6.1.0', '>=' ) ) {
                \add_filter( 'site_status_page_cache_supported_cache_headers', function( $cache_headers  ) {
                    // Add new header to the existing list.
                    $cache_headers['x-aruba-cache'] = static function ( $header_value ) {
                        return false !== strpos( strtolower( $header_value ), 'hit' );
                    };
                    return $cache_headers;
                });
            }            
            
        }

        /**
         * Run
         *
         * Wrap for the Aruba_HiSpeed_Cache_Loader/run method
         *
         * @see    Aruba_HiSpeed_Cache_Loader/run
         * @return void
         */
        public function run()
        {
            $this->loader->run();
        }

        /**
         * Get_loader
         *
         * @return ArubaHiSpeedCache\Aruba_HiSpeed_Cache_Loader
         */
        public function get_loader()
        {
            return $this->loader;
        }

        /**
         * Required_wp_version
         * Checking the currently installed version and the minimum required version.
         *
         * @return bool
         */
        public function required_wp_version()
        {
            global $wp_version;

            if (!\version_compare($wp_version, ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('MINIMUM_WP'), '>=')) {
                \add_action('admin_notices', array( &$this, 'display_notices' ));
                \add_action('network_admin_notices', array( &$this, 'display_notices' ));
                return false;
            }

            return true;
        }

        /**
         * Check_hispeed_cache_services - check if aruba hispeed cache service is activable
         * or is a aruba server.
         *
         * @param  string $plugin relative path of the plugin.
         *
         * @return void
         */

        public function check_hispeed_cache_services($plugin)
        {
            if ('aruba-hispeed-cache/aruba-hispeed-cache.php' === $plugin) {
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

                if (\is_multisite() && !is_null($notices_file)) {
                    \set_site_transient(
                        ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('TRANSIENT_NAME'),
                        $notices_file,
                        ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('TRANSIENT_LIFE_TIME')
                    );
                } elseif (!is_null($notices_file)) {
                    \set_transient(
                        ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('TRANSIENT_NAME'),
                        $notices_file,
                        ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('TRANSIENT_LIFE_TIME')
                    );
                }
            }
        }

        /**
         * Display_notices
         * Adds the error message in case the function required_wp_version returns false
         *
         * @return void
         */
        public function display_notices()
        {
            include_once ARUBA_HISPEED_CACHE_BASEPATH . 'admin' .AHSC_DS. 'partials' .AHSC_DS. 'admin-notice-version.php';
        }
    }
}


//--------------
// Logger function
//--------------
function logger($message, string $name = '', string $livel = 'debug')
{
    if (!class_exists(__NAMESPACE__ . '\ArubaHispeeCacheLogger')) {
        return;
    }

    switch ($livel) {
        case 'debug':
            Logger::debug($message, $name);
            break;
        case 'info':
            Logger::info($message, $name);
            break;
        case 'warning':
            Logger::warning($message, $name);
            break;
        case 'error':
            Logger::error($message, $name);
            break;
    }

    return;
}
