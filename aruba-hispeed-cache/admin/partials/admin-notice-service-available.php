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

<div id="ahsc-service-warning" class="notice notice-warning">
    <p>
<?php \printf(
    \wp_kses(__('<strong>The HiSpeed Cache feature is not enabled.</strong> To enable it, go to your domain <a href="%s" rel="nofollow" target="_blank">control panel</a> (verifying the request may take up to 15 minutes). For further details <a href="%s" rel="nofollow" target="_blank">see our guide</a>.', 'aruba-hispeed-cache'), array( 'strong' => array(), 'a' => array( 'href' => array(), 'target' => array(), 'rel' => array()) )),
    esc_url(ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs::getLocalizedLink('link_aruba_pca')),
    esc_url(ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs::getLocalizedLink('link_guide'))
);
?>
    </p>
</div>
