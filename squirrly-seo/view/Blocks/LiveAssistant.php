<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>

<div class="sq_box" style="display: none">
    <div class="sq_header sq-border-bottom sq-p-1 sq-m-0">
        <span class="sq_logo"></span>
        <?php echo esc_html__("Live Assistant", 'squirrly-seo'); ?>
        <div class="sq_box_close" title="<?php echo esc_html__("Click to Close Squirrly Live Assistant", 'squirrly-seo'); ?>" style="display: none">x</div>
        <div class="sq_box_minimize" title="<?php echo esc_html__("Click to Minimize Box", 'squirrly-seo'); ?>" style="display: none">_</div>
        <div class="sq_box_maximize dashicons-before dashicons-editor-expand" title="<?php echo esc_html__("Click to Maximize Box", 'squirrly-seo'); ?>" style="display: none"></div>
        <div id="sq_box_briefcase fa-solid fa-briefcase" title="<?php echo esc_html__("Squirrly Briefcase", 'squirrly-seo'); ?>"></div>
    </div>
    <div id="sq_errorloading" class="sq-p-2 sq-py-3 sq-bg-light sq-border-bottom" style="display: none">
        <?php echo sprintf(esc_html__("Connection error with Squirrly Cloud. Please check the connection and %s refresh the page %s", 'squirrly-seo'),'<a href="javascript:void(0);" onclick="jQuery.sq_initialized = false; jQuery.sq_initialize();">','</a>'); ?>
    </div>
    <div id="sq_preloading" class="sq-p-2 sq-py-4 sq-bg-light sq-border-bottom">
        <?php echo esc_html__("Loading", 'squirrly-seo'); ?> ...
    </div>
    <div id="sq_preloading_keyword" class="sq-p-2 sq-py-4 sq-bg-light sq-border-bottom" style="display: none">
        <?php echo esc_html__("Loading the keyword", 'squirrly-seo'); ?> ...
    </div>
    <div id="sq_nokeyword" class="sq-p-2 sq-py-4 sq-bg-light sq-border-bottom sq-text-center"  style="display: none">
        <a href="javascript:void(0);" onclick="jQuery('.sq_box_maximize').trigger('click'); jQuery('#sq_block_tabs').find('.sq_block_tab:first-child').trigger('click');"><?php echo esc_html__("Add a keyword from Briefcase", 'squirrly-seo') ?></a>
    </div>
    <div class="sq_keyword sq-p-2 sq-bg-light sq-border-bottom" style="display: none">
        <span class="sq-font-weight-bold" style="font-size: 1rem"><?php echo esc_html__("SLA Score", 'squirrly-seo') ?>:</span> <span class="sq-font-weight-bold" id="sq_keyword_score" style="font-size: 1rem"></span>
        <button type="button" class="sq_seo_refresh sq-btn sq-btn-sm sq-btn-primary sq-text-white sq-rounded-0 sq-float-right"><?php echo esc_html__("Update", 'squirrly-seo') ?></button>
        <input type="text" id="sq_keyword" name="sq_keyword" class="sq-col-9 sq-m-0 sq-p-0" value="<?php echo SQ_Classes_Helpers_Sanitize::escapeGetValue('keyword', '') ?>" autocomplete="off" />
        <input type="hidden" id="sq_selectit" />
    </div>
    <div id="sq_block_tabs" class="sq-row sq-p-0 sq-mt-2 sq-mb-0 sq-mx-0 sq-border-bottom" style="display: none">
        <div class="sq_block_tab sq-col-3 sq-p-2 sq-m-0 sq-text-primary sq_active small" data-target="#sq_briefcase"><strong><?php echo esc_html__("Step 1", 'squirrly-seo'); ?></strong><br /><?php echo esc_html__("Keywords", 'squirrly-seo'); ?></div>
        <div class="sq_block_tab sq-col-3 sq-p-2 sq-m-0 sq-text-primary small" data-target="#sq_blocksearch"><strong><?php echo esc_html__("Step 2", 'squirrly-seo'); ?></strong><br /><?php echo esc_html__("Enhance", 'squirrly-seo'); ?></div>
        <div class="sq_block_tab sq-col-3 sq-p-2 sq-m-0 sq-text-primary small" data-target="#sq_blockseo"><strong><?php echo esc_html__("Step 3", 'squirrly-seo'); ?></strong><br /><?php echo esc_html__("Analysis", 'squirrly-seo'); ?></div>
        <div class="sq_block_tab sq-col-3 sq-p-2 sq-m-0 sq-text-primary small" data-target="#sq_focuspages"><strong><?php echo esc_html__("Extra", 'squirrly-seo'); ?></strong><br /><?php echo esc_html__("Deep", 'squirrly-seo'); ?></div>
    </div>
    <div id="sq_briefcase" class="sq_block_steps"  style="display: none">
        <?php if(SQ_Classes_Helpers_Tools::getOption('sq_keyword_help')){ ?>
            <div class="sq-text-white sq-p-1" style="background-color: #1C3C50;">
                <div class="sq-row sq-p-2 sq-m-0  small" style="height: 100px;">
                    <i class="fa-solid fa-briefcase sq-p-1 sq-px-2 sq-m-0"></i>
                    <strong><?php echo esc_html__("Squirrly Briefcase", 'squirrly-seo') ?>:</strong>
                    <div class="sq_help sq-col-12 sq-px-4 sq-mx-2"><?php echo esc_html__("We recommend that you select at least 3 Keywords for best results.", 'squirrly-seo') ?></div>
                </div>
            </div>
        <?php }?>
        <div id="sq_briefcase_list" class="sq-col-12 sq-m-0 sq-p-1">
            <input type="text" id="sq_briefcase_keyword" class="sq-col-12 sq-m-0 sq-p-2 sq-rounded-0 sq-border" value="" autocomplete="off" placeholder="<?php echo esc_html__("Search in Briefcase ...", 'squirrly-seo'); ?>" >

            <div id="sq_briefcase_content" class="sq-col-12 sq-m-0 sq-p-0 sq-py-2"></div>
        </div>
        <button type="button" data-target="#sq_blocksearch" class="sq_nextstep sq_step1 sq_button sq-col-12 sq-m-0 sq-p-2"><?php echo esc_html__("Continue", 'squirrly-seo'); ?> ></button>
    </div>
    <div id="sq_blocksearch" class="sq_block_steps" style="display: none">
        <?php if(SQ_Classes_Helpers_Tools::getOption('sq_keyword_help')){ ?>
            <div class="sq-text-white sq-p-1" style="background-color: #1C3C50;">
                <div class="sq-row sq-p-2 sq-m-0  small" style="height: 100px;">
                    <i class="fa-solid fa-messages sq-p-1 sq-px-2 sq-m-0"></i>
                    <strong><?php echo esc_html__("Squirrly Blogging Assistant", 'squirrly-seo') ?>:</strong>
                    <div class="sq_type_img_help sq_type_help sq-col-12 sq-px-4 sq-mx-2"><?php echo esc_html__("The keyword will be automatically be added as the image alt text.", 'squirrly-seo') ?></div>
                    <div class="sq_type_twitter_help sq_type_help sq-col-12 sq-px-4 sq-mx-2" style="display: none"><?php echo esc_html__("Click on “Insert it!” and the incorporated Tweet(s) will add more substance to your article.", 'squirrly-seo') ?></div>
                    <div class="sq_type_wiki_help sq_type_help sq-col-12 sq-px-4 sq-mx-2" style="display: none"><?php echo esc_html__("Browse through Wikipedia articles to get more insight or quick fact-checking on your topic.", 'squirrly-seo') ?></div>
                    <div class="sq_type_blog_help sq_type_help sq-col-12 sq-px-4 sq-mx-2" style="display: none"><?php echo esc_html__("Find topic-related articles written by fellow bloggers or influencers.", 'squirrly-seo') ?></div>
                    <div class="sq_type_local_help sq_type_help sq-col-12 sq-px-4 sq-mx-2" style="display: none"><?php echo esc_html__("Squirrly automatically browses your WordPress for previously written articles you want to cite or insert", 'squirrly-seo') ?></div>
                </div>
            </div>
        <?php }?>

        <div id="sq_types">
            <ul>
                <li id="sq_type_img" class="fa-solid fa-image sq_active" title="<?php echo esc_html__("Images", 'squirrly-seo') ?>"></li>
                <li id="sq_type_twitter" class="fa-brands fa-twitter" title="<?php echo esc_html__("Twitter", 'squirrly-seo') ?>"></li>
                <li id="sq_type_wiki" class="fa-brands fa-wikipedia-w" title="<?php echo esc_html__("Wiki", 'squirrly-seo') ?>"></li>
                <li id="sq_type_blog" class="fa-solid fa-comment" title="<?php echo esc_html__("Blogs", 'squirrly-seo') ?>"></li>
                <li id="sq_type_local" class="fa-solid fa-sticky-note" title="<?php echo esc_html__("My articles", 'squirrly-seo') ?>"></li>
            </ul>
        </div>

        <div class="sq_search"></div>
        <button type="button" data-target="#sq_blockseo" class="sq_nextstep sq_step2 sq_button sq-col-12 sq-m-0 sq-p-2"><?php echo esc_html__("Continue", 'squirrly-seo'); ?> ></button>

        <div id="sq_search_img_filter" style="display:none">
            <input id="sq_search_img_nolicence" type="checkbox" value="1" <?php if (SQ_Classes_Helpers_Tools::getOption('sq_img_licence')) echo 'checked="checked"'; ?> />
            <label id="sq_search_img_nolicence_label" <?php if (SQ_Classes_Helpers_Tools::getOption('sq_img_licence')) echo 'class="checked"'; ?> for="sq_search_img_nolicence"><?php echo esc_html__("Show only Copyright Free images", 'squirrly-seo') ?></label>
        </div>


    </div>
    <div id="sq_blockseo" class="sq_block_steps" style="display: none">
        <?php if(SQ_Classes_Helpers_Tools::getOption('sq_keyword_help')){ ?>
            <div class="sq-text-white sq-p-1" style="background-color: #1C3C50;">
                <div class="sq-row sq-p-2 sq-m-0  small" style="height: 100px;">
                    <i class="fa-solid fa-message sq-p-1 sq-px-2 sq-m-0"></i>
                    <strong><?php echo esc_html__("Squirrly Live Assistant", 'squirrly-seo') ?>:</strong>
                    <div class="sq_help sq-col-12 sq-px-4 sq-mx-2"><?php echo esc_html__("Squirrly automatically checks your article to make sure it has the best SEO chances", 'squirrly-seo') ?></div>
                </div>
            </div>
        <?php }?>
        <div class="sq-row sq-text-right sq-m-0 sq-p-2">

            <div class="sq-flex-grow-1 sq-m-0 sq-p-0 sq-py-1 sq-mr-2">
                <progress class="sq_blockseo_progress sq-col-12 sq-m-0 sq-p-0 sq-my-1" max="100" value="0"></progress>
            </div>
            <button type="button" class="sq_seo_refresh sq-btn sq-btn-sm sq-btn-primary sq-text-white sq-rounded-0"><?php echo esc_html__("Update", 'squirrly-seo') ?></button>

        </div>

        <div class="sq_tasks">
            <ul>
                <?php
                $sla_tasks = SQ_Classes_ObjController::getClass('SQ_Models_Post')->getTasks();
                foreach ($sla_tasks as $category => $row) { ?>
                    <li class="sq_tasks_category" style="display: none"><?php echo esc_html($category) ?></li>
                    <?php foreach ($row as $name => $task) { ?>
                        <li id="<?php echo esc_attr('sq_' . $name) . '' ?>" style="display: none">
                            <?php echo wp_kses_post($task['title']) ?>
                            <?php if(SQ_Classes_Helpers_Tools::getOption('sq_keyword_help')){ ?>
                                <div class="arrow fa-solid fa-info-circle sq-p-1"><p class="sq_help"><?php echo wp_kses_post($task['help']) ?></p></div>
                            <?php }?>
                        </li>
                    <?php }
                } ?>
            </ul>
        </div>

        <button type="button" data-target="#sq_focuspages" class="sq_nextstep sq_step3 sq_button sq-col-12 sq-m-0 sq-p-2"><?php echo esc_html__("Continue", 'squirrly-seo'); ?> ></button>

    </div>
    <div id="sq_focuspages" class="sq_block_steps" style="display: none">
        <?php if(SQ_Classes_Helpers_Tools::getOption('sq_keyword_help')){ ?>
            <div class="sq-text-white sq-p-1" style="background-color: #1C3C50;">
                <div class="sq-row sq-p-2 sq-m-0  small" style="height: 100px;">
                    <i class="fa-solid fa-bullseye-arrow sq-p-1 sq-px-2 sq-m-0"></i>
                    <strong><?php echo esc_html__("Squirrly Focus Pages", 'squirrly-seo') ?>:</strong>
                    <div class="sq_help sq-col-12 sq-px-4 sq-mx-2"><?php echo esc_html__("Focus Pages bring you clear methods to take your content from never found to always found on Google.", 'squirrly-seo') ?></div>
                </div>
            </div>
        <?php }?>

        <div class="sq-row sq-p-4 sq-m-0 small">
            <strong><?php echo esc_html__("Need a deeper analysis of this content?", 'squirrly-seo') ?>:</strong>
            <div class="sq-col-12 sq-p-0 sq-my-2"><?php echo esc_html__("Add this page/article as a focus page and take your content from never found to always found on Google. Rank your pages by influencing the right ranking factors. Turn everything that you see here to Green and you will win.", 'squirrly-seo') ?></div>
        </div>

        <a href="<?php echo esc_url(SQ_Classes_Helpers_Tools::getAdminUrl('sq_focuspages', 'addpage')); ?>" target="_blank" class="sq_step4 sq_button sq-col-12 sq-m-0 sq-p-2"><?php echo esc_html__("Set Focus Pages", 'squirrly-seo'); ?> ></a>

    </div>

</div>
