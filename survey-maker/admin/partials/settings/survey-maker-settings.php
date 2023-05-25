<?php
    $actions = $this->settings_obj;

    if( isset( $_REQUEST['ays_submit'] ) ){
        $actions->store_data();
    }
    if(isset($_GET['ays_survey_tab'])){
        $ays_survey_tab = sanitize_text_field( $_GET['ays_survey_tab'] );
    }else{
        $ays_survey_tab = 'tab1';
    }

    $loader_iamge = '<span class="display_none ays_survey_loader_box"><img src="'. SURVEY_MAKER_ADMIN_URL .'/images/loaders/loading.gif"></span>';
    
    $db_data = $actions->get_db_data();
    $options = ($actions->ays_get_setting('options') === false) ? array() : json_decode($actions->ays_get_setting('options'), true);

    global $wp_roles;
    $ays_users_roles = $wp_roles->role_names;
    $user_roles = $actions->ays_get_setting('user_roles');
    if( $user_roles === null || $user_roles === false ){
        $user_roles = array();
    }else{
        $user_roles = json_decode( $user_roles );
    }

    $survey_types = array(
        // Free types
        "radio" => array(
            "type_name" => __("Radio", "survey-maker") ,
            "type_version" => "free"
        ),
        "checkbox" => array(
            "type_name" => __("Checkbox (Multi)", "survey-maker") , 
            "type_version" => "free"
        ),
        "select" => array(
            "type_name" => __("Dropdown", "survey-maker") , 
            "type_version" => "free"
        ),
        "text" => array(
            "type_name" => __("Paragraph", "survey-maker") , 
            "type_version" => "free"
        ),
        "short_text" => array(
            "type_name" => __("Short Text", "survey-maker") , 
            "type_version" => "free"
        ),
        "number" => array(
            "type_name" => __("Number", "survey-maker") , 
            "type_version" => "free"
        ),
        "yesorno" => array(
            "type_name" => __("Yes or No", "survey-maker") , 
            "type_version" => "free"
        ),
        "email" => array(
            "type_name" => __("Email", "survey-maker") , 
            "type_version" => "free"
        ),
        "name" => array(
            "type_name" => __("Name", "survey-maker") , 
            "type_version" => "free"
        ),        
        // Pro types        
        "matrix_scale" => array(
            "type_name" => __("Matrix Scale (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "matrix_scale_checkbox" => array(
            "type_name" => __("Matrix Scale Checkbox (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "star_list"    => array(
            "type_name" => __("Star List (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "slider_list"  => array(
            "type_name" => __("Slider List (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "linear_scale" => array(
            "type_name" => __("Linear Scale (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "star"         => array(
            "type_name" => __("Star Rating (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "slider"       => array(
            "type_name" => __("Slider (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "date"         => array(
            "type_name" => __("Date (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "time"         => array(
            "type_name" => __("Time (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "date_time"    => array(
            "type_name" => __("Date and Time (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "uplaod"       => array(
            "type_name" => __("Upload (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "phone"        => array(
            "type_name" => __("Phone (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "hidden"       => array(
            "type_name" => __("Hidden (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
        "html"         => array(
            "type_name" => __("HTML (Pro)", "survey-maker") , 
            "type_version" => "pro"
        ),  
    );

    // Survey question default type
    $options['survey_default_type'] = !isset($options['survey_default_type']) ? 'radio' : $options['survey_default_type'];
    $survey_default_type = (isset($options['survey_default_type']) && $options['survey_default_type'] != '') ? esc_attr($options['survey_default_type']) : 'radio';
    $survey_answer_default_count = (isset($options['survey_answer_default_count']) && $options['survey_answer_default_count'] != '')? $options['survey_answer_default_count'] : 1;

    // Do not store IP addresses
    $options['survey_disable_user_ip'] = (isset($options['survey_disable_user_ip']) && $options['survey_disable_user_ip'] == 'on') ? $options['survey_disable_user_ip'] : 'off' ;
    $survey_disable_user_ip = (isset($options['survey_disable_user_ip']) && $options['survey_disable_user_ip'] == 'on') ? true : false;

    // Do not store user names
    $options['survey_disable_user_name'] = (isset($options['survey_disable_user_name']) && $options['survey_disable_user_name'] != '') ? $options['survey_disable_user_name'] : 'off' ;
    $survey_disable_user_name = (isset($options['survey_disable_user_name']) && $options['survey_disable_user_name'] == 'on') ? true : false;

    // Do not store user emails
    $options['survey_disable_user_email'] = (isset($options['survey_disable_user_email']) && $options['survey_disable_user_email'] != '') ? $options['survey_disable_user_email'] : 'off' ;
    $survey_disable_user_email = (isset($options['survey_disable_user_email']) && $options['survey_disable_user_email'] == 'on') ? true : false;

    $survey_submmission_title_length = (isset($options['survey_submissions_title_length']) && $options['survey_submissions_title_length'] != '') ? intval($options['survey_submissions_title_length']) : 5;
    $survey_title_length = (isset($options['survey_title_length']) && $options['survey_title_length'] != '') ? intval($options['survey_title_length']) : 5;

    // Animation Top
    $survey_animation_top = (isset($options['survey_animation_top']) && $options['survey_animation_top'] != '') ? absint(intval($options['survey_animation_top'])) : 200;
    $options['survey_enable_animation_top'] = isset($options['survey_enable_animation_top']) ? $options['survey_enable_animation_top'] : 'on';
    $survey_enable_animation_top = (isset($options['survey_enable_animation_top']) && $options['survey_enable_animation_top'] == "on") ? true : false;

    // Textarea height (public)
    $survey_textarea_height = (isset($options['survey_textarea_height']) && $options['survey_textarea_height'] != '' && $options['survey_textarea_height'] != 0) ? absint( sanitize_text_field($options['survey_textarea_height']) ) : 100;

    // WP Editor height
    $survey_wp_editor_height = (isset($options['survey_wp_editor_height']) && $options['survey_wp_editor_height'] != '' && $options['survey_wp_editor_height'] != 0) ? absint( esc_attr($options['survey_wp_editor_height']) ) : 100;
    
    // Make question required
    $options['survey_make_questions_required'] = (isset($options['survey_make_questions_required']) && $options['survey_make_questions_required'] != '') ? $options['survey_make_questions_required'] : 'off' ;
    $survey_make_questions_required = (isset($options['survey_make_questions_required']) && $options['survey_make_questions_required'] == 'on') ? true : false;

    // Lazy loading for images
    $options['survey_lazy_loading_for_images'] = (isset($options['survey_lazy_loading_for_images']) && $options['survey_lazy_loading_for_images'] != '') ? $options['survey_lazy_loading_for_images'] : 'off' ;
    $survey_lazy_loading_for_images = (isset($options['survey_lazy_loading_for_images']) && $options['survey_lazy_loading_for_images'] == 'on') ? true : false;

    //Summary Email
    $ays_survey_admin_email_sessions = array(
        'hourly'     => __('Hourly', "survey-maker"),
        'daily'      => __('Daily', "survey-maker"),
        'twicedaily' => __('Twicedaily', "survey-maker"),
        'weekly'     => __('Weekly', "survey-maker"),
    );

    $buttons_texts_res      = ($actions->ays_get_setting('buttons_texts') === false) ? json_encode(array()) : $actions->ays_get_setting('buttons_texts');
    $buttons_texts          = json_decode($buttons_texts_res, true);

    $survey_next_button     = (isset($buttons_texts['next_button']) && $buttons_texts['next_button'] != '') ? stripslashes( esc_attr($buttons_texts['next_button']) ) : 'Next';
    $survey_previous_button = (isset($buttons_texts['prev_button']) && $buttons_texts['prev_button'] != '') ? stripslashes( esc_attr($buttons_texts['prev_button']) ) : 'Prev';
    $survey_restart_button  = (isset($buttons_texts['restart_button']) && $buttons_texts['restart_button'] != '') ? stripslashes( esc_attr($buttons_texts['restart_button']) ) : 'Restart';
    $survey_clear_button    = (isset($buttons_texts['clear_button']) && $buttons_texts['clear_button'] != '') ? stripslashes( esc_attr($buttons_texts['clear_button']) ) : 'Clear selection';
    $survey_finish_button   = (isset($buttons_texts['finish_button']) && $buttons_texts['finish_button'] != '') ? stripslashes( esc_attr($buttons_texts['finish_button']) ) : 'Finish';
    $survey_exit_button     = (isset($buttons_texts['exit_button']) && $buttons_texts['exit_button'] != '') ? stripslashes( esc_attr($buttons_texts['exit_button']) ) : 'Exit';
    $survey_login_button    = (isset($buttons_texts['login_button']) && $buttons_texts['login_button'] != '') ? stripslashes( esc_attr($buttons_texts['login_button']) ) : 'Log In';

    $default_all_submissions_columns = array(
        'User Name',
        'Survey Name',
        'Submission Date',
    );

    $ays_survey_user_history_columns_order = array(
        'Survey Name',
        'Submission Date',
    );

    // Add message varibales here
    $message_variables = array(        
        'general_message_variables' => array(
            'current_date'    => __('The date of the submission survey.' , "survey-maker"),
            'current_time'    => __('The time of the submission survey.' , "survey-maker"),
            'unique_code'     => __('Use to identify the uniqueness of each attempt.' , "survey-maker"),
        ),
        'user_message_variables' => array(
            'user_name'  => __('The name the user entered into the survey form. It will work only if the name field exists in the form.' , "survey-maker"),
            'user_email' => __('The E-mail the user entered into the survey form. It will work only if the email field exists in the form.' , "survey-maker"),
            'user_wordpress_email' => __('The E-mail that was filled in their WordPress site during registration.' , "survey-maker"),
            'user_id' => __('The ID of the current user.' , "survey-maker"),
            'users_count'     => __('The number of the passed users count of the given survey.' , "survey-maker"),
            'users_first_name' => __('The user\'s first name that was filled in their WordPress site during registration.' , "survey-maker"),
            'users_last_name'  => __('The user\'s last name that was filled in their WordPress site during registration.' , "survey-maker"),
            'users_nick_name'  => __('The user\'s nick name that was filled in their WordPress site during registration.' , "survey-maker"),
            'user_wordpress_roles'  => __('The user\'s role(s) when logged-in. In case the user is not logged-in, the field will be empty.' , "survey-maker"),
            'users_display_name'    => __('The user\'s display name that was filled in their WordPress site during registration.' , "survey-maker"),
            'users_ip_address'      => __('The user\'s ip address.' , "survey-maker"),
            'admin_email'      => __('Shows the admin\'s email that was filled in their WordPress profile.' , "survey-maker"),
        ),
        'survey_message_variables' => array(
            'survey_title' => __('The title of the survey.' , "survey-maker"),
            'survey_id'    => __('The ID of the survey.' , "survey-maker"),
            'current_survey_author' => __('It will show the author of the current survey.' , "survey-maker"),
            'current_survey_author_email' => __('It will show the author email of the current survey.' , "survey-maker"),
            'current_survey_page_link'    => __('Prints the webpage link where the current survey is posted.' , "survey-maker"),
            'questions_count' => __('The number of the questions of the given survey.' , "survey-maker"),
            'sections_count'  => __('The number of the sections of the given survey.' , "survey-maker"),
            'submission_count' => __('Shows the submission count of a particular survey.' , "survey-maker"),
            'creation_date'         => __('The creation date of the survey.' , "survey-maker"),
        )
    );
?>
<div class="wrap" style="position:relative;">
    <div class="container-fluid">
        <div class="ays-survey-heading-box">
            <div class="ays-survey-wordpress-user-manual-box">
                <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank" style="text-decoration: none;font-size: 13px;">
                    <i class="ays_fa ays_fa_file_text" ></i> 
                    <span style="margin-left: 3px;text-decoration: underline;">View Documentation</span>
                </a>
            </div>
        </div>
        <form method="post" id="ays-survey-settings-form">
            <input type="hidden" name="ays_survey_tab" value="<?php echo esc_attr($ays_survey_tab); ?>">
            <h1 class="wp-heading-inline">
            <?php
                echo __('General Settings',"survey-maker");
            ?>
            </h1>
            <?php
                if( isset( $_REQUEST['status'] ) ){
                    $actions->survey_settings_notices( sanitize_text_field( $_REQUEST['status'] ) );
                }
            ?>
            <hr/>
            <div class="ays-settings-wrapper">
                <div class="ays-settings-wrapper-tabs">
                    <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                        <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($ays_survey_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("General", "survey-maker");?>
                        </a>
                        <a href="#tab2" data-tab="tab2" class="nav-tab <?php echo ($ays_survey_tab == 'tab2') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Integrations", "survey-maker");?>
                        </a>
                        <a href="#tab3" data-tab="tab3" class="nav-tab <?php echo ($ays_survey_tab == 'tab3') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Message Variables", "survey-maker");?>
                        </a>
                        <a href="#tab4" data-tab="tab4" class="nav-tab <?php echo ($ays_survey_tab == 'tab4') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Buttons Texts", "survey-maker");?>
                        </a>
                        <a href="#tab5" data-tab="tab5" class="nav-tab <?php echo ($ays_survey_tab == 'tab5') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("Shortcodes", "survey-maker");?>
                        </a>
                    </div>
                </div>
                <div class="ays-survey-tabs-wrapper">
                    <div id="tab1" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'tab1') ? 'ays-survey-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('General Settings',"survey-maker")?></p>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_question"></i></strong>
                                <h5><?php echo __('Default parameters for Survey',"survey-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_default_type">
                                        <?php echo __( "Surveys default question type", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can choose default question type which will be selected in the Add new survey page.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <select id="ays_survey_default_type" name="ays_survey_default_type">
                                        <option></option>
                                        <?php
                                            foreach($survey_types as $survey_type => $survey_label):
                                            $selected = $survey_default_type == $survey_type ? "selected" : "";
                                        ?>
                                        <?php if($survey_label['type_version'] == 'free'):?>
                                            <option value="<?php echo esc_attr($survey_type); ?>" <?php echo esc_attr($selected); ?> ><?php echo esc_html($survey_label['type_name']); ?></option>
                                        <?php else:?>
                                            <option value="<?php echo esc_attr($survey_type); ?>" class="ays-survey-choose-question-default-type-pro" disabled><?php echo esc_html($survey_label['type_name']); ?></option>
                                        <?php endif;?>
                                        <?php
                                            endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_answer_default_count">
                                        <?php echo __( "Answer default count", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('You can write the default answer count which will be showing in the Add new question page (this will work only with radio, checkbox, and dropdown types).',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_answer_default_count" id="ays_survey_answer_default_count" class="ays-text-input" value="<?php echo esc_attr($survey_answer_default_count); ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_textarea_height">
                                        <?php echo __( "Textarea height (public)", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Set the height of the textarea by entering a numeric value. It applies to Paragraph question type textarea.',"survey-maker"); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_textarea_height" id="ays_survey_textarea_height" class="ays-text-input" value="<?php echo esc_attr($survey_textarea_height); ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_wp_editor_height">
                                        <?php echo __( "WP Editor height", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Give the default value to the height of the WP Editor. It will apply to all WP Editors within the plugin on the dashboard.',"survey-maker"); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_wp_editor_height" id="ays_survey_wp_editor_height" class="ays-text-input" value="<?php echo esc_attr($survey_wp_editor_height); ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_make_questions_required">
                                        <?php echo __( "Make questions required", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('By enabling this option, the questions of newly created surveys will be required by default. Note: The changes will not be applied to the already created surveys.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_make_questions_required" name="ays_survey_make_questions_required" value="on" <?php echo $survey_make_questions_required ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_lazy_loading_for_images">
                                        <?php echo __( "Lazy loading for images", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Tick this option to delay the loading of images of questions and answers to improve the performance of your plugin.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_lazy_loading_for_images" name="ays_survey_lazy_loading_for_images" value="on" <?php echo $survey_lazy_loading_for_images ? 'checked' : ''; ?> />
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-server"></i></strong>
                                <h5><?php echo __('Users IP addresses',"survey-maker")?></h5>
                            </legend>
                            <blockquote class="ays_warning">
                                <p style="margin:0;"><?php echo __( "If this option is enabled then the 'Limitation by IP' option will not work!", "survey-maker" ); ?></p>
                            </blockquote>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_disable_user_ip">
                                        <?php echo __( "Do not store IP addresses", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, IP address of the users will not be stored in database. Note: If this option is enabled, then the `Limits user by IP` option will not work.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_disable_user_ip" name="ays_survey_disable_user_ip" value="on" <?php echo $survey_disable_user_ip ? 'checked' : ''; ?> />
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-user-secret"></i></strong>
                                <h5><?php echo __('Anonymity Survey',"survey-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_disable_user_name">
                                        <?php echo __( "Do not store User Names", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, User Names will not be stored in database.',"survey-maker"); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_disable_user_name" name="ays_survey_disable_user_name" value="on" <?php echo $survey_disable_user_name ? 'checked' : ''; ?> />
                                </div>
                            </div>
                            <hr/>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_disable_user_email">
                                        <?php echo __( "Do not store User Emails", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option, User Emails will not be stored in database.',"survey-maker"); ?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" class="ays-checkbox-input" id="ays_survey_disable_user_email" name="ays_survey_disable_user_email" value="on" <?php echo $survey_disable_user_email ? 'checked' : ''; ?> />
                                </div>
                            </div>
                        </fieldset> <!-- Anonymity Survey -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-align-left"></i></strong>
                                <h5><?php echo __('Excerpt words count in list tables',"survey-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_title_length">
                                        <?php echo __( "Survey list table", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the surveys to be shown in the Surveys List Table by putting your preferred count of words in the following field. (For example: if you put 10,  you will see the first 10 words of each survey in the Surveys page of your dashboard.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_title_length" id="ays_survey_title_length" class="ays-text-input" value="<?php echo esc_attr($survey_title_length); ?>">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_submissions_title_length">
                                        <?php echo __( "Submissions list table", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Determine the length of the submissions to be shown in the Submissions List Table by putting your preferred count of words in the following field. (For example: if you put 10,you will see the first 10 words of each submissions in the Submissions page of your dashboard.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_submissions_title_length" id="ays_survey_submissions_title_length" class="ays-text-input" value="<?php echo esc_attr($survey_submmission_title_length); ?>">
                                </div>
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="fa fa-code"></i></strong>
                                <h5><?php echo __('Animation Top',"survey-maker")?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_enable_animation_top">
                                        <?php echo __( "Enable animation", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Enable animation of the scroll offset of the survey container. It works when the survey container is visible on the screen partly and the user starts the survey and moves from one question to another.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="checkbox" name="ays_survey_enable_animation_top" id="ays_survey_enable_animation_top" value="on" <?php echo $survey_enable_animation_top ? 'checked' : ''; ?>>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="ays_survey_animation_top">
                                        <?php echo __( "Scroll offset(px)", "survey-maker" ); ?>
                                        <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Define the scroll offset of the survey container after the animation starts. It works when the survey container is visible on the screen partly and the user starts the survey and moves from one question to another.',"survey-maker")?>">
                                            <i class="ays_fa ays_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" name="ays_survey_animation_top" id="ays_survey_animation_top" class="ays-text-input" value="<?php echo esc_attr($survey_animation_top); ?>">
                                </div>
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_globe"></i></strong>
                                <h5><?php echo __('Who will have permission to Survey menu',"survey-maker")?></h5>
                            </legend>
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
                                            <label for="ays_user_roles">
                                                <?php echo __( "Select user role", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Ability to manage Survey Maker plugin only for selected user roles.',"survey-maker")?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select name="ays_user_roles[]" id="ays_user_roles" multiple="multiple">
                                                <?php
                                                    foreach($ays_users_roles as $role => $role_name){
                                                        $selected = in_array($role, $user_roles) ? 'selected' : '';
                                                        echo "<option ".esc_attr($selected)." value='".esc_attr($role)."'>".esc_attr($role_name)."</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <blockquote>
                                        <?php echo __( "Ability to manage Survey Maker plugin only for selected user roles.", "survey-maker" ); ?>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset>
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="ays_fa ays_fa_globe"></i></strong>
                                <h5><?php echo __('Block Users by IP addresses',"survey-maker")?></h5>
                            </legend>
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
                                            <label for="ays_survey_block_by_user_ips">
                                                <?php echo __( "Block Users by IP addresses", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After enabling this option you will be able to block particular User IPs.', "survey-maker")?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays_toggle_checkbox" checked/>
                                        </div>
                                    </div>
                                    <div class="form-group ays_toggle_target">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="ays_survey_users_ips_that_will_blocked">
                                                    <?php echo __( "Block User IP's", "survey-maker" ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __("After adding User IP's, you can restrict the access to the survey. The users with particular IP addresses will not be able to pass the survey. You will be able to add as many User IP's as you may need.","survey-maker"); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="ays-poll-email-to-admins">                                        
                                                    <input type="text" placeholder="User IPs" style="width:100%" multiple>                                          
                                                </div>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- summary email start -->
                        <hr/>
                        <fieldset class="ays_toggle_parent">
                            <legend>
                                <img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/icons/send-mail-50x50.png" alt="">
                                <h5><?php echo __("Send automatic email reporting to admin(s)","survey-maker")?></h5>
                            </legend>
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
                                            <label for="ays_survey_email_to_admins">
                                                <?php echo __( "Send automatic email reporting per session", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Set up automatic email reporting to inform yourself or anyone else, about the submissions of surveys. It will indicate the times of submissions all surveys will have at that given moment,  will send a table which will include the names of all surveys and the number of submissions each one will have and will show the statistics of the growth/decline of the submissions in percentage',"survey-maker"); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="ays-poll-email-to-admins">
                                                <input type="checkbox" class="ays_toggle_checkbox" id="ays_survey_email_to_admins" value="on">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ays_toggle_target" style='display:block'>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="ays_survey_admin_email_sessions">
                                                    <?php echo __( "Session period", "survey-maker" ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Specify the time for one session and the provided email(s) will receive an automatic email notification once during the period.',"survey-maker"); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="ays-poll-email-to-admins">
                                                    <select id="ays_survey_admin_email_session">
                                                        <?php
                                                            foreach($ays_survey_admin_email_sessions as $key => $admin_email_session):
                                                                $selected = '';
                                                        ?>
                                                            <option value="<?php echo esc_attr($key);?>" <?php echo esc_attr($selected);?>>
                                                                <?php echo esc_html($admin_email_session); ?>
                                                            </option>
                                                        <?php
                                                            endforeach;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="ays_survey_admins_emails">
                                                    <?php echo __( "Email addresses", "survey-maker" ); ?>
                                                    <a class="ays_help" data-toggle="tooltip" title="<?php echo __("Provide emails to which you need to send the survey's reports. Insert emails comma separated.","survey-maker"); ?>">
                                                        <i class="ays_fa ays_fa_info_circle"></i>
                                                    </a>
                                                </label>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="ays-poll-email-to-admins">
                                                    <input type="email" class="" id="ays_survey_admins_emails" value="" placeholder="Admins email" style="width:100%" multiple>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        <!-- summary email end -->
                        <hr>
                        <fieldset class="ays_toggle_parent">
                            <legend>
                            <strong style="font-size:30px;"><i class="fa fa-th-list"></i></strong>
                                <h5><?php echo __("Submissions settings","survey-maker")?></h5>
                            </legend>
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
                                            <label>
                                                <?php echo __( "Matrix scale results", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('The results can be displayed by Votes and by Percentage. Choosing by Votes, the system will display the results based on votes. To see the percentage you need to hover over the vote numbers. You will be able to do the same in the case of choosing by Percentage as well.',"survey-maker"); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label class="checkbox_ays form-check form-check-inline" for="ays_survey_matrix_scale_show_result_type_votes">
                                                    <input type="radio" checked>
                                                    <?php echo __('By Votes', "survey-maker") ?>
                                                </label>
                                                <label class="checkbox_ays form-check form-check-inline" for="ays_survey_matrix_scale_show_result_type_percentage">
                                                    <input type="radio" >
                                                    <?php echo __('By Percentage', "survey-maker") ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr/>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Show results by", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Specify how the the charts will be displayed. Note that ascending and descending orderings can be used with Checkbox, Linear/Star scales, Star list, Slider List question types only.',"survey-maker"); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="form-group">
                                                <label class="checkbox_ays form-check form-check-inline" for="ays_survey_show_submissions_order_type_defalut">
                                                    <input type="radio" id="ays_survey_show_submissions_order_type_defalut" value="by_default" checked>
                                                    <?php echo __('Default', "survey-maker") ?>
                                                </label>
                                                <label class="checkbox_ays form-check form-check-inline" for="ays_survey_show_submissions_order_type_ascending">
                                                    <input type="radio" id="ays_survey_show_submissions_order_type_ascending" value='by_asc' >
                                                    <?php echo __('Ascending', "survey-maker") ?>
                                                </label>
                                                <label class="checkbox_ays form-check form-check-inline" for="ays_survey_show_submissions_order_type_descending">
                                                    <input type="radio" id="ays_survey_show_submissions_order_type_descending" value='by_desc' >
                                                    <?php echo __('Descending', "survey-maker") ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>  
                                </div> 
                            </div> 
                        </fieldset><!-- submissions settings -->
                        <hr>
                        <fieldset class="ays_toggle_parent">
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __("Survey multilanguage","survey-maker")?></h5>
                            </legend>
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
                                    <div class="form-group row" style="padding:0px;margin:0;">
                                        <div class="col-sm-12" style="padding:20px;">
                                            <div class="form-group row">
                                                <div class="col-sm-4">
                                                    <label for="ays_survey_multilanugage_shortcode">
                                                        <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                        <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Write your desired text in any WordPress language. It will be translated in the front-end. The languages must be included in the ISO 639-1 Code column.', "survey-maker"); ?>">
                                                            <i class="ays_fa ays_fa_info_circle"></i>
                                                        </a>
                                                    </label>
                                                </div>
                                                <div class="col-sm-8">
                                                    <input type="text" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[:en]Hello[:es]Hola[:]'>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    </div>
                                    <blockquote>
                                        <ul class="ays-survey-general-settings-blockquote-ul">
                                            <li>
                                                <?php
                                                    echo sprintf(
                                                        __( "In this shortcode you can add your desired text and its translation. The translated version of the text will be displayed in the front-end. The languages must be written in the %sLanguage Code%s", "survey-maker" ),
                                                        '<a href="https://www.loc.gov/standards/iso639-2/php/code_list.php" target="_blank">',
                                                        '</a>'
                                                    );
                                                ?>
                                            </li>
                                        </ul>
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset><!-- Survey multilanguage -->
                    </div>
                    <div id="tab2" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'tab2') ? 'ays-survey-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle"><?php echo __('Integrations',"survey-maker")?></p>
                        <hr/>
                        <?php
                            do_action( 'ays_sm_settings_page_integrations' );
                        ?>                        
                    </div>
                    <div id="tab3" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'tab3') ? 'ays-survey-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Message variables',"survey-maker")?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<p><?php echo __( 'You can copy these variables and paste them in the following options from the survey settings', "survey-maker" ); ?>:</p>
                                <ul class='ays_tooltop_ul'>
                                    <li><?php echo __( 'Thank you message', "survey-maker" ); ?></li>
                                </ul>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote>
                            <p><?php echo __( "You can copy these variables and paste them in the following options from the survey settings", "survey-maker" ); ?>:</p>
                            <p style="text-indent:10px;margin:0;">- <?php echo __( "Thank you message", "survey-maker" ); ?></p>
                            <div style="margin-top: 20px;background: #dae2e2;padding: 10px;">
                                <p style="margin:0;"><?php echo __( "Message variables for these features are available only in the Pro version.", "survey-maker" ); ?></p>
                                <p style="text-indent:10px;margin:0;color:red;">- <?php echo __( "Send email to user", "survey-maker" ); ?></p>
                                <p style="text-indent:10px;margin:0;color:red;">- <?php echo __( "Send email to admin", "survey-maker" ); ?></p>
                                <p style="text-indent:10px;margin:0;color:red;">- <?php echo __( "Email configuration", "survey-maker" ); ?></p>
                                <p style="text-indent:30px;margin:0;color:red;">* <?php echo __( "From Name", "survey-maker" ); ?></p>
                                <p style="text-indent:30px;margin:0;color:red;">* <?php echo __( "Subject", "survey-maker" ); ?></p>
                                <p style="text-indent:30px;margin:0;color:red;">* <?php echo __( "Reply To Name", "survey-maker" ); ?></p>
                            </div>
                        </blockquote>
                        <hr>
                        <div style="display: flex;justify-content: center; align-items: center;margin-bottom: 15px;"><iframe width="560" height="315" class="ays-survey-iframe-video-link" src="https://www.youtube.com/embed/ct7_edtpuAs" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <?php echo $actions->message_variables_section($message_variables); ?>
                            </div>
                        </div>
                    </div>
                    <div id="tab4" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'tab4') ? 'ays-survey-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Buttons texts',"survey-maker")?>
                            <a class="ays_help" data-toggle="tooltip" data-html="true" title="<p style='margin-bottom:3px;'><?php echo __( 'If you make a change here, these words will not be translated!', "survey-maker" ); ?>">
                                <i class="ays_fa ays_fa_info_circle"></i>
                            </a>
                        </p>
                        <blockquote class="ays_warning">
                            <p style="margin:0;"><?php echo __( "If you make a change here, these words will not be translated!", "survey-maker" ); ?></p>
                        </blockquote>
                        <hr>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_next_button">
                                    <?php echo __( "Next button", "survey-maker" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_next_button" name="ays_survey_next_button" class="ays-text-input ays-text-input-short"  value='<?php echo esc_attr($survey_next_button); ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_previous_button">
                                <?php echo __( "Previous button", "survey-maker" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_previous_button" name="ays_survey_previous_button" class="ays-text-input ays-text-input-short"  value='<?php echo esc_attr($survey_previous_button); ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_clear_button">
                                    <?php echo __( "Clear selection button", "survey-maker" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_clear_button" name="ays_survey_clear_button" class="ays-text-input ays-text-input-short"  value='<?php echo esc_attr($survey_clear_button); ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_finish_button">
                                    <?php echo __( "Finish button", "survey-maker" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_finish_button" name="ays_survey_finish_button" class="ays-text-input ays-text-input-short"  value='<?php echo esc_attr($survey_finish_button); ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_restart_button">
                                <?php echo __( "Restart survey button", "survey-maker" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_restart_button" name="ays_survey_restart_button" class="ays-text-input ays-text-input-short"  value='<?php echo esc_attr($survey_restart_button); ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_exit_button">
                                    <?php echo __( "Exit button", "survey-maker" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_exit_button" name="ays_survey_exit_button" class="ays-text-input ays-text-input-short"  value='<?php echo esc_attr($survey_exit_button); ?>'>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label for="ays_survey_login_button">
                                    <?php echo __( "Log In button", "survey-maker" ); ?>
                                </label>
                            </div>
                            <div class="col-sm-9">
                                <input type="text" id="ays_survey_login_button" name="ays_survey_login_button" class="ays-text-input ays-text-input-short"  value='<?php echo esc_attr($survey_login_button); ?>'>
                            </div>
                        </div>
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
                                    <div class="col-sm-3">
                                        <label for="ays_survey_check_button">
                                            <?php echo __( "Check button", "survey-maker" ); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="ays-text-input ays-text-input-short"  value='<?php echo __("Check button", "survey-maker"); ?>'>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <div class="col-sm-3">
                                        <label for="ays_survey_start_button">
                                            <?php echo __( "Start button", "survey-maker" ); ?>
                                        </label>
                                    </div>
                                    <div class="col-sm-9">
                                        <input type="text" class="ays-text-input ays-text-input-short"  value='<?php echo __("Start button", "survey-maker"); ?>'>
                                    </div>
                                </div>          
                            </div>          
                        </div>          
                    </div>
                    <div id="tab5" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'tab5') ? 'ays-survey-tab-content-active' : ''; ?>">
                        <p class="ays-subtitle">
                            <?php echo __('Shortcodes',"survey-maker"); ?>
                        </p>                        
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Display survey summary',"survey-maker"); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the given shortcode and insert it into any post or page to show the charts of the submissions of the given survey. Please change \'Your_Survey_ID\' to the corresponding ID of your survey.', "survey-maker" ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_submissions_summary" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_submissions_summary id="Your_Survey_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Display survey summary -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Extra shortcodes',"survey-maker"); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_first_name">
                                                <?php echo __( "Show User First Name", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's First Name. If the user is not logged-in, the shortcode will be empty.","survey-maker") ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_first_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_first_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_last_name">
                                                <?php echo __( "Show User Last Name", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's last name. If the user is not logged-in, the shortcode will be empty.","survey-maker") ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_last_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_last_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_nickname">
                                                <?php echo __( "Show User Nickname", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's nickname. If the user is not logged-in, the shortcode will be empty.","survey-maker") ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_nickname" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_nick_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_display_name">
                                                <?php echo __( "Show User Display name", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's display name. If the user is not logged-in, the shortcode will be empty.","survey-maker") ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_display_name" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_display_name]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_email">
                                                <?php echo __( "Show User Email", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows the logged-in user's email. If the user is not logged-in, the shortcode will be empty.","survey-maker") ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_email" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_email]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_user_wordpress_roles">
                                                <?php echo __( "Show User Wordpress Roles", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("Shows user's role(s) when logged-in. In case the user is not logged-in, the field will be empty.","survey-maker") ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_user_wordpress_roles" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_wordpress_roles]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_passed_users_count">
                                                <?php echo __( "Passed users count", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode and paste it in posts. Insert the Survey ID to receive the number of participants of the survey.',"survey-maker"); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_passed_users_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_passed_users_count id="Your_survey_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_show_creation_date">
                                                <?php echo __( "Show survey creation date", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Survey ID in the shortcode. It will show the creation date of the particular survey. If there is no survey available/found with that particular Survey ID, the shortcode will be empty.","survey-maker") ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_show_creation_date" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_creation_date id="Your_Survey_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_show_sections_count">
                                                <?php echo __( "Show survey sections count", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Survey ID in the shortcode. It will show the number of the sections of the given survey. If there is no survey available/found with that particular Survey ID, the shortcode will be empty.","survey-maker") ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_show_sections_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_sections_count id="Your_Survey_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_show_questions_count">
                                                <?php echo __( "Show survey questions count", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo esc_attr( __("You need to insert Your Survey ID in the shortcode. It will show the number of the questions of the given survey. If there is no survey available/found with that particular Survey ID, the shortcode will be empty.","survey-maker") ); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_show_questions_count" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_questions_count id="Your_Survey_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Extra shortcodes -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Most popular survey',"survey-maker"); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_most_popular">
                                                <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('Designed to show the most popular survey that is passed most commonly by users.',"survey-maker"); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_most_popular" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_most_popular count="1"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Most popular survey -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Survey Links by Category',"survey-maker"); ?></h5>
                            </legend>
                            <div class="form-group row" style="padding:0px;margin:0;">
                                <div class="col-sm-12" style="padding:20px;">
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label for="ays_survey_links_by_category">
                                                <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                <a class="ays_help" data-toggle="tooltip" title="<?php echo __('After adding the shortcode all surveys from the particular category will be collected in the front-end. After clicking on the Open button you will be redirected to the Survey page.',"survey-maker"); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="ays_survey_links_by_category" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_links_by_category id="YOUR_CATEGORY_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Survey Links by Category -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('All submission settings',"survey-maker")?></h5>
                            </legend>
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
                                            <label >
                                                <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Copy the given shortcode and insert it into any post or page to show all the submissions of the surveys.',"survey-maker")?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="ays-text-input" value='[ays_survey_all_submissions]'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-4">
                                            <label>
                                                <?php echo __( "Show to guests too", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Show the table to guests as well. By default, it is displayed only for logged-in users. So if the option is disabled, then only the logged-in users will be able to see the table. 
                                                Note: Despite the fact of showing the table to the guests, the table will contain only the info of the logged-in users.',"survey-maker")?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="checkbox" class="ays-checkbox-input" />
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "Table columns", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Select and reorder the given columns which should be displayed on the front-end.',"survey-maker")?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-all-submission-table ays-show-user-page-table">
                                                    <?php
                                                        foreach ($default_all_submissions_columns as $val) {
                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="checkbox" checked>
                                                                <label>
                                                                    <?php echo esc_attr($val); ?>
                                                                </label>
                                                            </li>
                                                        <?php
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- All Submission settings -->
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('User history settings',"survey-maker")?></h5>
                            </legend>
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
                                            <label >
                                                <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Copy the given shortcode and insert it into any post or page to show the current user’s submissions history. Each user will see individually presented content based on their taken surveys.',"survey-maker")?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" class="ays-text-input" value='[ays_survey_user_history]'>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <label>
                                                <?php echo __( "User history submissions table columns", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Select and reorder the given columns which should be displayed on the front-end.',"survey-maker")?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div class="ays-show-user-page-table-wrap">
                                                <ul class="ays-show-user-page-table">
                                                    <?php
                                                        foreach ($ays_survey_user_history_columns_order as $val) {
                                                            ?>
                                                            <li class="ays-user-page-option-row ui-state-default">
                                                                <input type="checkbox" value="<?php echo esc_attr($val); ?>" class="ays-checkbox-input" checked/>
                                                                <label>
                                                                    <?php echo esc_attr($val); ?>
                                                                </label>
                                                            </li>
                                                            <?php
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- User history settings -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Display question summary',"survey-maker"); ?></h5>
                            </legend>
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
                                            <label for="">
                                                <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Display each question summary individually on the front-end. To insert question ID, please click on the three dots located at the bottom right corner of the question(Surveys > the given survey > General tab).',"survey-maker"); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                            <div>
                                                <span style="font-size: 12px;padding: 2px 10px;font-style: italic;">
                                                    <a href="https://ays-demo.com/survey-question-summary-shortcode/" class="ays-survey-zindex-for-pro" style="color:#524e4e;position:relative;" target="_blank" title="PRO feature"><?php echo __("View Demo ", "survey-maker"); ?></a>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_question_summary id="Your_question_ID"]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Display question summary -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Recent surveys',"survey-maker"); ?></h5>
                            </legend>
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
                                            <label for="">
                                                <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('Copy the following shortcode, configure it based on your preferences and paste it into the post or widget.',"survey-maker"); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_activity_per_day]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- Recent surveys -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('User Activity Per Day Settings',"survey-maker"); ?></h5>
                            </legend>
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
                                            <label for="">
                                                <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('See how many surveys a user passed per day.',"survey-maker"); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_user_activity_per_day]'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset> <!-- User Activity Per Day -->
                        <hr>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;">[ ]</strong>
                                <h5><?php echo __('Survey Activity Per Day Settings',"survey-maker"); ?></h5>
                            </legend>
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
                                            <label for="">
                                                <?php echo __( "Shortcode", "survey-maker" ); ?>
                                                <a class="ays_help ays-survey-zindex-for-pro" data-toggle="tooltip" title="<?php echo __('See how many times the survey is passed per day.',"survey-maker"); ?>">
                                                    <i class="ays_fa ays_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input type="text" id="" class="ays-text-input" onclick="this.setSelectionRange(0, this.value.length)" readonly="" value='[ays_survey_activity_per_day id="Your_Survey_ID"]'>
                                        </div>
                                    </div>
                                    <blockquote>
                                        <b>ID</b> - Enter ID of the Survey. Example: id="2".
                                    </blockquote>
                                </div>
                            </div>
                        </fieldset> <!-- Survey Activity Per Day -->
                    </div>
                </div>
            </div>
            <hr/>
            <div style="position:sticky;padding:15px 0px;bottom:0;" class="ays-survey-save-changes-settings-page-mobile">
                <?php
                    wp_nonce_field('settings_action', 'settings_action');
                    // $other_attributes = array();

                    $other_attributes = array(
                        'id' => 'ays-button-apply',
                        'title' => 'Ctrl + s',
                        'data-toggle' => 'tooltip',
                        'data-delay'=> '{"show":"1000"}'
                    );
                    
                    submit_button(__('Save changes', "survey-maker"), 'primary ays-survey-loader-banner ays-survey-gen-settings-save', 'ays_submit', true, $other_attributes);
                    echo wp_kses_post($loader_iamge);
                ?>
            </div>
        </form>
    </div>
</div>
