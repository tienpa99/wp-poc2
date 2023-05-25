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

global $pagenow;
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap ahsc-wrapper">
    <h1 class="ahsc-option-title">
        <?php \esc_html_e('Aruba HiSpeed Cache Settings ', 'aruba-hispeed-cache'); ?>
    </h1>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
<?php
                    require ARUBA_HISPEED_CACHE_BASEPATH . 'admin' .AHSC_DS. 'partials' .AHSC_DS. 'admin-general-options.php';
?>
            </div> <!-- End of #post-body-content -->
            <div id="postbox-container-1" class="postbox-container">
                <!-- empty sidebar -->
            </div> <!-- End of #postbox-container-1 -->
        </div> <!-- End of #post-body -->
    </div> <!-- End of #poststuff -->
</div> <!-- End of .wrap .ahsc-wrapper -->
