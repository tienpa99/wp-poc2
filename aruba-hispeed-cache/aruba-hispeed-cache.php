<?php
/**
 * Aruba HiSpeed Cache
 * php version 5.6
 *
 * @category Wordpress-plugin
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 * @link     null
 * @since    1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       Aruba HiSpeed Cache
 * Version:           1.2.4
 * Plugin URI:        https://hosting.aruba.it/wordpress.aspx
 * Description:       Aruba HiSpeed Cache interfaces directly with an Aruba hosting platform's HiSpeed Cache service and automates its management.
 * Author:            Aruba.it
 * Author URI:        https://www.aruba.it/
 * Text Domain:       aruba-hispeed-cache
 * Domain Path:       /languages
 * License:           GPL v2
 * Requires at least: 5.4
 * Tested up to:      6.2
 * Requires PHP:      5.6
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace ArubaHiSpeedCache;

use ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs;
use ArubaHiSpeedCache\includes\ArubaHiSpeedCacheBootstrap;

use register_activation_hook;
use register_deactivation_hook;
use defined;

if (!defined('WPINC')) {
    die;
}

/**
 * Require ArubaHiSpeedCacheConfigs.php to boostrasp the confing of plugin execution
 * of the method ArubaHiSpeedCache_set_default_constant for the generation of the
 * environment variables
 */
require_once __DIR__ . DIRECTORY_SEPARATOR . 'includes' .DIRECTORY_SEPARATOR. 'ArubaHiSpeedCacheConfigs.php';
ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_set_default_constant(__FILE__);

/**
 * Adding methods to "activate" hooks
 */
\register_activation_hook(
    \ARUBA_HISPEED_CACHE_FILE,
    function () {
        ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_activate();
    }
);

/**
 * Adding methods to "deactivate" hooks
 */
\register_deactivation_hook(
    \ARUBA_HISPEED_CACHE_FILE,
    function () {
        ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_deactivate();
    }
);

/**
 * Load bootstrap if not loaded
 */
if (!class_exists(__NAMESPACE__ . '\includes\ArubaHiSpeedCacheBootstrap')) {
    include_once \ARUBA_HISPEED_CACHE_BASEPATH . 'includes' .\AHSC_DS. 'ArubaHiSpeedCacheBootstrap.php';
}


/**
 * Run on WP Init
 */
$bootstrap = new ArubaHiSpeedCacheBootstrap();

//check on activation
add_action('activated_plugin', array($bootstrap, 'check_hispeed_cache_services'), 20, 1);

//Init
add_action('init', array($bootstrap, 'run'));
