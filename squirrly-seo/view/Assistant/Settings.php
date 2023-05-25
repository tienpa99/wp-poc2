<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<div id="sq_wrap">
    <?php $view->show_view('Blocks/Toolbar'); ?>
    <?php do_action('sq_notices'); ?>
    <div id="sq_content" class="d-flex flex-row bg-white my-0 p-0 m-0">
        <?php
        if (!SQ_Classes_Helpers_Tools::userCan('sq_manage_settings')) {
            echo '<div class="col-12 alert alert-success text-center m-0 p-3">'. esc_html__("You do not have permission to access this page. You need Squirrly SEO Admin role.", 'squirrly-seo').'</div>';
            return;
        }
        ?>
        <?php $view->show_view('Blocks/Menu'); ?>
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-light m-0 p-0">
            <div class="flex-grow-1 sq_flex m-0 p-0 px-4">
                <?php do_action('sq_form_notices'); ?>


                <form method="POST">
                    <?php SQ_Classes_Helpers_Tools::setNonce('sq_settings_assistant', 'sq_nonce'); ?>
                    <input type="hidden" name="action" value="sq_settings_assistant"/>

                    <div class="sq_breadcrumbs my-4"><?php echo SQ_Classes_ObjController::getClass('SQ_Models_Menu')->getBreadcrumbs('sq_assistant/settings') ?></div>
                    <h3 class="mt-4 card-title">
                        <?php echo esc_html__("Live Assistant Settings", 'squirrly-seo'); ?>
                        <div class="sq_help_question d-inline">
                            <a href="https://howto12.squirrly.co/kb/squirrly-live-assistant/#settings" target="_blank"><i class="fa-solid fa-question-circle"></i></a>
                        </div>
                    </h3>

                    <div id="sq_seosettings" class="col-12 p-0 m-0">
                        <div class="col-12 m-0 p-0">
                            <div class="col-12 m-0 p-0">

                                <div class="col-12 m-0 p-0">
                                    <?php if (SQ_Classes_Helpers_Tools::getOption('sq_seoexpert') ||
                                        SQ_Classes_Helpers_Tools::isPluginInstalled('elementor/elementor.php') ||
                                        SQ_Classes_Helpers_Tools::isPluginInstalled('bb-plugin/fl-builder.php') ||
                                        SQ_Classes_Helpers_Tools::isPluginInstalled('oxygen/functions.php') ||
                                        SQ_Classes_Helpers_Tools::isPluginInstalled('divi-builder/divi-builder.php') ||
                                        SQ_Classes_Helpers_Tools::isThemeActive('Divi') ||
                                        SQ_Classes_Helpers_Tools::isThemeActive('bricks') ||
                                        SQ_Classes_Helpers_Tools::isPluginInstalled('js_composer/js_composer.php') ||
                                        SQ_Classes_Helpers_Tools::isPluginInstalled('zionbuilder/zionbuilder.php') ) { ?>
                                        <div class="col-12 row m-0 p-0">
                                            <div class="checker col-12 row my-4 p-0 mx-0">
                                                <div class="col-12 p-0 m-0 sq-switch sq-switch-sm">
                                                    <input type="hidden" name="sq_sla_frontend" value="0"/>
                                                    <input type="checkbox" id="sq_sla_frontend" name="sq_sla_frontend" class="sq-switch" <?php echo(SQ_Classes_Helpers_Tools::getOption('sq_sla_frontend') ? 'checked="checked"' : '') ?> value="1"/>
                                                    <label for="sq_sla_frontend" class="ml-1"><?php echo esc_html__("Activate Live Assistant in Frontend", 'squirrly-seo'); ?><span class="text-danger"></span>
                                                        <a href="https://howto12.squirrly.co/kb/squirrly-live-assistant/#Add-Live-Assistant-in-Frontend" target="_blank"><i class="fa-solid fa-question-circle m-0 px-2" style="display: inline;"></i></a>
                                                    </label>
                                                    <div class="small text-black-50 ml-5"><?php echo esc_html__("Load Squirrly Live Assistant in Frontend to customize the posts and pages with Builders.", 'squirrly-seo'); ?></div>
                                                    <div class="small text-black-50 ml-5 mt-1"><?php echo sprintf(esc_html__("Supports %s Elementor Builder %s plugin.", 'squirrly-seo'),'<a href="https://elementor.com/" target="_blank" class="font-weight-bold">','</a>'); ?></div>
                                                    <div class="small text-black-50 ml-5 mt-1"><?php echo sprintf(esc_html__("Supports %s Oxygen Builder %s plugin.", 'squirrly-seo'),'<a href="https://oxygenbuilder.com/" target="_blank" class="font-weight-bold">','</a>'); ?></div>
                                                    <div class="small text-black-50 ml-5 mt-1"><?php echo sprintf(esc_html__("Supports %s Divi Builder %s plugin.", 'squirrly-seo'),'<a href="https://www.elegantthemes.com/gallery/divi/" target="_blank" class="font-weight-bold">','</a>'); ?></div>
                                                    <div class="small text-black-50 ml-5 mt-1"><?php echo sprintf(esc_html__("Supports %s Thrive Architect %s plugin.", 'squirrly-seo'),'<a href="https://thrivethemes.com/architect/" target="_blank" class="font-weight-bold">','</a>'); ?></div>
                                                    <div class="small text-black-50 ml-5 mt-1"><?php echo sprintf(esc_html__("Supports %s Bricks Website Builder %s.", 'squirrly-seo'),'<a href="https://bricksbuilder.io/" target="_blank" class="font-weight-bold">','</a>'); ?></div>
                                                    <div class="small text-black-50 ml-5 mt-1"><?php echo sprintf(esc_html__("Supports %s WPBakery Page Builder %s plugin.", 'squirrly-seo'),'<a href="https://wpbakery.com/" target="_blank" class="font-weight-bold">','</a>'); ?></div>
                                                    <div class="small text-black-50 ml-5 mt-1"><?php echo sprintf(esc_html__("Supports %s Zion Builder %s plugin.", 'squirrly-seo'),'<a href="https://zionbuilder.io/" target="_blank" class="font-weight-bold">','</a>'); ?></div>
                                                    <div class="small text-black-50 ml-5 mt-1"><?php echo sprintf(esc_html__("Supports %s Beaver Builder %s plugin.", 'squirrly-seo'),'<a href="https://www.wpbeaverbuilder.com/" target="_blank" class="font-weight-bold">','</a>'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php }?>

                                    <div class="col-12 row m-0 p-0">
                                        <div class="checker col-12 row my-4 p-0 mx-0">
                                            <div class="col-12 p-0 m-0 sq-switch sq-switch-sm">
                                               <input type="hidden" name="sq_img_licence" value="0"/>
                                                <input type="checkbox" id="sq_img_licence" name="sq_img_licence" class="sq-switch" <?php echo(SQ_Classes_Helpers_Tools::getOption('sq_img_licence') ? 'checked="checked"' : '') ?> value="1"/>
                                                <label for="sq_img_licence" class="ml-1"><?php echo esc_html__("Show Copyright Free Images", 'squirrly-seo'); ?>
                                                    <a href="https://howto12.squirrly.co/kb/squirrly-live-assistant/#copyright_free_images" target="_blank"><i class="fa-solid fa-question-circle m-0 px-2" style="display: inline;"></i></a>
                                                </label>
                                                <div class="small text-black-50 ml-5"><?php echo sprintf(esc_html__("Search %sCopyright Free Images%s in Squirrly Live Assistant.", 'squirrly-seo'), '<strong>', '</strong>'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 row m-0 p-0 sq_advanced">
                                        <div class="checker col-12 row my-4 p-0 mx-0">
                                            <div class="col-12 p-0 m-0 sq-switch sq-switch-sm">
                                                <input type="hidden" name="sq_sla_social_fetch" value="0"/>
                                                <input type="checkbox" id="sq_sla_social_fetch" name="sq_sla_social_fetch" class="sq-switch" <?php echo(SQ_Classes_Helpers_Tools::getOption('sq_sla_social_fetch') ? 'checked="checked"' : '') ?> value="1"/>
                                                <label for="sq_sla_social_fetch" class="ml-1"><?php echo esc_html__("Fetch Snippet on Social Media", 'squirrly-seo'); ?>
                                                    <a href="https://howto12.squirrly.co/kb/squirrly-live-assistant/#fetch_snippet" target="_blank"><i class="fa-solid fa-question-circle m-0 px-2" style="display: inline;"></i></a>
                                                </label>
                                                <div class="small text-black-50 ml-5"><?php echo sprintf(esc_html__("Automatically fetch the Squirrly Snippet on %sFacebook Sharing Debugger%s every time you update the content on a page.", 'squirrly-seo'), '<a href="https://developers.facebook.com/tools/debug/" target="_blank">', '</a>'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 row m-0 p-0 sq_advanced">
                                        <div class="checker col-12 row my-4 p-0 mx-0">
                                            <div class="col-12 p-0 m-0 sq-switch sq-switch-sm">
                                                <input type="hidden" name="sq_local_images" value="0"/>
                                                <input type="checkbox" id="sq_local_images" name="sq_local_images" class="sq-switch" <?php echo(SQ_Classes_Helpers_Tools::getOption('sq_local_images') ? 'checked="checked"' : '') ?> value="1"/>
                                                <label for="sq_local_images" class="ml-1"><?php echo esc_html__("Download Remote Images", 'squirrly-seo'); ?>
                                                    <a href="https://howto12.squirrly.co/kb/squirrly-live-assistant/#download_images" target="_blank"><i class="fa-solid fa-question-circle m-0 px-2" style="display: inline;"></i></a>
                                                </label>
                                                <div class="small text-black-50 ml-5"><?php echo sprintf(esc_html__("Download %sremote images%s in your %sMedia Library%s for the new posts.", 'squirrly-seo'), '<strong>', '</strong>', '<strong>', '</strong>'); ?></div>
                                                <div class="small text-black-50 ml-5"><?php echo esc_html__("Prevent from losing the images you use in your articles in case the remote images are deleted.", 'squirrly-seo'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 row m-0 p-0 sq_advanced">
                                        <div class="checker col-12 row my-4 p-0 mx-0">
                                            <div class="col-12 p-0 m-0 sq-switch sq-switch-sm">
                                                <input type="hidden" name="sq_keyword_help" value="0"/>
                                                <input type="checkbox" id="sq_keyword_help" name="sq_keyword_help" class="sq-switch" <?php echo(SQ_Classes_Helpers_Tools::getOption('sq_keyword_help') ? 'checked="checked"' : '') ?> value="1"/>
                                                <label for="sq_keyword_help" class="ml-1"><?php echo esc_html__("Show Tooltips", 'squirrly-seo'); ?>
                                                    <a href="https://howto12.squirrly.co/kb/squirrly-live-assistant/#tooltip" target="_blank"><i class="fa-solid fa-question-circle m-0 px-2" style="display: inline;"></i></a>
                                                </label>
                                                <div class="small text-black-50 ml-5"><?php echo sprintf(esc_html__("Show %s Tooltips %s in Squirrly Live Assistant for tasks and sections.", 'squirrly-seo'), '<strong>', '</strong>'); ?></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 row m-0 p-0 my-5 sq_advanced">
                                        <div class="col-4 p-0 font-weight-bold">
                                            <?php echo esc_html__("Live Assistant Type", 'squirrly-seo'); ?>
                                            <a href="https://howto12.squirrly.co/kb/squirrly-live-assistant/#settings" target="_blank"><i class="fa-solid fa-question-circle m-0 px-2" style="display: inline;"></i></a>
                                        </div>
                                        <div class="col-8 m-0 p-0 input-group">
                                            <select name="sq_sla_type" class="form-control bg-input mb-1">
                                                <option value="auto" <?php echo((SQ_Classes_Helpers_Tools::getOption('sq_sla_type') == 'auto') ? 'selected="selected"' : '') ?>><?php echo esc_html__("Auto", 'squirrly-seo'); ?></option>
                                                <option value="integrated" <?php echo((SQ_Classes_Helpers_Tools::getOption('sq_sla_type') == 'integrated') ? 'selected="selected"' : '') ?>><?php echo esc_html__("Integrated Box", 'squirrly-seo'); ?></option>
                                                <option value="floating" <?php echo((SQ_Classes_Helpers_Tools::getOption('sq_sla_type') == 'floating') ? 'selected="selected"' : '') ?>><?php echo esc_html__("Floating Box", 'squirrly-seo'); ?></option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12 p-0 m-0 my-5">
                                    <div class="col-12 row m-0 p-0">
                                        <div class="col-4 p-0 font-weight-bold">
                                            <?php echo esc_html__("Exclusions", 'squirrly-seo'); ?>:
                                            <a href="https://howto12.squirrly.co/kb/squirrly-live-assistant/#disable_live_assistant" target="_blank"><i class="fa-solid fa-question-circle m-0 px-2" style="display: inline;"></i></a>
                                            <div class="small text-black-50 pr-3 my-1"><?php echo esc_html__("Select places where you do NOT want Squirrly Live Assistant to load.", 'squirrly-seo'); ?></div>
                                        </div>
                                        <div class="col-8 p-0 m-0 form-group">
                                            <input type="hidden" name="sq_sla_exclude_post_types[]" value="0"/>
                                            <select multiple name="sq_sla_exclude_post_types[]" class="selectpicker form-control bg-input mb-1" data-live-search="true">
                                                <?php
                                                $types = get_post_types(array('public' => true));
                                                foreach ($types as $type) {
                                                    $type_data = get_post_type_object($type);
                                                    echo '<option value="' . esc_attr($type) . '" ' . (in_array($type, (array)SQ_Classes_Helpers_Tools::getOption('sq_sla_exclude_post_types')) ? 'selected="selected"' : '') . '>' . esc_html($type_data->labels->name) . '</option>';
                                                } ?>
                                            </select>  
                                            <div class="small text-danger py-3 my-1"><?php echo esc_html__("Don't select anything if you wish Squirrly Live Assistant to load for all post types.", 'squirrly-seo'); ?></div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 my-3 p-0">
                        <button type="submit" class="btn rounded-0 btn-primary btn-lg m-0 px-5 "><?php echo esc_html__("Save Settings", 'squirrly-seo'); ?></button>
                    </div>
                </form>

                <div class="sq_tips col-12 m-0 p-0 my-5">
                    <h5 class="text-left my-3 font-weight-bold"><i class="fa-solid fa-exclamation-circle" ></i> <?php echo esc_html__("Tips and Tricks", 'squirrly-seo'); ?></h5>
                    <ul class="mx-4">
                        <li class="text-left"><?php echo esc_html__("The Settings section allows you to set up your assistant just the way you like it.", 'squirrly-seo'); ?></li>
                        <li class="text-left"><?php echo esc_html__("Quickly enable or disable the settings available here based on your needs and preferences.", 'squirrly-seo'); ?></li>
                    </ul>
                </div>

                <?php SQ_Classes_ObjController::getClass('SQ_Core_BlockKnowledgeBase')->init(); ?>

            </div>
            <div class="sq_col_side bg-white">
                <div class="col-12 m-0 p-0 sq_sticky">
                    <?php echo SQ_Classes_ObjController::getClass('SQ_Core_BlockAssistant')->init(); ?>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
