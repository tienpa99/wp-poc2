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

                    <div class="sq_breadcrumbs my-4"><?php echo SQ_Classes_ObjController::getClass('SQ_Models_Menu')->getBreadcrumbs('sq_assistant/settings') ?></div>
                    <h3 class="mt-4 card-title">
                        <?php echo esc_html__("IndexNow Settings", 'squirrly-seo'); ?>
                        <div class="sq_help_question d-inline">
                            <a href="https://howto12.squirrly.co/kb/indexnow/" target="_blank"><i class="fa-solid fa-question-circle"></i></a>
                        </div>
                    </h3>

                    <div class="col-7 small m-0 p-0">
                        <?php echo esc_html__("Automatically send URLs to the IndexNow API. The post types are automatically sent to the IndexNow API when a post is published, updated, or trashed.", 'squirrly-seo'); ?>
                    </div>

                    <div id="sq_seosettings" class="col-12 p-0 m-0">
                        <div class="col-12 m-0 p-0">
                            <div class="col-12 m-0 p-0">

                                <div class="col-12 p-0 m-0 my-5">

                                    <form method="POST">
                                        <?php SQ_Classes_Helpers_Tools::setNonce('sq_seosettings_indexnow_reset', 'sq_nonce'); ?>
                                        <input type="hidden" name="action" value="sq_seosettings_indexnow_reset"/>

                                        <div class="col-12 row p-0 m-0">
                                            <div class="col-4 p-0 pr-3 font-weight-bold">
                                                <?php echo esc_html__("IndexNow Key", 'squirrly-seo'); ?>:
                                                <div class="small text-black-50 my-1"><?php echo esc_html__("The IndexNow key confirms the ownership of the site. The key is generated automatically.", 'squirrly-seo'); ?></div>
                                            </div>
                                            <div class="col-8 p-0">

                                                <div class="col-12 row m-0 p-0 my-1">
                                                    <div class="col-9 m-0 p-0 input-group">
                                                        <input type="text" disabled="disabled" class="form-control bg-input" value="<?php echo SQ_Classes_ObjController::getClass('SQ_Models_Indexnow')->getKey() ?>"/>
                                                    </div>
                                                    <div class="col m-0 p-0 input-group" >
                                                        <button type="submit" class="btn btn-block btn-warning btn-sm mx-2 py-2"><i class="dashicons dashicons-update m-1"></i><?php echo esc_html__("Reset Key", 'squirrly-seo'); ?></button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </form>

                                    <div class="col-12 row m-0 p-0">
                                        <div class="col-12 row p-0 m-0 my-5">
                                            <div class="col-4 p-0 pr-3 font-weight-bold">
                                                <?php echo esc_html__("IndexNow Key URL", 'squirrly-seo'); ?>:
                                                <div class="small text-black-50 my-1"><?php echo esc_html__("The IndexNow key URL should show the API key when opened.", 'squirrly-seo'); ?></div>
                                            </div>
                                            <div class="col-8 p-0">

                                                <div class="col-12 row m-0 p-0 my-1">
                                                    <div class="col-9 m-0 p-0 input-group">
                                                        <input type="text" disabled="disabled" class="form-control bg-input" value="<?php echo SQ_Classes_ObjController::getClass('SQ_Models_Indexnow')->getKeyUrl() ?>"/>
                                                    </div>
                                                    <div class="col m-0 p-0 input-group" >
                                                        <a href="<?php echo SQ_Classes_ObjController::getClass('SQ_Models_Indexnow')->getKeyUrl() ?>" target="_blank" class="btn btn-block btn-warning btn-sm mx-2 py-2"><i class="dashicons dashicons-external m-1"></i><?php echo esc_html__("Check Key", 'squirrly-seo'); ?></a>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <form  method="POST">
                                        <?php SQ_Classes_Helpers_Tools::setNonce('sq_seosettings_indexnow', 'sq_nonce'); ?>
                                        <input type="hidden" name="action" value="sq_seosettings_indexnow"/>

                                        <div class="col-12 row m-0 p-0">
                                            <div class="col-4 p-0 font-weight-bold">
                                                <?php echo esc_html__("Automatically Submit Post Types", 'squirrly-seo'); ?>:
                                                <div class="small text-black-50 my-1"><?php echo esc_html__("Select post types you want to send to IndexNow automatically.", 'squirrly-seo'); ?></div>
                                            </div>

                                            <div class="col-8 p-0 m-0 form-group">
                                                <select multiple name="indexnow_post_type[]" class="selectpicker form-control bg-input mb-1" data-live-search="true">
                                                    <?php
                                                    $types = get_post_types(array('public' => true));
                                                    foreach ($types as $type) {
                                                        $type_data = get_post_type_object($type);
                                                        echo '<option value="' . esc_attr($type) . '" ' . (in_array($type, (array)SQ_Classes_Helpers_Tools::getOption('indexnow_post_type')) ? 'selected="selected"' : '') . '>' . esc_html($type_data->labels->name) . '</option>';
                                                    } ?>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="col-12 my-3 p-0 mt-5">
                                            <button type="submit" class="btn rounded-0 btn-primary btn-lg m-0 px-5 "><?php echo esc_html__("Save Settings", 'squirrly-seo'); ?></button>
                                        </div>
                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>


                </form>


                <?php SQ_Classes_ObjController::getClass('SQ_Core_BlockKnowledgeBase')->init(); ?>

            </div>
            <div class="sq_col_side bg-white">
                <div class="col-12 m-0 p-0 sq_sticky">
                </div>
            </div>
        </div>

    </div>
</div>
</div>
