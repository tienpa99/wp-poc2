<div id="tab3" class="ays-survey-tab-content <?php echo ($ays_tab == 'tab3') ? 'ays-survey-tab-content-active' : ''; ?>">
    <p class="ays-subtitle"><?php echo __('Survey Settings',"survey-maker")?></p>
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays-category">
                <?php echo __('Survey categories', "survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                    echo htmlspecialchars( sprintf(
                        __('Choose the category/categories your survey belongs to. To create a category, go to the %sCategories%s page.',"survey-maker"),
                        '<strong>',
                        '</strong>'
                    ) );
                ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <select id="ays-category" name="<?php echo esc_attr($html_name_prefix); ?>category_ids[]" multiple>
                <option></option>
                <?php
                foreach ($categories as $key => $category) {
                    $selected = in_array( $category['id'], $category_ids ) ? "selected" : "";
                    if( empty( $category_ids ) ){
                        if ( intval( $category['id'] ) == 1 ) {
                            $selected = 'selected';
                        }
                    }
                    echo '<option value="' . esc_attr($category["id"]) . '" ' . esc_attr($selected) . '>' . stripslashes( esc_attr($category['title']) ) . '</option>';
                }
                ?>
            </select>
        </div>
    </div> <!-- Survey Category -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays-status">
                <?php echo __('Survey status', "survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php
                    echo htmlspecialchars( sprintf(
                        __("Decide whether your survey is active or not. If you choose %sDraft%s, the survey won't be shown anywhere on your website (you don't need to remove shortcodes).", "survey-maker"),
                        '<strong>',
                        '</strong>'
                    ) );
                ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <select id="ays-status" name="<?php echo esc_attr($html_name_prefix); ?>status">
                <option></option>
                <option <?php selected( $status, 'published' ); ?> value="published"><?php echo __( "Published", "survey-maker" ); ?></option>
                <option <?php selected( $status, 'draft' ); ?> value="draft"><?php echo __( "Draft", "survey-maker" ); ?></option>
            </select>
        </div>
    </div> <!-- Survey Status -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_show_title">
                <?php echo __('Show survey title',"survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the name of the survey on the front-end.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
                <input type="checkbox" id="ays_survey_show_title" name="ays_survey_show_title" value="on" <?php echo $survey_show_title ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Show survey title -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_show_section_header">
                <?php echo __('Show section header info',"survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox if you want to show the title and description of the section on the front-end.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
                <input type="checkbox" id="ays_survey_show_section_header" name="ays_survey_show_section_header" value="on" <?php echo $survey_show_section_header ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Show section header info -->
    <hr/>    
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_leave_page">
                <?php echo __('Enable confirmation box for leaving the page',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show a popup box whenever the survey taker wants to refresh or leave the page while taking the survey.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_leave_page" name="ays_survey_enable_leave_page" value="on" <?php echo ($survey_enable_leave_page) ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Enable confirmation box for leaving the page -->
    <hr>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_enable_full_screen_mode">
                <?php echo __('Enable full-screen mode',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow the survey to enter full-screen mode by pressing the icon located in the top-right corner of the survey container.',"survey-maker")?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox"
                   class="ays-enable-timer1 ays_toggle_checkbox"
                   id="ays_survey_enable_full_screen_mode"
                   name="ays_survey_enable_full_screen_mode"
                   value="on"
                   <?php echo esc_attr($survey_full_screen);?>/>
        </div>
        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $survey_full_screen == "checked" ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for='ays_survey_full_screen_button_color'>
                        <?php echo __('Full screen button color', "survey-maker"); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the color of the full screen button.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8 ">
                    <input type="text" class="ays-text-input" id='ays_survey_full_screen_button_color' name='ays_survey_full_screen_button_color' data-alpha="true" value="<?php echo esc_attr($survey_full_screen_button_color); ?>"/>
                </div>
            </div>
        </div>
    </div> <!-- Open Full Screen Mode -->
    <hr>
    <div class="form-group row ays_toggle_parent">
        <div class="col-sm-4">
            <label for="ays_survey_enable_progres_bar">
                <?php echo __('Enable live progress bar',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the current state of the user passing the survey. It will be shown at the bottom of the survey container.',"survey-maker")?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-1">
            <input type="checkbox"
                   class="ays-enable-timer1 ays_toggle_checkbox"
                   id="ays_survey_enable_progres_bar"
                   name="ays_survey_enable_progres_bar"
                   value="on"
                   <?php echo esc_attr($survey_enable_progress_bar);?>/>
        </div>
        <div class="col-sm-7 ays_toggle_target ays_divider_left <?php echo $survey_enable_progress_bar == "checked" ? '' : 'display_none_not_important'; ?>">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_hide_section_pagination_text">
                        <?php echo __('Hide the pagination text',"survey-maker")?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to hide the pagination text.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox"
                   class="ays-enable-timer1"
                   id="ays_survey_hide_section_pagination_text"
                   name="ays_survey_hide_section_pagination_text"
                   value="on"
                   <?php echo esc_attr($survey_hide_section_pagination_text); ?>
                   />
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_pagination_positioning">
                        <?php echo __('Pagination items positioning',"survey-maker")?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick the checkbox to change the position of the pagination items.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <select class="ays-text-input ays-text-input-short" name="ays_survey_pagination_positioning">
                        <option <?php echo $survey_pagination_positioning == "none" ? "selected" : ""; ?> value="none"><?php echo __( "None", "survey-maker"); ?></option>
                        <option <?php echo $survey_pagination_positioning == "reverse" ? "selected" : ""; ?> value="reverse"><?php echo __( "Reverse", "survey-maker"); ?></option>
                        <option <?php echo $survey_pagination_positioning == "column" ? "selected" : ""; ?> value="column"><?php echo __( "Column", "survey-maker"); ?></option>
                        <option <?php echo $survey_pagination_positioning == "column_reverse" ? "selected" : ""; ?> value="column_reverse"><?php echo __( "Column Reverse", "survey-maker"); ?></option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_hide_section_bar">
                        <?php echo __('Hide the bar',"survey-maker")?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to hide the bar.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox"
                   class="ays-enable-timer1"
                   id="ays_survey_hide_section_bar"
                   name="ays_survey_hide_section_bar"
                   value="on"
                   <?php echo esc_attr($survey_hide_section_bar); ?>
                   />
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_progress_bar_text">
                        <?php echo __('Progress bar text',"survey-maker")?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the text of the progress bar.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="text" class="ays-text-input ays-text-input-short" id="ays_survey_progress_bar_text" name="ays_survey_progress_bar_text" value="<?php echo esc_attr($survey_progress_bar_text); ?>">
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for='ays_survey_pagination_text_color'>
                        <?php echo __('Progress bar text color', "survey-maker"); ?>
                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the color of the pagination text.',"survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8 ">
                    <input type="text" class="ays-text-input" id='ays_survey_pagination_text_color' name='ays_survey_pagination_text_color' data-alpha="true" value="<?php echo esc_attr($survey_pagination_text_color); ?>"/>
                </div>
            </div> <!-- Progress bar text color' -->
        </div>
    </div> <!-- Live progres bar -->    
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_clear_answer">
                <?php echo __('Enable clear answer button',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow the survey taker to clear the chosen answer.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_clear_answer" name="ays_survey_enable_clear_answer" value="on" <?php echo ($survey_enable_clear_answer) ? 'checked' : '' ?>/>
        </div>
    </div> <!-- Enable clear answer button -->
    <hr/>    
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_previous_button">
                <?php echo __('Enable previous button', "survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Add a previous button that will let the users go back to the previous sections.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_previous_button" name="ays_survey_enable_previous_button" value="on" <?php echo ($survey_enable_previous_button) ? 'checked' : '' ?>/>
        </div>
    </div> <!-- Enable previous button -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_allow_html_in_section_description">
                <?php echo __('Allow HTML in section description',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow implementing HTML coding in section description boxes.', "survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_allow_html_in_section_description" name="ays_survey_allow_html_in_section_description" value="on" <?php echo ($survey_allow_html_in_section_description) ? 'checked' : '' ?>/>
        </div>
    </div> <!-- Allow HTML in section description -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label>
                <?php echo __('Change current survey creation date',"survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Change the survey creation date to your preferred date.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <div class="input-group mb-3">
                <input type="text" class="ays-text-input ays-text-input-short ays-survey-date-create" id="ays_survey_change_creation_date" name="ays_survey_change_creation_date" value="<?php echo esc_attr($date_created); ?>" placeholder="<?php echo current_time( 'mysql' ); ?>">
                <div class="input-group-append">
                    <label for="ays_survey_change_creation_date" class="input-group-text">
                        <span><i class="ays_fa ays_fa_calendar"></i></span>
                    </label>
                </div>
            </div>
        </div>
    </div> <!-- Change current survey creation date -->
    <hr/>
    <p class="ays-subtitle"><?php echo __('Question Settings',"survey-maker")?></p>
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_randomize_questions">
                <?php echo __('Enable randomize questions',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the questions in a random sequence every time the survey takers participate in a survey.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timerl" id="ays_survey_enable_randomize_questions" name="ays_survey_enable_randomize_questions" value="on" <?php echo ($survey_enable_randomize_questions) ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Enable randomize questions -->    
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label>
                <?php echo __('Questions numbering',"survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Assign numbering to each question in ascending sequential order. Choose your preferred type from the list.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <select class="ays-text-input ays-text-input-short" name="ays_survey_show_question_numbering">
                <option <?php echo $survey_auto_numbering_questions == "none" ? "selected" : ""; ?> value="none"><?php echo __( "None", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering_questions == "1."   ? "selected" : ""; ?>   value="1."><?php echo __( "1.", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering_questions == "1)"   ? "selected" : ""; ?>   value="1)"><?php echo __( "1)", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering_questions == "A."   ? "selected" : ""; ?>   value="A."><?php echo __( "A.", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering_questions == "A)"   ? "selected" : ""; ?>   value="A)"><?php echo __( "A)", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering_questions == "a."   ? "selected" : ""; ?>   value="a."><?php echo __( "a.", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering_questions == "a)"   ? "selected" : ""; ?>   value="a)"><?php echo __( "a)", "survey-maker"); ?></option>
            </select>

        </div>
    </div> <!-- Show question numbering -->
    <hr/>    
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_show_questions_count">
                <?php echo __('Show questions count',"survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to show every sections questions count',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox"
                class="ays-enable-timer1"
                id="ays_survey_show_questions_count"
                name="ays_survey_show_questions_count"
                value="on"
                <?php echo esc_attr($survey_show_sections_questions_count);?>/>
        </div>
    </div> <!-- Show sections questions count -->
    <hr/>    
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_required_questions_message">
                <?php echo __('Required questions message',"survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the required message text displayed in case of the required questions.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="text" class="ays-text-input" name="ays_survey_required_questions_message" id="ays_survey_required_questions_message" value="<?php echo __($survey_required_questions_message , "survey-maker"); ?>" placeholder="<?php echo __( 'Required question message' , "survey-maker" ); ?>">
        </div>
    </div> <!-- Show sections questions count -->
    <hr/>
    <p class="ays-subtitle"><?php echo __('Answer Settings',"survey-maker")?></p>
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_randomize_answers">
                <?php echo __('Enable randomize answers',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Show the answers in a random sequence every time the survey takers participate in a survey.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timerl" id="ays_survey_enable_randomize_answers" name="ays_survey_enable_randomize_answers" value="on" <?php echo ($survey_enable_randomize_answers) ? 'checked' : ''; ?>/>
        </div>
    </div> <!-- Enable randomize answers -->
    <hr/>    
    <div class="form-group row">
        <div class="col-sm-4">
            <label>
                <?php echo __('Answers numbering',"survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Assign numbering to each answer in ascending sequential order. Choose your preferred type from the list.',"survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <select class="ays-text-input ays-text-input-short" name="ays_survey_show_answers_numbering">
                <option <?php echo $survey_auto_numbering == "none" ? "selected" : ""; ?> value="none"><?php echo __( "None", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering == "1."   ? "selected" : ""; ?>   value="1."><?php echo __( "1.", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering == "1)"   ? "selected" : ""; ?>   value="1)"><?php echo __( "1)", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering == "A."   ? "selected" : ""; ?>   value="A."><?php echo __( "A.", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering == "A)"   ? "selected" : ""; ?>   value="A)"><?php echo __( "A)", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering == "a."   ? "selected" : ""; ?>   value="a."><?php echo __( "a.", "survey-maker"); ?></option>
                <option <?php echo $survey_auto_numbering == "a)"   ? "selected" : ""; ?>   value="a)"><?php echo __( "a)", "survey-maker"); ?></option>
            </select>

        </div>
    </div> <!-- Show answers numbering -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_allow_html_in_answers">
                <?php echo __('Allow HTML in answers',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Allow implementing HTML coding in answer boxes. This works only for Radio and Checkbox (Multiple) questions.', "survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_allow_html_in_answers" name="ays_survey_allow_html_in_answers" value="on" <?php echo ($survey_allow_html_in_answers) ? 'checked' : '' ?>/>
        </div>
    </div> <!-- Allow HTML in answers -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_enable_info_autofill">
                <?php echo __('Autofill logged-in user data',"survey-maker"); ?>
                <a class="ays_help" data-toggle="tooltip" title="<?php echo __("After enabling this option, logged in user's name and email will be autofilled in Name and Email fields.","survey-maker"); ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_info_autofill" name="ays_survey_enable_info_autofill" <?php echo esc_attr($survey_info_autofill); ?>/>
        </div>
    </div><!-- Autofill logged-in user data -->
    <hr/>
    <div class="form-group row">
        <div class="col-sm-4">
            <label for="ays_survey_main_url">
                <?php echo __('Survey main URL',"survey-maker")?>
                <a class="ays_help" data-toggle="tooltip" data-html="true" title="<?php echo  __('Write the URL link where your survey is located (in Front-end).',"survey-maker");
                ?>">
                    <i class="ays_fa ays_fa_info_circle"></i>
                </a>
            </label>
        </div>
        <div class="col-sm-8">
            <input type="url" id="ays_survey_main_url" name="ays_survey_main_url" class="ays-text-input" value="<?php echo esc_attr($survey_main_url); ?>">
        </div>
    </div> <!-- Survey Main URL -->
    <hr>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_enable_copy_protection">
                        <?php echo __('Enable copy protection',"survey-maker")?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Disable copy functionality in the survey. The user will not be able to copy the text or right-click on it.',"survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="ays-enable-timer1" id="ays_survey_enable_copy_protection"/>
                </div>
            </div>
        </div>
    </div> <!-- Enable copy protection -->
    <hr>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("AGENCY version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_question_text_to_speech">
                        <?php echo __('Enable text to speech', "survey-maker")?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Enable this option to convert question text into spoken words, providing an audio representation of the question for improved accessibility and convenience.', "survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="ays-enable-timer1" />
                </div>
            </div> 
        </div>
    </div> <!-- Questions text to speech -->
    <hr/>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_allow_collecting_logged_in_users_data">
                        <?php echo __('Allow collecting information of logged in users',"survey-maker"); ?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __("Allow collecting information from logged-in users. Email and name of users will be stored in the database. Email options will be work for these users.","survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" value="on" />
                </div>
            </div> 
        </div>
    </div> <!-- Allow collecting information of logged in users -->
    <hr/>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_enable_expand_collapse_question">
                        <?php echo __('Expand/collapse questions',"survey-maker")?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Expand/collapse questions on the front page.',"survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="ays-enable-timer1" value="on" />
                </div>
            </div> 
        </div>
    </div><!-- Expand/collapse questions -->
    <hr>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row ays_toggle_parent">
                <div class="col-sm-4">
                    <label for="ays_survey_enable_chat_mode">
                        <?php echo __('Enable chat mode',"survey-maker")?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Make your survey look like an online chat conversation. It will print each pre-set question instantly after the user answers the previous one.',"survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                    <div>
                        <span style="font-size: 12px;padding: 2px 10px;font-style: italic;">
                            <a href="https://ays-demo.com/conversational-survey/" class="ays-survey-zindex-for-pro" style="color:#524e4e;position:relative;" target="_blank" title="PRO feature"><?php echo __("View Demo ", "survey-maker"); ?></a>
                        </span>
                    </div>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" class="ays_toggle_checkbox" value="on" />
                </div>
            </div> 
        </div>
    </div><!-- Enable chat mode -->
    <hr/>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_terms_and_conditions">
                        <?php echo __('Terms and Conditions',"survey-maker")?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __(' Write your terms and conditions here. It will be displayed in the front end in top of the finish button. Please note that you will be able to click on the finish button only when all the checkboxes are ticked.',"survey-maker")?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                </div>
                <div class="col-sm-1">
                    <input type="checkbox" id="ays_survey_terms_and_conditions" value="on" checked/>
                </div>
                <div class="col-sm-7 ays_divider_left ays_survey_terms_and_conditions_all_inputs_block">
                    <div class="ays_survey_icons appsMaterialWizButtonPapericonbuttonEl">
                        <img class="ays_survey_add_new_textarea" src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/icons/add-circle-outline.svg">
                    </div>
                    <div class="ays_survey_terms_and_conditions_content">
                        <div class = "ays_survey_terms_conditions_edit_block" data-condition-id = "1">
                            <div class="ays_survey_terms_and_conditions_textarea_div">
                                <textarea value="" placeholder="Terms and Conditions..."></textarea>
                            </div>
                            <div class="ays_survey_icons appsMaterialWizButtonPapericonbuttonEl" data-trigger="hover">
                                <img class="ays_survey_remove_textarea" src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/icons/trash.svg">
                            </div>
                        </div>
                    </div>
                    <div class="ays_survey_icons appsMaterialWizButtonPapericonbuttonEl">
                        <img class="ays_survey_add_new_textarea" src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/icons/add-circle-outline.svg">
                    </div>         
                </div> 
            </div> 
        </div>
    </div><!-- Terms and Conditions -->
    <hr/>
    <div class="form-group row" style="margin:0px;">
        <div class="col-sm-12 only_pro">
            <div class="pro_features">
                <div>
                    <p>
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                    <p style="position: absolute;top: 0;">
                        <?php echo __("This feature is available only in ", "survey-maker"); ?>
                        <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" title="PRO feature"><?php echo __("PRO version!!!", "survey-maker"); ?></a>
                    </p>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="ays_survey_enable_schedule">
                        <?php echo __('Schedule the Survey', "survey-maker"); ?>
                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip"
                           title="<?php echo __('Define the period of time when your survey will be active. When the time is due, a message will inform the survey takers about it.', "survey-maker"); ?>">
                            <i class="ays_fa ays_fa_info_circle"></i>
                        </a>
                    </label>
                    <p class="ays_survey_small_hint_text_for_message_variables">
                        <span><?php echo __( "To change your GMT " , "survey-maker" ); ?></span>
                        <a href="<?php echo esc_url($wp_general_settings_url); ?>" target="_blank"><?php echo __( "click here" , "survey-maker" ); ?></a>
                    </p>
                </div>
                <div class="col-sm-1">
                    <input id="ays_survey_enable_schedule" type="checkbox" class="active_date_check">
                </div>
                <div class="col-sm-7 ays_toggle_target ays_divider_left active_date">
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label class="form-check-label" for="ays_survey_schedule_active"> <?php echo __('Start date:', "survey-maker"); ?> </label>
                            <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Set a date since which your survey will be active.', "survey-maker"); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group mb-3">
                                <input type="text" class="ays-text-input ays-text-input-short" id="ays_survey_schedule_active" placeholder="<?php echo current_time( 'mysql' ); ?>">
                                <div class="input-group-append">
                                    <label for="ays_survey_schedule_active" class="input-group-text">
                                        <span><i class="ays_fa ays_fa_calendar"></i></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label class="form-check-label" for="ays_survey_schedule_deactive"> <?php echo __('End date:', "survey-maker"); ?> </label>
                            <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Set a date since which your survey will be inactive.', "survey-maker"); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <div class="input-group mb-3">
                                <input type="text" class="ays-text-input ays-text-input-short" id="ays_survey_schedule_deactive" placeholder="<?php echo current_time( 'mysql' ); ?>">
                                <div class="input-group-append">
                                    <label for="ays_survey_schedule_deactive" class="input-group-text">
                                        <span><i class="ays_fa ays_fa_calendar"></i></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label class="form-check-label" for="ays_survey_schedule_pre_start_message"><?php echo __("Pre-start message:", "survey-maker") ?></label>
                            <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Write a message that will inform the survey takers about the activation of the survey.', "survey-maker"); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <div class="editor">
                                <?php
                                $content   = __( 'The survey will be available soon!' , "survey-maker" );
                                $editor_id = 'ays_survey_schedule_pre_start_message';
                                $settings  = array(
                                    'editor_height'  => '100',
                                    'editor_class'   => 'ays-textarea',
                                    'media_elements' => false
                                );
                                wp_editor($content, $editor_id, $settings);
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-4">
                            <label class="form-check-label" for="ays_survey_schedule_expiration_message"><?php echo __("Expiration message:", "survey-maker") ?></label>
                            <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Set down a message that will inform the survey takers that they cannot take the survey anymore.', "survey-maker"); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <div class="editor">
                                <?php
                                $content   = __( 'This survey has expired!' , "survey-maker" );
                                $editor_id = 'ays_survey_schedule_expiration_message';
                                $settings  = array(
                                    'editor_height'  => '100',
                                    'editor_class'   => 'ays-textarea',
                                    'media_elements' => false
                                );
                                wp_editor($content, $editor_id, $settings);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </div> <!-- Schedule the Survey ( PRO ) -->
</div>
