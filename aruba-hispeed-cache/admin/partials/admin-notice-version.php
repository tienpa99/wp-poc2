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

<div id="message" class="error">
    <p>
        <strong>
<?php
\printf(
    \esc_html__('Sorry, Aruba HiSpeed Cache requires WordPress %s or higher.', 'aruba-hispeed-cache'),
    \esc_html(ArubaHiSpeedCache\includes\ArubaHiSpeedCacheConfigs::ArubaHiSpeedCache_getConfigs('MINIMUM_WP'))
);
?>
        </strong>
    </p>
</div>
