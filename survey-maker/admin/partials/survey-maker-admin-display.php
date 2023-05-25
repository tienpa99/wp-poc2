<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/admin/partials
 */

$survey_page_url = sprintf('?page=%s', 'survey-maker');
$add_new_url = sprintf('?page=%s&action=%s', 'survey-maker', 'add');

?>
<div class="wrap">
    <!-- <div class="ays-survey-maker-wrapper" style="position:relative;">
        <h1 class="ays_heart_beat"><?php // echo __(esc_html(get_admin_page_title()),"survey-maker"); ?> <i class="ays_fa ays_fa_heart_o animated"></i></h1>
    </div> -->
    <div class="ays-survey-heart-beat-main-heading ays-survey-heart-beat-main-heading-container">
        <h1 class="ays-survey-maker-wrapper ays_heart_beat">
            <?php echo __(esc_html(get_admin_page_title()),"survey-maker"); ?> <i class="ays_fa ays_fa_heart_o animated"></i>
        </h1>
    </div>
    <div class="ays-survey-faq-main">
        <h2>
            <?php echo __("How to create a simple survey in 3 steps with the help of the", "survey-maker" ) .
            ' <strong>'. __("Survey Maker", "survey-maker" ) .'</strong> '.
            __("plugin.", "survey-maker" ); ?>
            
        </h2>
        <fieldset>
            <div class="ays-survey-ol-container">
                <ol>
                    <li>
                        <?php echo __( "Go to the", "survey-maker" ) . ' <a href="'. $survey_page_url .'" target="_blank">'. __( "Surveys" , "survey-maker" ) .'</a> ' .  __( "page and build your first survey by clicking on the", "survey-maker" ) . ' <a href="'. $add_new_url .'" target="_blank">'. __( "Add New" , "survey-maker" ) .'</a> ' .  __( "button", "survey-maker" ); ?>,
                    </li>
                    <li>
                        <?php echo __( "Fill out the information by adding a title, creating questions and so on.", "survey-maker" ); ?>
                    </li>
                    <li>
                        <?php echo __( "Copy the", "survey-maker" ) . ' <strong>'. __( "shortcode" , "survey-maker" ) .'</strong> ' .  __( "of the survey and paste it into any post.", "survey-maker" ); ?> 
                    </li>
                </ol>
            </div>
            <div class="ays-survey-p-container">
                <p><?php echo __("Congrats! You have already created your first survey." , "survey-maker"); ?></p>
            </div>
        </fieldset>
    </div>
    <br>

    <div class="ays-survey-community-wrap">
        <div class="ays-survey-community-title">
            <h4><?php echo __( "Community", "survey-maker" ); ?></h4>
        </div>
        <div class="ays-survey-community-youtube-video">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/EMN9MlMGlbo" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <div class="ays-survey-community-container">
            <div class="ays-survey-community-item">
                <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="ays-survey-community-item-cover" >
                    <i class="ays-survey-community-item-img ays_fa ays_fa_youtube_play"></i>
                </a>
                <h3 class="ays-survey-community-item-title"><?php echo __( "YouTube community", "survey-maker" ); ?></h3>
                <p class="ays-survey-community-item-desc"><?php echo __("Our YouTube community  guides you to step by step tutorials about our products and not only...", "survey-maker"); ?></p>
                <p class="ays-survey-community-item-desc"></p>
                <div class="ays-survey-community-item-footer">
                    <a href="https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA" target="_blank" class="button"><?php echo __( "Subscribe", "survey-maker" ); ?></a>
                </div>
            </div>
            <div class="ays-survey-community-item">
                <a href="https://wordpress.org/support/plugin/survey-maker/" target="_blank" class="ays-survey-community-item-cover" >
                    <i class="ays-survey-community-item-img ays_fa ays_fa_wordpress"></i>
                </a>
                <h3 class="ays-survey-community-item-title"><?php echo __( "Best Free support", "survey-maker" ); ?></h3>
                <p class="ays-survey-community-item-desc"><?php echo __( "With the Free version, you get a lifetime usage for the plugin, however, you will get new updates and support for only 1 month.", "survey-maker" ); ?></p>
                <p class="ays-survey-community-item-desc"></p>
                <div class="ays-survey-community-item-footer">
                    <a href="https://wordpress.org/support/plugin/survey-maker/" target="_blank" class="button"><?php echo __( "Join", "survey-maker" ); ?></a>
                </div>
            </div>
            <div class="ays-survey-community-item">
                <a href="https://ays-pro.com/contact" target="_blank" class="ays-survey-community-item-cover" >
                    <!-- <img class="ays-survey-community-item-img" src="<?php // echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/logo_final.png"> -->
                    <i class="ays-survey-community-item-img ays_fa ays_fa_users" aria-hidden="true"></i>
                </a>
                <h3 class="ays-survey-community-item-title"><?php echo __( "Premium support", "survey-maker" ); ?></h3>
                <p class="ays-survey-community-item-desc"><?php echo __( "Get 12 months updates and support for the Business package and lifetime updates and support for the Developer package.", "survey-maker" ); ?></p>
                <p class="ays-survey-community-item-desc"></p>
                <div class="ays-survey-community-item-footer">
                    <a href="https://ays-pro.com/contact" target="_blank" class="button"><?php echo __( "Contact", "survey-maker" ); ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="ays-survey-faq-main">
        <div class="ays-survey-asked-questions">
            <h4><?php echo __("FAQs" , "survey-maker"); ?></h4>
            <div class="ays-survey-asked-question">
                <div class="ays-survey-asked-question__header">
                    <div class="ays-survey-asked-question__title">
                        <h4><strong><?php echo __( "Can I create a multi-step survey with the Free version?", "survey-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-survey-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-survey-asked-question__body">
                    <p>
                        <?php 
                            echo sprintf(
                                __( "%s You can! %s With the help of the plugin, you can create unlimited online surveys with multi sections. Quick, easy, and reliable for creating surveys interesting and engaging to take. %s A drastic increase in conversion rate is guaranteed %s", "survey-maker" ),
                                '<strong>',
                                '</strong>',
                                '<em>',
                                '</em>',
                            );
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-survey-asked-question">
                <div class="ays-survey-asked-question__header">
                    <div class="ays-survey-asked-question__title">
                        <h4><strong><?php echo __("How do I make one question per page in Survey Maker?" , "survey-maker"); ?></strong></h4>
                    </div>
                    <div class="ays-survey-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-survey-asked-question__body">
                    <p>
                        <?php 
                            echo sprintf( 
                                __( "To achieve that, you need to divide your survey into separate sections and write one question per section. If you want to drive more traffic and use it as a %slead generator%s, then the plugin is %sperfect for your needs.%s It %sdoesn't require any coding to build your surveys,%s yet it is %seasy to customize%s.", "survey-maker" ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            );
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-survey-asked-question">
                <div class="ays-survey-asked-question__header">
                    <div class="ays-survey-asked-question__title">
                        <h4><strong><?php echo __( "Will I lose the data after upgrading to the Pro version?", "survey-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-survey-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-survey-asked-question__body">
                    <p>
                        <?php 
                            echo sprintf( 
                                __( " %s Nope, you will not! %s All the data(surveys, settings, and submissions) of the plugin will remain unchanged even after switching to the Pro version. You don't need to redo what you have already created with the free version. %s For further detailed instructions, please check out the upgrade guide of the plugin. %s" , "survey-maker" ),
                                '<strong>',
                                '</strong>',
                                '<em>',
                                '</em>',
                            );
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-survey-asked-question">
                <div class="ays-survey-asked-question__header">
                    <div class="ays-survey-asked-question__title">
                        <h4><strong><?php echo __( "Where can I find the summary report of my submissions?", "survey-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-survey-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-survey-asked-question__body">
                    <p>
                        <?php 
                            echo sprintf( 
                                __( "To do that, please go to the %sSubmissions%s page of the plugin. There you will find plenty of analysis tools. Want to %sanalyze data from a survey%s? The plugin is %sjust what you needed%s. Collect user feedback and interpret them via reports, charts, and statistics.", "survey-maker" ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            );                            
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-survey-asked-question">
                <div class="ays-survey-asked-question__header">
                    <div class="ays-survey-asked-question__title">
                        <h4><strong><?php echo __( "How to transfer all surveys from one site to another?", "survey-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-survey-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-survey-asked-question__body">
                    <p>
                        <?php 
                            echo sprintf( 
                                __( "Looking for an %samazing and flexible plugin%s? You are in a right place! We have done our best to make the time of our customers more manageable. You can import and export your already created surveys from one website to another via JSON format. %sGreat plugin, right?%s", "survey-maker" ),
                                '<strong>',
                                '</strong>',
                                '<em>',
                                '</em>'
                            );                            
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-survey-asked-question">
                <div class="ays-survey-asked-question__header">
                    <div class="ays-survey-asked-question__title">
                        <h4><strong><?php echo __( "Can I get support for the Survey Maker Free version?" , "survey-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-survey-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-survey-asked-question__body">
                    <p>
                        <?php
                            echo __( "We happily support our community. Our Support Care specialists are always ready to help you to use the product more efficiently." , "survey-maker" );
                            echo " ";
                            echo sprintf( 
                                __( "Though the plugin is %seasy to set up and use%s and has a %ssimple interface%s, please feel free to reach out to us anytime when you have any concerns.", "survey-maker" ),
                                '<strong>',
                                '</strong>',
                                '<strong>',
                                '</strong>'
                            );
                            echo " ";
                            echo __( "The team may respond to your questions within 24 hours." , "survey-maker" );
                            echo " ";
                            echo sprintf( 
                                __( "For questions concerning the Free plugin please post it on the %sWordPress Support Forum%s, otherwise(pre-sale question/pro support), please contact us via %sthis form%s. %sExcellent service is guaranteed!%s", "survey-maker" ),
                                '<a href="https://wordpress.org/support/plugin/survey-maker/" target="_blank">',
                                '</a>',
                                '<a href="https://ays-pro.com/contact" target="_blank">',
                                '</a>',
                                '<em>',
                                '</em>'
                            );
                        ?>
                    </p>
                </div>
            </div>
            <div class="ays-survey-asked-question">
                <div class="ays-survey-asked-question__header">
                    <div class="ays-survey-asked-question__title">
                        <h4><strong><?php echo __( "How to add unlimited number of questions?" , "survey-maker" ); ?></strong></h4>
                    </div>
                    <div class="ays-survey-asked-question__arrow"><i class="fa fa-chevron-down"></i></div>
                </div>
                <div class="ays-survey-asked-question__body">
                    <p>
                        <?php
                            echo sprintf( 
                                __( "You must know that our plugin has no limits
                                Survey Maker offers %sunlimited%s number of sections, questions and answers. However, if you came across to such problem, it is connected with your server. To solve that problem you must access to your cPanel, find your PHP values and enhance the number, and in case you are unable to do that yourself then contact your Hosting provider, so that they do the mentioned for you.", "survey-maker" ),
                                '<strong>',
                                '</strong>'
                            );   
                            echo "<br>";
                            echo "<br>";
                            echo "max_input_vars 10000";
                            echo "<br>";
                            echo "max_execution_time 600";
                            echo "<br>";
                            echo "max_input_time 600";
                            echo "<br>";
                            echo "post_max_size 256M";
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <p class="ays-survey-faq-footer">
            <?php echo __( "For more advanced needs, please take a look at our" , "survey-maker" ); ?> 
            <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank"><?php echo __( "Survey Maker plugin User Manual." , "survey-maker" ); ?></a>
            <br>
            <?php echo __( "If none of these guides help you, ask your question by contacting our" , "survey-maker" ); ?>
            <a href="https://ays-pro.com/contact" target="_blank"><?php echo __( "support specialists." , "survey-maker" ); ?></a> 
            <?php echo __( "and get a reply within a day." , "survey-maker" ); ?>
        </p>
    </div>
</div>
<script>
    var acc = document.getElementsByClassName("ays-survey-asked-question__header");
    var i;
    for (i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function() {
        
        var panel = this.nextElementSibling;
        
        
        if (panel.style.maxHeight) {
          panel.style.maxHeight = null;
          this.children[1].children[0].style.transform="rotate(0deg)";
        } else {
          panel.style.maxHeight = panel.scrollHeight + "px";
          this.children[1].children[0].style.transform="rotate(180deg)";
        } 
      });
    }
</script>
