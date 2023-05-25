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

// @see ArubaHiSpeedCache\includes\_settings_manager function
$aruba_hispeed_cache_settings = $this->_settings_manager();

// @see ArubaHiSpeedCache\includes\_check_hispeed_cache_services_realtime function
$aruba_hispeed_cache_debugger = $this->_check_hispeed_cache_services_realtime();

?>

<form id="post_form" method="post" action="#" name="smart_http_expire_form" class="clearfix">

    <div class="ahsc-options-wrapper">
        <div class="inside">
            <table class="form-table ahsc-table">
                <?php foreach ($this->_form_fields() as $field_sets_key => $field_sets_data) : ?>
                    <?php if (isset($field_sets_data['title'])) : ?>
            </table>
            <h2 class="title"><?php echo esc_html($field_sets_data['title']); ?>
            </h2>
            <table class="form-table ahsc-table">
                        <?php continue; ?>
                    <?php endif; ?>

                <tr class="<?php echo esc_html($field_sets_key); ?>">
                    <th scope="row">
                        <?php echo esc_html($field_sets_data['th']); ?>
                        <small><?php echo (isset($field_sets_data['small'])) ? esc_html($field_sets_data['small']) : '' ?></small>
                    </th>
                    <td>
                        <fieldset>
                            <legend class="screen-reader-text">
                                <span><?php echo esc_html($field_sets_data['legend_text']); ?></span>
                            </legend>
                            <?php foreach ($field_sets_data['fields'] as $field_key => $field): ?>
                            <label
                                for="<?php echo esc_html($field_key); ?>">
                                <input type="hidden"
                                    name="<?php echo esc_html($field['name']); ?>"
                                    value="0" />
                                <input
                                    type="<?php echo esc_html($field['type']) ?>"
                                    value="1"
                                    name="<?php echo esc_html($field['name']); ?>"
                                    id="<?php  esc_html_e($field['id']); ?>"
                                    <?php \checked($aruba_hispeed_cache_settings[$field_key], 1); ?>
                                />
                                <?php _e($field['lable_text']); ?>
                            </label>
                            <?php endforeach; ?>
                        </fieldset>
                    </td>
                </tr>

                <?php endforeach;?>
            </table>
        </div>
    </div>

    <input type="hidden" name="smart_http_expire_form_nonce"
        value="<?php _e($this->_generate_settings_form_nonce()); ?>" />

</form> <!-- End of #post_form -->

<div class="ahsc-actions-wrapper">
    <table class="form-table ahst-table">
        <tr>
            <th></th>
            <td>
                <?php
                        \submit_button(__('Save changes', 'aruba-hispeed-cache'), 'primary', 'smart_http_expire_save', false, array('form' => 'post_form'));
?>
                <a id="purgeall"
                    href="<?php echo \esc_url($this->_generate_purge_nonce()); ?>"
                    class="button button-secondary">
                    <?php \esc_html_e('Purge entire cache', 'aruba-hispeed-cache'); ?>
                </a>
            </td>
        </tr>
    </table>
</div>

<?php if ($aruba_hispeed_cache_debugger) { ?>
<div class="ahsc-logger-wrapper">
    <div class="inside">
        <textarea id="logger" cols="30" rows="10" readonly
            style="width: 100%;"><?php echo $aruba_hispeed_cache_debugger ?></textarea>
    </div>
</div>
<?php };
