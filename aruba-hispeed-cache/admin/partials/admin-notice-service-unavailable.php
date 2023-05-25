<?php
/**
 * A telmplate frame
 * php version 5.6
 *
 * @category Wordpress-plugin
 * @package  Aruba-HiSpeed-Cache
 * @author   Aruba Developer <hispeedcache.developer@aruba.it>
 * @license  https://www.gnu.org/licenses/gpl-2.0.html GPLv2 or later
 * @link     none
 */
?>
<div id="ahsc-service-error" class="notice notice-error">
    <p>
<?php \printf(
    \wp_kses(__('<strong>The HiSpeed Cache feature, with which the plugin interfaces, is not available on the server that hosts your website.</strong> To use HiSpeed Cache and the plugin, contact <a href="%s" rel="nofollow" target="_blank">support</a>.', 'aruba-hispeed-cache'), array( 'strong' => array(), 'a' => array( 'href' => array(), 'target' => array(), 'rel' => array()) )),
    esc_url(ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs::getLocalizedLink('link_assistance'))
);
?>
    </p>
</div>
