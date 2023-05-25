<?php
/**
 * ArubaHiSpeedCacheConfigs - Utility class containing methods and settings for
 * the correct functioning of the plugin.
 *
 * @category Wordpress-plugin
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 * @link     run_aruba_hispeed_cache
 */


namespace ArubaHiSpeedCache\includes;

use defined;
use define;
use plugin_dir_path;
use current_user_can;
use update_site_option;
use get_role;

if (! \class_exists('ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs')) {
    /**
     * ArubaHiSpeedCacheConfigs
     *
     * @category ArubaHiSpeedCache\includes
     * @package  ArubaHiSpeedCache
     * @author   Aruba Developer <hispeedcache.developer@aruba.it>
     * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
     * @link     run_aruba_hispeed_cache
     */

    class ArubaHiSpeedCacheConfigs
    {
        /**
         * USER_CAP array
         */
        //@todo to remove?
        public static $USER_CAP = array(
            'Aruba Hispeed Cache | Config',
            'Aruba Hispeed Cache | Purge cache'
        );

        /**
         * PLUGIN_NAME string
         */
        public static $PLUGIN_NAME = 'aruba-hispeed-cache';

        /**
         * PLUGIN_VERSION string
         */
        public static $PLUGIN_VERSION = '1.2.4';

        /**
         * MINIMUM_WP string
         */
        public static $MINIMUM_WP = '5.4';

        /**
         * OPTIONS array
         */
        public static $OPTIONS = array(
            'ahsc_enable_purge',
            'ahsc_purge_homepage_on_edit',
            'ahsc_purge_homepage_on_del',
            'ahsc_purge_archive_on_edit',
            'ahsc_purge_archive_on_del',
            'ahsc_purge_archive_on_new_comment',
            'ahsc_purge_archive_on_deleted_comment',
            'ahsc_purge_page_on_mod',
            'ahsc_purge_page_on_new_comment',
            'ahsc_purge_page_on_deleted_comment'
        );

        /**
         * PURGE_HOST string
         * The host colled to purge the cache
         */
        public static $PURGE_HOST = '127.0.0.1';

        /**
         * PURGE_PORT string
         * the port of host.
         */
        public static $PURGE_PORT = '8889';

        /**
         * PURGE_TIME_OUT int
         */
        public static $PURGE_TIME_OUT = 5;

        /**
         * TRANSIENT_NAME the transient name
         *
         * @var string
         */
        public static $TRANSIENT_NAME = 'ahsc_activation_check';

        /**
         * TRANSIENT_LIFE_TIME the life time of transient
         *
         * @var int
         */
        public static $TRANSIENT_LIFE_TIME = 15 * \MINUTE_IN_SECONDS;

        /**
         * CHECK_TIMEOUT the time out of check request
         *
         * @var int
         */
        public static $CHECK_TIMEOUT = 15;

        /**
         * $LINK
         * the aruba links
         *
         * @var array
         */
        public static $LINKS = array(
            'link_base'             => array(
                'it' => 'https://hosting.aruba.it/',
                'en' => 'https://hosting.aruba.it/en/',
                'es' => 'https://hosting.aruba.it/es/'
            ),
            'link_guide'            => array(
                'it' => 'https://guide.hosting.aruba.it/hosting/cache-manager/gestione-cache.aspx',
            ),
            'link_assistance'       => array(
                'it' => 'https://assistenza.aruba.it/home.aspx',
                'en' => 'https://assistenza.aruba.it/en/home.aspx',
                'es' => 'https://assistenza.aruba.it/es/home.aspx'
            ),
            'link_hosting_truck'    => array(
                'it' => 'https://hosting.aruba.it/home.aspx?utm_source=pannello-wp&utm_medium=error-bar&utm_campain=aruba-hispeed-cache',
                'en' => 'https://hosting.aruba.it/en/home.aspx?utm_source=pannello-wp&utm_medium=error-bar&utm_campain=aruba-hispeed-cache',
                'es' => 'https://hosting.aruba.it/es/home.aspx?utm_source=pannello-wp&utm_medium=error-bar&utm_campain=aruba-hispeed-cache'
            ),
            'link_aruba_pca'        => array(
                'it' => 'https://admin.aruba.it/PannelloAdmin/Login.aspx?Lang=it',
                'en' => 'https://admin.aruba.it/PannelloAdmin/login.aspx?Op=ChangeLanguage&Lang=EN',
                'es' => 'https://admin.aruba.it/PannelloAdmin/login.aspx?Op=ChangeLanguage&Lang=ES'
            ),
        );

        /**
         * GetSiteHome - Helper method that returns the homepage address with support for wpml
         *
         * @return string $urlToTest url of wp website.
         */
        public static function getSiteHome()
        {
            $home_uri = \trailingslashit(\home_url());

            if (\function_exists('icl_get_home_url')) {
                $home_uri = \trailingslashit(\icl_get_home_url());
            }

            return $home_uri;
        }

        /**
         * ArubaHiSpeedCache_set_default_constant
         *
         * @param string $file   __FILE__ for the root directory.
         * @param string $prefix Possible prefix
         *
         * @return void
         */
        public static function ArubaHiSpeedCache_set_default_constant($file, $prefix = '')
        {
            $plugin_dir_path = \plugin_dir_path($file);

            $default_constants = array(
                'ARUBA_HISPEED_CACHE_PLUGIN'         => true,
                'ARUBA_HISPEED_CACHE_FILE'           => $file,
                'ARUBA_HISPEED_CACHE_BASEPATH'       => $plugin_dir_path,
                'ARUBA_HISPEED_CACHE_BASEURL'        => \plugin_dir_url($file),
                'ARUBA_HISPEED_CACHE_BASENAME'       => \plugin_basename($file),
                'ARUBA_HISPEED_CACHE_OPTIONS_NAME'   => 'aruba_hispeed_cache_options',
                'HOME_URL'                           => \get_home_url(null, '/'),
                'AHSC_DS'                            => DIRECTORY_SEPARATOR,
            );

            foreach ($default_constants as $name => $value) {
                if (! \defined($name)) {
                    \define($name, $value);
                }
            }
        }

        /**
         * ArubaHiSpeedCache_activate
         *
         * @return void
         */
        public static function ArubaHiSpeedCache_activate()
        {
            //get the option
            $options = \get_site_option(ARUBA_HISPEED_CACHE_OPTIONS_NAME);

            if (! $options) {
                $options = self::ArubaHiSpeedCache_get_default_settings();
            }

            \update_site_option(ARUBA_HISPEED_CACHE_OPTIONS_NAME, $options);
        }

        /**
         * Undocumented ArubaHiSpeedCache_register_setting
         *
         * @return void
         */
        public static function ArubaHiSpeedCache_update_plugins_db()
        {
            //get the option, set to empty array to def.
            $current_options = \is_array( \get_site_option(ARUBA_HISPEED_CACHE_OPTIONS_NAME) ) ? \get_site_option(ARUBA_HISPEED_CACHE_OPTIONS_NAME) : array();

            $new_options = self::ArubaHiSpeedCache_get_default_settings();

            $option_to_update = array_diff_key($new_options, $current_options);

            if (!empty($option_to_update)) {
                $option_merge = array_merge($new_options, $current_options);
                \update_site_option(ARUBA_HISPEED_CACHE_OPTIONS_NAME, $option_merge);
            }

            return;
        }

        /**
         * ArubaHiSpeedCache_deactivate
         *
         * @return void
         */
        public static function ArubaHiSpeedCache_deactivate()
        {
            //empty
        }

        /**
         * Deactivates this plugin, hook this function on admin_init.
         *
         * @since 1.0.1
         */
        public static function ArubaHiSpeedCache_deactivate_me()
        {
            if (\function_exists('deactivate_plugins')) {
                \deactivate_plugins(\ARUBA_HISPEED_CACHE_BASENAME);
            }
        }

        /**
         * ArubaHiSpeedCache_get_default_settings
         *
         * @return array $default_settings
         */
        public static function ArubaHiSpeedCache_get_default_settings()
        {
            $dafault_value = array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

            return \array_combine(self::$OPTIONS, $dafault_value);
        }

        /**
         * GetLocalizedLink
         *
         * @param  [type] $link
         * @return void
         */
        public static function getLocalizedLink($link)
        {
            $locale = substr(\get_locale(), 0, 2); // return it_IT -> it

            if (array_key_exists($locale, self::$LINKS[$link])) {
                return self::$LINKS[$link][$locale];
            }

            return self::$LINKS[$link]['it'];
        }

        /**
         * ArubaHiSpeedCache_getConfigs
         *
         * @param string $config name of config to get
         *
         * @return string|array confing
         */
        public static function ArubaHiSpeedCache_getConfigs($config)
        {
            $configs = array(
                'USER_CAP'            => self::$USER_CAP,
                'PLUGIN_NAME'         => self::$PLUGIN_NAME,
                'PLUGIN_VERSION'      => self::$PLUGIN_VERSION,
                'MINIMUM_WP'          => self::$MINIMUM_WP,
                'OPTIONS'             => self::$OPTIONS,
                'PURGE_HOST'          => self::$PURGE_HOST,
                'PURGE_PORT'          => self::$PURGE_PORT,
                'PURGE_TIME_OUT'      => self::$PURGE_TIME_OUT,
                'TRANSIENT_NAME'      => self::$TRANSIENT_NAME,
                'TRANSIENT_LIFE_TIME' => self::$TRANSIENT_LIFE_TIME,
                'CHECK_TIMEOUT'       => self::$CHECK_TIMEOUT
            );

            return $configs[$config];
        }
    }
}
