<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<div id="sq_wrap">
    <?php $view->show_view('Blocks/Toolbar'); ?>
    <?php do_action('sq_notices'); ?>
    <div id="sq_content" class="d-flex flex-row bg-white my-0 p-0 m-0">
        <?php
        if (!SQ_Classes_Helpers_Tools::userCan('sq_manage_snippet')) {
            echo '<div class="col-12 alert alert-success text-center m-0 p-3">' . esc_html__("You do not have permission to access this page. You need Squirrly SEO Admin role.", 'squirrly-seo') . '</div>';
            return;
        }
        ?>
        <?php $view->show_view('Blocks/Menu'); ?>
        <div class="d-flex flex-row flex-nowrap flex-grow-1 bg-light m-0 p-0">
            <div class="flex-grow-1 sq_flex m-0 py-0 px-4">
                <?php do_action('sq_form_notices'); ?>

                <div class="col-12 p-0 m-0">
                    <?php $metas = json_decode(wp_json_encode(SQ_Classes_Helpers_Tools::getOption('sq_metas'))); ?>
                    <form method="POST">
                        <?php SQ_Classes_Helpers_Tools::setNonce('sq_seosettings_indexnow_submit', 'sq_nonce'); ?>
                        <input type="hidden" name="action" value="sq_seosettings_indexnow_submit"/>

                        <div class="sq_breadcrumbs my-4"><?php echo SQ_Classes_ObjController::getClass('SQ_Models_Menu')->getBreadcrumbs('sq_indexnow/submit') ?></div>
                        <h3 class="mt-4 card-title">
                            <?php echo esc_html__("Submit URLs to IndexNow", 'squirrly-seo'); ?>
                            <div class="sq_help_question d-inline">
                                <a href="https://howto12.squirrly.co/kb/indexnow/" target="_blank"><i class="fa-solid fa-question-circle"></i></a>
                            </div>
                        </h3>
                        <div class="col-7 small m-0 p-0">
                            <?php echo esc_html__("Manually send URLs to the IndexNow API.", 'squirrly-seo'); ?>
                        </div>

                        <div class="col-12 p-0 m-0 my-5">

                            <div class="col-12 row m-0 p-0 my-5">
                                <div class="col-4 m-0 p-0 font-weight-bold">
                                    <?php echo esc_html__("URLs", 'squirrly-seo'); ?>:
                                    <div class="small text-black-50 my-1 pr-3"><?php echo esc_html__("Insert the URLs you want to send to the IndexNow (one per line, up to 10,000)", 'squirrly-seo'); ?></div>
                                </div>
                                <div class="col-8 p-0">
                                    <textarea class="form-control" name="urls" rows="5" placeholder="<?php echo home_url() ?>"></textarea>
                                </div>
                            </div>

                            <div class="col-12 m-0 p-0 mt-5">
                                <button type="submit" class="btn btn-primary btn-lg m-0 p-0 py-2 px-4 rounded-0"><?php echo esc_html__("Submit URLs", 'squirrly-seo'); ?></button>
                            </div>

                        </div>
                    </form>
                </div>

                <?php $log = get_option( 'sq_indexnow_log', [] ); ?>

                <div class="col-12 m-0 p-0">
                    <div class="col-12 m-0 p-0 my-5">
                        <h3 class="py-0 card-title">
                            <?php echo esc_html__("IndexNow History", 'squirrly-seo'); ?>
                        </h3>
                        <div class="col-7 small m-0 p-0">
                            <?php echo esc_html__("Check the log to see how your URLs were submited.", 'squirrly-seo'); ?>
                        </div>
                        <?php if (!empty($log)){ ?>
                            <div class="col-12 text-right m-0 p-0 my-1">
                                <form method="POST">
                                    <?php SQ_Classes_Helpers_Tools::setNonce('sq_seosettings_indexnow_clear', 'sq_nonce'); ?>
                                    <input type="hidden" name="action" value="sq_seosettings_indexnow_clear"/>
                                    <button type="submit" class="btn btn-light btn-sm"><?php echo esc_html__("Clear log", 'squirrly-seo'); ?></button>
                                </form>
                            </div>
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 50%;" scope="col"><?php echo esc_html__("URL", 'squirrly-seo') ?></th>
                                    <th  style="width: 25%;" scope="col"><?php echo esc_html__("Message", 'squirrly-seo') ?></th>
                                    <th  style="width: 25%;" scope="col"><?php echo esc_html__("Date", 'squirrly-seo') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $log = array_slice($log,-20);
                                $log = array_reverse($log);
                                foreach ($log as $row) {
                                    $timestamp = (int)$row['time'] + ((int)get_option('gmt_offset') * 3600);

                                    ?>
                                    <tr>
                                        <td><?php echo str_replace(' ','<br />',$row['url']) ?> <em><?php echo (!$row['manual'] ? '<i title="'.esc_html__("Submited automatically", 'squirrly-seo').'" class="dashicons dashicons-cloud-saved m-1"></i>' : '') ?></em></td>
                                        <td><?php echo $row['status'] ?> <em>(<?php echo $row['message'] ?>)</em></td>
                                        <td><?php echo date(get_option('date_format') . ' ' . get_option('time_format'), $timestamp) ?></td>
                                    </tr>
                                    <?php
                                }
                                ?>

                                </tbody>
                            </table>
                        <?php }else{?>
                            <table class="table table-striped table-hover mt-3">
                                <thead>
                                <tr>
                                    <th style="width: 50%;"><?php echo esc_html__("URL", 'squirrly-seo') ?></th>
                                    <th scope="col"><?php echo esc_html__("Message", 'squirrly-seo') ?></th>
                                    <th scope="col"><?php echo esc_html__("Date", 'squirrly-seo') ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="3"><?php echo esc_html__("No requests so far", 'squirrly-seo') ?></td>
                                </tbody>
                            </table>
                        <?php }?>
                    </div>
                </div>


                <?php SQ_Classes_ObjController::getClass('SQ_Core_BlockKnowledgeBase')->init(); ?>

            </div>

            <div class="sq_col_side bg-white">
                <div class="col-12 m-0 p-0 sq_sticky">
                </div>
            </div>
        </div>
    </div>
</div>
