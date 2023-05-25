<div id="tab6" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab6') ? 'ays-survey-tab-content-active' : ''; ?>">
    <div class="form-group row" style="margin-top: 15px;">
        <div class="col-sm-12 only_pro" style="padding: 10px;">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
                <div class="pro_features_inner_2">
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <p class="ays-subtitle"><?php echo __('Start page settings',"survey-maker")?></p>
            <p><?php echo __("Configure your survey's start page by adding the title, description and styling it the way you want. The start page will be shown to the survey takers before displaying the survey.","survey-maker")?></p>
            <hr/>
            <div style="display: flex;justify-content: center; align-items: center;margin-bottom: 15px;position: relative;" class="ays-survey-zindex-for-pro"><iframe width="560" height="315" src="https://www.youtube.com/embed/NfILS3ndd0U" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
            <hr/>

            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_enable_start_page">
                        <?php echo __('Enable start page',"survey-maker"); ?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Tick the checkbox if you want to add a start page to your survey. After enabling this option, a new tab will appear next to the Settings tab, where you can configure Start Page settings.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="checkbox" value="on" checked/>
                </div>
            </div> <!-- Enable start page -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_start_page_title">
                        <?php echo __('Start page title',"survey-maker"); ?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Give a title to the start page',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <input type="text" class="ays-text-input" />
                </div>
            </div> <!-- Start page title -->
            <hr/>
            <div class="form-group row">
                <div class="col-sm-3">
                    <label for="ays_survey_start_page_description">
                        <?php echo __('Start page description',"survey-maker"); ?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Provide some information about the survey. This will show up on the start page.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-9">
                    <?php
                    $content = "";
                    $editor_id = 'ays_survey_start_page_description';
                    $settings = array('editor_height' => 100, 'textarea_name' => '', 'editor_class' => 'ays-textarea', 'media_elements' => true);
                    wp_editor($content, $editor_id, $settings);
                    ?>
                </div>
            </div> <!-- Start page description -->
            <hr/>
            <p class="ays-subtitle" style="margin-top:0;"><?php echo __('Start page styles',"survey-maker"); ?></p>
            <hr/>
            <div class="form-group row"> <!-- Start page Styles -->
                <div class="col-lg-7 col-sm-12">
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label for='ays_survey_start_page_background_color'>
                                <?php echo __('Background color', "survey-maker"); ?>
                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Specify the background color of the start page.',"survey-maker"); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-7 ays_divider_left">
                            <input type="text" class="ays-text-input" id='ays_survey_start_page_background_color' data-alpha="true" value='#d1d1d1'>
                        </div>
                    </div> <!-- Start page Background Color -->
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label for='ays_survey_start_page_text_color'>
                                <?php echo __('Text color', "survey-maker"); ?>
                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Specify the text color of the start page.',"survey-maker"); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-7 ays_divider_left">
                            <input type="text" class="ays-text-input" id='ays_survey_start_page_text_color' value='#333333'/>
                        </div>
                    </div> <!-- Start page Text Color -->
                    <hr/>
                    <div class="form-group row">
                        <div class="col-sm-5">
                            <label for="ays_survey_start_page_custom_class">
                                <?php echo __('Custom class for start page container',"survey-maker")?>
                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Use your custom HTML class for adding your custom styles to the survey start page container.',"survey-maker"); ?>">
                                    <i class="ays_fa ays_fa_info_circle"></i>
                                </a>
                            </label>
                        </div>
                        <div class="col-sm-7 ays_divider_left">
                            <input type="text" class="ays-text-input" placeholder="myClass myAnotherClass..." >
                        </div>
                    </div> <!-- Custom class for start page container -->
                </div>
                <hr/>
                <div class="col-lg-5 col-sm-12 ays_divider_left" style="position:relative;">
                    <div id="ays_buttons_styles_tab" class="display_none" style="position:sticky;top:50px; margin:auto;">
                        <div class="ays_buttons_div" style="justify-content: center; overflow:hidden;">
                            <input type="button" class="action-button ays-quiz-live-button" style="padding:0;" value="<?php echo __( "Start", "survey-maker" ); ?>">
                        </div>
                    </div>
                </div> <!-- Start page Styles Live -->
            </div> <!-- Start page Styles End -->
        </div>
    </div> 
</div>
