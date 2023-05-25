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
<?php
printf(
    \wp_kses(__('<strong>The Aruba HiSpeed Cache plugin cannot be used because your WordPress website is not hosted on an Aruba hosting platform.</strong> Buy an <a href="%s" rel="nofollow" target="_blank">Aruba hosting</a> service and migrate your website to use the plugin.', 'aruba-hispeed-cache'), array( 'strong' => array(), 'a' => array( 'href' => array(), 'target' => array(), 'rel' => array()) )),
    esc_url(ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs::getLocalizedLink('link_hosting_truck'))
);
?>
    </p>
</div>
