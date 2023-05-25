<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>

<div id="sq_blockseoissues">

    <!-- Show the Goals from Goals.php through ajax -->
    <div id="sq_seocheck_content">
        <?php
        $view->report = $view->getNotifications();
        $view->show_view('Goals/Goals'); ?>
    </div>

    <?php if (isset($view->congratulations) && !empty($view->congratulations)) { ?>
        <div class="sq_breadcrumbs"><?php echo SQ_Classes_ObjController::getClass('SQ_Models_Menu')->getBreadcrumbs('sq_progress') ?></div>
        <?php $view->show_view('Goals/Congrats'); ?>
    <?php } ?>
</div>

<div id="sq_loading_modal" tabindex="-1" class="sq_loading_modal modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content bg-white rounded-0">
            <div class="modal-header">
                <div class="row col-12 m-0 p-0">
                    <div class="m-0 p-0 align-middle"><i class="sq_logo sq_logo_30"></i></div>
                    <div class="col-9 m-0 px-2 align-middle text-left">
                        <h5 class="modal-title"><?php echo esc_html__("Website SEO Check", 'squirrly-seo'); ?>
                            <span style="font-weight: bold; font-size: 110%"></span>
                        </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>
            </div>
            <div class="modal-body" style="min-height: 90px;">
                <div class="text-center m-0 p-0">
                    <div class="small m-0 mt-3 p-0 text-black-50"><?php echo esc_html__("Remember that it may take up to 1 minute for a complete SEO check. There is a lot of processing involved.", 'squirrly-seo'); ?></div>
                </div>
                <div class="m-0 my-2 p-0">
                    <div class="text-center text-dark font-weight-bold m-0 my-3 p-0 "><?php echo esc_html__("Checking the website ...", 'squirrly-seo') ?></div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

