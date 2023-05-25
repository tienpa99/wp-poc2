<?php
    $ays_tab = 'tab1';
    if(isset($_GET['tab'])){
        $ays_tab = sanitize_text_field( $_GET['tab'] );
    }

    $action = (isset($_GET['action'])) ? sanitize_text_field( $_GET['action'] ) : '';

    $id = (isset($_GET['id'])) ? absint( sanitize_text_field( $_GET['id'] ) ) : null;

    $html_name_prefix = 'ays_';
    $name_prefix = 'survey_';

    $user_id = get_current_user_id();

    $options = array(
        // Styles Tab
        'survey_theme' => 'classic_light',
        'survey_color' => 'rgb(255, 87, 34)', // #673ab7
        'survey_background_color' => '#fff',
        'survey_text_color' => '#333',
        'survey_buttons_text_color' => '#333',
        'survey_width' => '',
        'survey_width_by_percentage_px' => 'pixels',
        'survey_mobile_max_width' => '',
        'survey_custom_class' => '',
        'survey_custom_css' => '',
        'survey_logo' => '',
        'survey_logo_image_position' => 'right',
        'survey_logo_title' => '',
        'survey_cover_photo' => '',
        'survey_cover_photo_height' => 150,
        'survey_cover_photo_mobile_height' => 150,
        'survey_cover_photo_position' => "center_center",
        'survey_cover_photo_object_fit' => "cover",
        'survey_title_alignment' => 'left',
        'survey_title_font_size' => 30,
        'survey_title_font_size_for_mobile' => 30,
        'survey_title_box_shadow_enable' => 'off',
        'survey_title_box_shadow_color'  => '#333',
        'survey_title_text_shadow_x_offset'  => 0,
        'survey_title_text_shadow_y_offset'  => 0,
        'survey_title_text_shadow_z_offset'  => 10,
        'section_title_font_size' => 32,
        'survey_section_title_alignment' => 'left',
        'survey_section_description_alignment' => 'left',
        'survey_section_description_font_size' => 14,
        'survey_section_description_font_size_mobile' => 14,

        'survey_question_font_size' => 16,
        'survey_question_font_size_mobile' => 16,
        'survey_question_title_alignment' => 'left',
        'survey_question_image_width' => '',
        'survey_question_image_height' => '',
        'survey_question_image_sizing' => 'cover',
        'survey_question_padding' => 24,
        'survey_question_caption_text_color' => '#333',
        'survey_question_caption_text_alignment' => 'center',
        'survey_question_caption_font_size' => 16,
        'survey_question_caption_font_size_on_mobile' => 16,
        'survey_question_caption_text_transform' => 'none',

        'survey_answer_font_size' => 15,
        'survey_answer_font_size_on_mobile' => 15,
        'survey_answers_view' => 'list',        
        'survey_answers_view_alignment' => 'space-around',
        'survey_answers_object_fit' => 'cover',
        'survey_answers_padding' => 8,
        'survey_answers_gap' => 0,
        'survey_answers_image_size' => 195,

        'survey_buttons_size' => 'medium',
        'survey_buttons_font_size' => 14,
        'survey_buttons_left_right_padding' => 20,
        'survey_buttons_top_bottom_padding' => 0,
        'survey_buttons_border_radius' => 3,
        'survey_buttons_alignment' => 'left',
        'survey_buttons_top_distance' => 10,

        // Settings Tab
        'survey_show_title' => 'on',
        'survey_show_section_header' => 'on',
        'survey_enable_randomize_answers' => 'off',
        'survey_enable_randomize_questions' => 'off',
        'survey_enable_clear_answer' => 'off',
        'survey_enable_previous_button' => 'off',
        'survey_allow_html_in_answers' => 'off',
        'survey_allow_html_in_section_description' => 'off',
        'survey_enable_leave_page' => 'on',
        'survey_enable_info_autofill' => 'on',
        'survey_enable_schedule' => 'off',
        'survey_schedule_active' => current_time( 'mysql' ),
        'survey_schedule_deactive' => current_time( 'mysql' ),
        'survey_schedule_show_timer' => 'off',
        'survey_show_timer_type' => 'countdown',
        'survey_schedule_pre_start_message' =>  __("The survey will be available soon!", "survey-maker"),
        'survey_schedule_expiration_message' => __("This survey has expired!", "survey-maker"),
        'survey_auto_numbering' => 'none',
        'survey_auto_numbering_questions' => 'none',
        'survey_full_screen_button_color' => '#333',
        'survey_hide_section_pagination_text' => 'off',
        'survey_pagination_positioning' => 'none',
        'survey_hide_section_bar' => 'off',
        'survey_progress_bar_text' => 'Page',
        'survey_pagination_text_color' => '#333',
        'survey_required_questions_message' => 'This is a required question',

        // Result Settings Tab
        'survey_redirect_after_submit' => 'off',
        'survey_submit_redirect_url' => '',
        'survey_submit_redirect_delay' => '',
        'survey_submit_redirect_new_tab' => 'off',
        'survey_enable_exit_button' => 'off',
        'survey_exit_redirect_url' => '',
        'survey_enable_restart_button' => 'off',
        'survey_final_result_text' => '',
        'survey_show_questions_as_html' => 'on',
        'survey_loader' => 'default',
        'survey_main_url' => '',

        // Limitation Tab
        'survey_limit_users' => 'off',
        'survey_limit_users_by' => 'ip',
        'survey_max_pass_count' => 1,
        'survey_limitation_message' => '',
        'survey_redirect_url' => '',
        'survey_redirect_delay' => 0,
        'survey_enable_logged_users' => 'off',
        'survey_logged_in_message' => '',
        'survey_show_login_form' => 'off',
        'survey_enable_takers_count' => 'off',
        'survey_takers_count' => 1,

        // E-Mail Tab
        'survey_enable_mail_user' => 'off',
        'survey_mail_message' => '',
        'survey_enable_mail_admin' => 'off',
        'survey_send_mail_to_site_admin' => 'on',
        'survey_additional_emails' => '',
        'survey_mail_message_admin' => '',
        'survey_email_configuration_from_email' => '',
        'survey_email_configuration_from_name' => '',
        'survey_email_configuration_from_subject' => '',
        'survey_email_configuration_replyto_email' => '',
        'survey_email_configuration_replyto_name' => '',

    );

    $object = array(
        'author_id' => $user_id,
        'title' => '',
        'description' => '',
        'category_ids' => '',
        'question_ids' => '',
        'date_created' => current_time( 'mysql' ),
        'date_modified' => current_time( 'mysql' ),
        'image' => '',
        'status' => 'published',
        'ordering' => '0',
        'post_id' => '0',
        'section_ids' => '',
        'options' => json_encode($options),
    );

    $survey_message_vars = array(
        '%%user_name%%'        => 'User Name',
        '%%user_email%%'       => 'User Email',
        '%%survey_title%%'     => 'Survey Title',
        '%%survey_id%%'        => 'Survey ID',
        '%%questions_count%%'  => 'Questions Count',
        '%%current_date%%'     => 'Current Date',
        '%%current_time%%'     => 'Current Time',
        '%%unique_code%%'      => 'Unique Code',
        '%%sections_count%%'   => 'Sections Count',
        '%%users_count%%'      => 'Users Count',
        '%%users_first_name%%' => 'Users First Name',
        '%%users_last_name%%'  => 'Users Last Name',
        '%%users_nick_name%%'  => 'Users Nick Name',
        '%%user_wordpress_roles%%'  => 'Users Wordpress Roles',
        '%%users_display_name%%'    => 'Users Display Name',
        '%%users_ip_address%%'      => 'Users IP Address',
        '%%creation_date%%'         => 'Current Date',
        '%%current_survey_author%%' => 'Survey Author',
        '%%current_survey_author_email%%' => 'Survey Author Email',
        '%%current_survey_page_link%%'    => 'Survey Page Link',
        '%%admin_email%%'       => 'Admin Email',        
        '%%submission_count%%'  => 'Submission count',
    );

    $heading = '';
    $survey_settings = $this->settings_obj;
    switch ($action) {
        case 'add':
            $heading = __( 'Add new survey', "survey-maker" );
            // $survey_default_options = ($survey_settings->ays_get_setting('survey_default_options') === false) ? json_encode(array()) : $survey_settings->ays_get_setting('survey_default_options');
            // if (! empty($survey_default_options)) {
            //     $object['options'] = $survey_default_options;
            // }
            break;
        case 'edit':
            $heading = __( 'Edit survey', "survey-maker" );
            $object = $this->surveys_obj->get_item_by_id( $id );
            break;
    }

    if (isset($_POST['ays_submit']) || isset($_POST['ays_submit_top'])) {
        $_POST["id"] = $id;
        $this->surveys_obj->add_or_edit_item( $_POST );
    }

    if(isset($_POST['ays_apply']) || isset($_POST['ays_apply_top'])){
        $_POST["id"] = $id;
        $_POST['save_type'] = 'apply';
        $this->surveys_obj->add_or_edit_item( $_POST );
    }

    if(isset($_POST['ays_save_new']) || isset($_POST['ays_save_new_top'])){
        $_POST["id"] = $id;
        $_POST['save_type'] = 'save_new';
        $this->surveys_obj->add_or_edit_item( $_POST );
    }

    if (isset($_POST['ays_default'])) {
        $_POST["id"] = $id;
        $_POST['save_type'] = 'apply';
        $_POST['save_type_default_options'] = 'save_type_default_options';
        $this->surveys_obj->add_or_edit_item( $_POST );
    }

    $loader_iamge = '<span class="display_none ays_survey_loader_box"><img src="'. SURVEY_MAKER_ADMIN_URL .'/images/loaders/loading.gif"></span>';

    $ays_super_admin_email = get_option('admin_email');
    $wp_general_settings_url = admin_url( 'options-general.php' ) ;

    // Options
    $options = isset( $object['options'] ) && $object['options'] != '' ? $object['options'] : '';
    $options = json_decode( $options, true );

    // Author ID
    $author_id = isset( $object['author_id'] ) && $object['author_id'] != '' ? intval( $object['author_id'] ) : $user_id;
    
    // Title
    $title = isset( $object['title'] ) && $object['title'] != '' ? stripslashes( htmlentities( $object['title'] ) ) : '';
    
    // Description
    $description = isset( $object['description'] ) && $object['description'] != '' ? stripslashes( htmlentities( $object['description'] ) ) : '';
    
    // Status
    $status = isset( $object['status'] ) && $object['status'] != '' ? stripslashes( $object['status'] ) : 'published';
    
    // Date created
    $date_created = isset( $object['date_created'] ) && Survey_Maker_Admin::validateDate( $object['date_created'] ) ? $object['date_created'] : current_time( 'mysql' );
    
    // Date modified
    $date_modified = current_time( 'mysql' );

    // Survey categories IDs
    $categories = $this->surveys_obj->get_categories();

    // Survey categories IDs
    $category_ids = isset( $object['category_ids'] ) && $object['category_ids'] != '' ? $object['category_ids'] : '';
    $category_ids = $category_ids == '' ? array() : explode( ',', $category_ids );

    // Survey questions IDs
    $question_ids = isset( $object['question_ids'] ) && $object['question_ids'] != '' ? $object['question_ids'] : '';
    // $question_ids = $question_ids == '' ? array() : explode( ',', $question_ids );

    // Survey image
    $image = isset( $object['image'] ) && $object['image'] != '' ? $object['image'] : '';

    // Post ID
    $post_id = isset( $object['post_id'] ) && ! empty( $object['post_id'] ) ? intval( $object['post_id'] ) : 0;

    // Section Ids
    $sections_ids = (isset( $object['section_ids' ] ) && $object['section_ids'] != '') ? $object['section_ids'] : '';

    $sections = Survey_Maker_Data::get_sections_by_survey_id($sections_ids);
    $sections_count = count( $sections );

    $multiple_sections = $sections_count > 1 ? true : false;

    $gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode($this->settings_obj->ays_get_setting('options'), true);
    $survey_default_type = (isset($gen_options[$name_prefix . 'default_type']) && $gen_options[$name_prefix . 'default_type'] != '') ? stripslashes($gen_options[$name_prefix . 'default_type']) : null;
    $survey_answer_default_count = (isset($gen_options[$name_prefix . 'answer_default_count']) && $gen_options[$name_prefix . 'answer_default_count'] != '') ? intval($gen_options[$name_prefix . 'answer_default_count']) : null;

    // WP Editor height
    $survey_wp_editor_height = (isset($gen_options[$name_prefix . 'wp_editor_height']) && $gen_options[$name_prefix . 'wp_editor_height'] != '' && $gen_options[$name_prefix . 'wp_editor_height'] != 0) ? absint( esc_attr($gen_options[$name_prefix . 'wp_editor_height']) ) : 100;

    // Make question required
    $survey_make_questions_required = (isset($gen_options[$name_prefix . 'make_questions_required']) && $gen_options[$name_prefix . 'make_questions_required'] == 'on') ? true : false;

    // Lazy loading for images
    $survey_lazy_loading_for_images = (isset($gen_options[$name_prefix . 'survey_lazy_loading_for_images']) && $gen_options[$name_prefix . 'survey_lazy_loading_for_images'] == 'on') ? true : false;

    if($survey_default_type === null){
        $survey_default_type = 'radio';
    }

    if($survey_answer_default_count === null){
        $survey_answer_default_count = '1';
    }

    $question_types = array(
        "radio" => __("Radio", "survey-maker"),
        "checkbox" => __("Checkbox (Multi)", "survey-maker"),
        "select" => __("Dropdown", "survey-maker"),
        "text" => __("Paragraph", "survey-maker"),
        "short_text" => __("Short Text", "survey-maker"),
        "number" => __("Number", "survey-maker"),
        "yesorno" => __("Yes or No", "survey-maker"),
        "email" => __("Email", "survey-maker"),
        "name" => __("Name", "survey-maker"),
    );
    
    $question_types_placeholders = array(
        "radio" => '',
        "checkbox" => '',
        "select" => '',
        "yesorno" => '',
        "text" => __("Your answer", "survey-maker"),
        "short_text" => __("Your answer", "survey-maker"),
        "number" => __("Your answer", "survey-maker"),
        "email" => __("Your email", "survey-maker"),
        "name" => __("Your name", "survey-maker"),
    );

    $text_question_types = array(
        "text",
        "short_text",
        "number",
        "email",
        "name",
    );

    $logic_jump_question_types = array(
        "radio",
        "select",
        "yesorno",
    );

    $survey_answers_alignment_grid_types = array(
        "space-around",
        "space-between",
    );

    foreach ($sections as $section_key => $section) {
        $sections[$section_key]['title'] = (isset($section['title']) && $section['title'] != '') ? stripslashes( htmlentities( $section['title'] ) ) : '';
        $sections[$section_key]['description'] = (isset($section['description']) && $section['description'] != '') ? stripslashes( htmlentities( $section['description'] ) ) : '';

        $section_opts = json_decode( $section['options'], true );
        $section_opts['collapsed'] = (isset($section_opts['collapsed']) && $section_opts['collapsed'] != '') ? $section_opts['collapsed'] : 'expanded';

        $section_questions = Survey_Maker_Data::get_questions_by_section_id( intval( $section['id'] ), $question_ids );

        foreach ($section_questions as $question_key => $question) {
            $section_questions[$question_key]['question'] = (isset($question['question']) && $question['question'] != '') ? stripslashes( $question['question'] ) : '';
            $section_questions[$question_key]['image'] = (isset($question['image']) && $question['image'] != '') ? $question['image'] : '';
            $section_questions[$question_key]['type'] = (isset($question['type']) && $question['type'] != '') ? $question['type'] : $survey_default_type;
            $section_questions[$question_key]['user_variant'] = (isset($question['user_variant']) && $question['user_variant'] == 'on') ? true : false;

            $opts = json_decode( $question['options'], true );
            $opts['required'] = (isset($opts['required']) && $opts['required'] == 'on') ? true : false;
            $opts['collapsed'] = (isset($opts['collapsed']) && $opts['collapsed'] != '') ? $opts['collapsed'] : 'expanded';
            $opts['enable_max_selection_count'] = (isset($opts['enable_max_selection_count']) && $opts['enable_max_selection_count'] == 'on') ? true : false;
            $opts['max_selection_count'] = (isset($opts['max_selection_count']) && $opts['max_selection_count'] != '') ? intval( $opts['max_selection_count'] ) : '';
            $opts['min_selection_count'] = (isset($opts['min_selection_count']) && $opts['min_selection_count'] != '') ? intval( $opts['min_selection_count'] ) : '';
            // Text Limitations
            $opts['enable_word_limitation'] = (isset($opts['enable_word_limitation']) && $opts['enable_word_limitation'] == 'on') ? true : false;
            $opts['limit_by']      = (isset($opts['limit_by']) && $opts['limit_by'] != '') ? sanitize_text_field($opts['limit_by'])  : '';
            $opts['limit_length']  = (isset($opts['limit_length']) && $opts['limit_length'] != '') ? intval( $opts['limit_length'] ) : '';
            $opts['limit_counter'] = (isset($opts['limit_counter']) && $opts['limit_counter'] == 'on') ? true : false;
            // Number Limitations
            $opts['enable_number_limitation']    = (isset($opts['enable_number_limitation']) && $opts['enable_number_limitation'] == 'on') ? true : false;
            $opts['number_min_selection']        = (isset($opts['number_min_selection']) && $opts['number_min_selection'] != '') ? intval( $opts['number_min_selection'] )  : '';
            $opts['number_max_selection']        = (isset($opts['number_max_selection']) && $opts['number_max_selection'] != '') ? intval( $opts['number_max_selection'] ) : '';
            $opts['number_error_message']        = (isset($opts['number_error_message']) && $opts['number_error_message'] != '') ? stripslashes( esc_attr($opts['number_error_message'])) : '';
            $opts['enable_number_error_message'] = (isset($opts['enable_number_error_message']) && $opts['enable_number_error_message'] == 'on') ? true : false;
            $opts['number_limit_length']         = (isset($opts['number_limit_length']) && $opts['number_limit_length'] != '') ? stripslashes( esc_attr($opts['number_limit_length'])) : '';
            $opts['enable_number_limit_counter'] = (isset($opts['enable_number_limit_counter']) && $opts['enable_number_limit_counter'] == 'on') ? true : false;
            
            // Input types placeholders
            $opts['placeholder'] = (isset($opts['survey_input_type_placeholder'])) ? stripslashes(esc_attr($opts['survey_input_type_placeholder'])) : $question_types_placeholders[$question['type']];

            $opts['image_caption'] = (isset($opts['image_caption']) && $opts['image_caption'] != '') ? stripslashes(esc_attr($opts['image_caption'])) : '';

            $opts['image_caption_enable'] = (isset($opts['image_caption_enable']) && $opts['image_caption_enable'] == 'on') ? true : false;

            $opts['with_editor'] = ! isset( $opts['with_editor'] ) ? 'off' : $opts['with_editor'];
            $opts['with_editor'] = $opts['with_editor'] == 'on' ? true : false;

            $q_answers = Survey_Maker_Data::get_answers_by_question_id( intval( $question['id'] ) );

            foreach ($q_answers as $answer_key => $answer) {
                $q_answers[$answer_key]['answer'] = (isset($answer['answer']) && $answer['answer'] != '') ? stripslashes( htmlentities( $answer['answer'] ) ) : '';
                $q_answers[$answer_key]['image'] = (isset($answer['image']) && $answer['image'] != '') ? $answer['image'] : '';
                $q_answers[$answer_key]['placeholder'] = (isset($answer['placeholder']) && $answer['placeholder'] != '') ? $answer['placeholder'] : '';
            }

            $section_questions[$question_key]['answers'] = $q_answers;

            $section_questions[$question_key]['options'] = $opts;
        }

        $sections[$section_key]['questions'] = $section_questions;
        $sections[$section_key]['options'] = $section_opts;
    }

        // =======================  //  ======================= // ======================= // ======================= // ======================= //

    // =============================================================
    // ======================    Styles Tab    =====================
    // ========================    START    ========================


        // Survey Theme
        $survey_theme = (isset($options[ $name_prefix . 'theme' ]) && $options[ $name_prefix . 'theme' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'theme' ] ) ) : 'classic_light';

        // Survey Color
        $survey_color = (isset($options[ $name_prefix . 'color' ]) && $options[ $name_prefix . 'color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'color' ] ) ) : '#ff5722'; // #673ab7

        // Background color
        $survey_background_color = (isset($options[ $name_prefix . 'background_color' ]) && $options[ $name_prefix . 'background_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'background_color' ] ) ) : '#fff';

        // Text Color
        $survey_text_color = (isset($options[ $name_prefix . 'text_color' ]) && $options[ $name_prefix . 'text_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'text_color' ] ) ) : '#333';

        // Buttons text Color
        $survey_buttons_text_color = (isset($options[ $name_prefix . 'buttons_text_color' ]) && $options[ $name_prefix . 'buttons_text_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'buttons_text_color' ] ) ) : $survey_text_color;

        // Width
        $survey_width = (isset($options[ $name_prefix . 'width' ]) && $options[ $name_prefix . 'width' ] != '') ? absint ( intval( $options[ $name_prefix . 'width' ] ) ) : '';

        // Survey Width by percentage or pixels
        $survey_width_by_percentage_px = (isset($options[ $name_prefix . 'width_by_percentage_px' ]) && $options[ $name_prefix . 'width_by_percentage_px' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'width_by_percentage_px' ] ) ) : 'percentage';

        // Mobile width
        $survey_mobile_width = (isset($options[ $name_prefix . 'mobile_width' ]) && $options[ $name_prefix . 'mobile_width' ] != '') ? absint ( intval( $options[ $name_prefix . 'mobile_width' ] ) ) : '';

        // Survey mobile width by percentage or pixels
        $survey_mobile_width_by_percentage_px = (isset($options[ $name_prefix . 'mobile_width_by_percent_px' ]) && $options[ $name_prefix . 'mobile_width_by_percent_px' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'mobile_width_by_percent_px' ] ) ) : 'percentage';

        // Survey container max-width for mobile
        $survey_mobile_max_width = (isset($options[ $name_prefix . 'mobile_max_width' ]) && $options[ $name_prefix . 'mobile_max_width' ] != "") ? absint ( intval( $options[ $name_prefix . 'mobile_max_width' ] ) ) : '';

        // Custom class for survey container
        $survey_custom_class = (isset($options[ $name_prefix . 'custom_class' ]) && $options[ $name_prefix . 'custom_class' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'custom_class' ] ) ) : '';

        // Custom CSS
        $survey_custom_css = (isset($options[ $name_prefix . 'custom_css' ]) && $options[ $name_prefix . 'custom_css' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'custom_css' ] ) ) : '';

        // Survey logo
        $survey_logo = (isset($options[ $name_prefix . 'logo' ]) && $options[ $name_prefix . 'logo' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'logo' ] ) ) : '';
        $survey_logo_text = __('Add Image', "survey-maker");
        $survey_logo_check = 'display_none_not_important';
        if ( $survey_logo != '' ) {
            $survey_logo_text = __('Edit Image', "survey-maker");
            $survey_logo_check = '';
        }

        // Survey Logo url
        $survey_logo_image_url       = (isset($options[ $name_prefix . 'logo_url' ]) &&  $options[ $name_prefix . 'logo_url' ] != '') ? esc_attr( $options[ $name_prefix . 'logo_url' ] ) : '';
        $survey_logo_image_url_check = (isset($options[ $name_prefix . 'enable_logo_url' ]) &&  $options[ $name_prefix . 'enable_logo_url' ] == 'on') ? true : false;
        $survey_logo_image_url_checked = $survey_logo_image_url_check ? 'checked' : '';
        $survey_logo_image_url_display = $survey_logo_image_url_check ? '' : 'display_none_not_important';
        $survey_logo_image_url_check_new_tab = (isset($options[ $name_prefix . 'logo_url_new_tab' ]) &&  $options[ $name_prefix . 'logo_url_new_tab' ] == 'on') ? "checked" : "";
        //

        // Survey Logo position
        $survey_logo_image_position = (isset( $options[ $name_prefix . 'logo_image_position' ] ) && $options[ $name_prefix . 'logo_image_position' ] != '') ? esc_attr( $options[ $name_prefix . 'logo_image_position' ] ) : 'right';

        // Survey Logo title
        $survey_logo_title = (isset( $options[ $name_prefix . 'logo_title' ] ) && $options[ $name_prefix . 'logo_title' ] != '') ? esc_attr( $options[ $name_prefix . 'logo_title' ] ) : '';

        // Survey cover photo
        $survey_cover_photo = (isset($options[ $name_prefix . 'cover_photo' ]) && $options[ $name_prefix . 'cover_photo' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'cover_photo' ] ) ) : '';
        $survey_cover_photo_text = __('Add Image', "survey-maker");
        $survey_cover_photo_check = 'display_none_not_important';
        if ( $survey_cover_photo != '' ) {
            $survey_cover_photo_text = __('Edit Image', "survey-maker");
            $survey_cover_photo_check = '';
        }

        // Survey cover photo height
        $survey_cover_photo_height   = (isset($options[ $name_prefix . 'cover_photo_height' ]) && $options[ $name_prefix . 'cover_photo_height' ] != '') ? esc_attr( $options[ $name_prefix . 'cover_photo_height' ] ) : 150;

        // Survey cover photo mobile height
        $survey_cover_photo_mobile_height   = (isset($options[ $name_prefix . 'cover_photo_mobile_height' ]) && $options[ $name_prefix . 'cover_photo_mobile_height' ] != '') ? esc_attr( $options[ $name_prefix . 'cover_photo_mobile_height' ] ) : $survey_cover_photo_height;

        // Survey cover photo position
        $survey_cover_photo_position = (isset($options[ $name_prefix . 'cover_photo_position' ]) && $options[ $name_prefix . 'cover_photo_position' ] != '') ? esc_attr( $options[ $name_prefix . 'cover_photo_position' ] ) : "center_center";
        
        // Survey cover photo position
        $survey_cover_photo_object_fit = (isset($options[ $name_prefix . 'cover_photo_object_fit' ]) && $options[ $name_prefix . 'cover_photo_object_fit' ] != '') ? esc_attr( $options[ $name_prefix . 'cover_photo_object_fit' ] ) : "cover";        

        // Survey title alignment
        $survey_title_alignment = (isset( $options[ $name_prefix . 'title_alignment' ] ) && $options[ $name_prefix . 'title_alignment' ] != '') ? esc_attr( $options[ $name_prefix . 'title_alignment' ] ) : 'left';

        // Survey title font size
        $survey_title_font_size = (isset( $options[ $name_prefix . 'title_font_size' ] ) && $options[ $name_prefix . 'title_font_size' ] != '' && $options[ $name_prefix . 'title_font_size' ] != '0') ? esc_attr( $options[ $name_prefix . 'title_font_size' ] ) : 30;

        // Survey title font size mobile
        $survey_title_font_size_for_mobile = (isset( $options[ $name_prefix . 'title_font_size_for_mobile' ] ) && $options[ $name_prefix . 'title_font_size_for_mobile' ] != '' && $options[ $name_prefix . 'title_font_size_for_mobile' ] != '0') ? esc_attr( $options[ $name_prefix . 'title_font_size_for_mobile' ] ) : 30;

        // Survey title box shadow
        $survey_title_box_shadow_enable = (isset( $options[ $name_prefix . 'title_box_shadow_enable' ] ) && $options[ $name_prefix . 'title_box_shadow_enable' ] == 'on') ? true : false;

        // Survey title box shadow color
        $survey_title_box_shadow_color  = (isset( $options[ $name_prefix . 'title_box_shadow_color' ] ) && $options[ $name_prefix . 'title_box_shadow_color' ] != '') ? esc_attr( $options[ $name_prefix . 'title_box_shadow_color' ] ) : "#333";
        
        // === Survey title box shadow offsets start ===
            // Survey title box shadow offset x
            $survey_title_text_shadow_x_offset  = ( isset($options[ $name_prefix . 'title_text_shadow_x_offset' ] ) && $options[ $name_prefix . 'title_text_shadow_x_offset' ] != "") ? esc_attr($options[ $name_prefix . 'title_text_shadow_x_offset' ]) : 0;
            // Survey title box shadow offset y
            $survey_title_text_shadow_y_offset  = ( isset($options[ $name_prefix . 'title_text_shadow_y_offset' ] ) && $options[ $name_prefix . 'title_text_shadow_y_offset' ] != "") ? esc_attr($options[ $name_prefix . 'title_text_shadow_y_offset' ]) : 0;
            // Survey title box shadow offset z
            $survey_title_text_shadow_z_offset  = ( isset($options[ $name_prefix . 'title_text_shadow_z_offset' ] ) && $options[ $name_prefix . 'title_text_shadow_z_offset' ] != "") ? esc_attr($options[ $name_prefix . 'title_text_shadow_z_offset' ]) : 10;
        // === Survey title box shadow offsets end ===

        // Survey section title font size PC
        $survey_section_title_font_size = (isset( $options[ $name_prefix . 'section_title_font_size' ] ) && $options[ $name_prefix . 'section_title_font_size' ] != '' && $options[ $name_prefix . 'section_title_font_size' ] != '0') ? esc_attr( $options[ $name_prefix . 'section_title_font_size' ] )  : 32;
        // Survey section title font size Mobile
        $survey_section_title_font_size_mobile = (isset( $options[ $name_prefix . 'section_title_font_size_mobile' ] ) && $options[ $name_prefix . 'section_title_font_size_mobile' ] != '' && $options[ $name_prefix . 'section_title_font_size_mobile' ] != '0') ? esc_attr( $options[ $name_prefix . 'section_title_font_size_mobile' ] )  : 32;

        // Survey section title alignment
        $survey_section_title_alignment = (isset( $options[ $name_prefix . 'section_title_alignment' ] ) && $options[ $name_prefix . 'section_title_alignment' ] != '') ? esc_attr( $options[ $name_prefix . 'section_title_alignment' ] ) : 'left';

        // Survey section description alignment
        $survey_section_description_alignment = (isset( $options[ $name_prefix . 'section_description_alignment' ] ) && $options[ $name_prefix . 'section_description_alignment' ] != '') ? esc_attr( $options[ $name_prefix . 'section_description_alignment' ] ) : 'left';

        // Survey description font size
        $survey_section_description_font_size = (isset( $options[ $name_prefix . 'section_description_font_size' ] ) && $options[ $name_prefix . 'section_description_font_size' ] != '' && $options[ $name_prefix . 'section_description_font_size' ] != '0') ? esc_attr( $options[ $name_prefix . 'section_description_font_size' ] ) : 14;

        // Survey title font size mobile
        $survey_section_description_font_size_mobile = (isset( $options[ $name_prefix . 'section_description_font_size_mobile' ] ) && $options[ $name_prefix . 'section_description_font_size_mobile' ] != '' && $options[ $name_prefix . 'section_description_font_size_mobile' ] != '0') ? esc_attr( $options[ $name_prefix . 'section_description_font_size_mobile' ] ) : 14;

        // =========== Questions Styles Start ===========

            // Question font size
            $survey_question_font_size = (isset($options[ $name_prefix . 'question_font_size' ]) && $options[ $name_prefix . 'question_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_font_size' ] ) ) : 16;

            // Question font size mobile
            $survey_question_font_size_mobile = (isset($options[ $name_prefix . 'question_font_size_mobile' ]) && $options[ $name_prefix . 'question_font_size_mobile' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_font_size_mobile' ] ) ) : 16;

            // Question title alignment
            $survey_question_title_alignment = (isset($options[ $name_prefix . 'question_title_alignment' ]) && $options[ $name_prefix . 'question_title_alignment' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'question_title_alignment' ] ) ) : 'left';

            // Question Image Width
            $survey_question_image_width = (isset($options[ $name_prefix . 'question_image_width' ]) && $options[ $name_prefix . 'question_image_width' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_image_width' ] ) ) : '';

            // Question Image Height
            $survey_question_image_height = (isset($options[ $name_prefix . 'question_image_height' ]) && $options[ $name_prefix . 'question_image_height' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_image_height' ] ) ) : '';

            // Question Image sizing
            $survey_question_image_sizing = (isset($options[ $name_prefix . 'question_image_sizing' ]) && $options[ $name_prefix . 'question_image_sizing' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'question_image_sizing' ] ) ) : 'cover';
            
            // Question padding
            $survey_question_padding = (isset($options[ $name_prefix . 'question_padding' ]) && $options[ $name_prefix . 'question_padding' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_padding' ] ) ) : 24;
            
            // Question caption text color
            $options[ $name_prefix . 'question_caption_text_color' ] = (isset($options[ $name_prefix . 'question_caption_text_color' ]) && $options[ $name_prefix . 'question_caption_text_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'question_caption_text_color' ] ) ) : $survey_text_color;
            $survey_question_caption_text_color = $options[ $name_prefix . 'question_caption_text_color' ];
            
            // Question caption text alignment
            $survey_question_caption_text_alignment = (isset($options[ $name_prefix . 'question_caption_text_alignment' ]) && $options[ $name_prefix . 'question_caption_text_alignment' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'question_caption_text_alignment' ] ) ) : 'center';

            // Question caption font size
            $survey_question_caption_font_size = (isset($options[ $name_prefix . 'question_caption_font_size' ]) && $options[ $name_prefix . 'question_caption_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_caption_font_size' ] ) ) : 16;

            // Question caption font size on mobile
            $options[ $name_prefix . 'question_caption_font_size_on_mobile' ] = isset($options[ $name_prefix . 'question_caption_font_size_on_mobile' ]) ? $options[ $name_prefix . 'question_caption_font_size_on_mobile' ] : $survey_question_caption_font_size;
            $survey_question_caption_font_size_on_mobile = (isset($options[ $name_prefix . 'question_caption_font_size_on_mobile' ]) && $options[ $name_prefix . 'question_caption_font_size_on_mobile' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_caption_font_size_on_mobile' ] ) ) : 16;

            // Question caption text transform
            $survey_question_caption_text_transform = (isset($options[ $name_prefix . 'question_caption_text_transform' ]) && $options[ $name_prefix . 'question_caption_text_transform' ] != '') ? esc_attr ( $options[ $name_prefix . 'question_caption_text_transform' ] ) : 'none';

        // =========== Questions Styles End   ===========

        // =========== Answers Styles Start ===========

            // Answer font size
            $survey_answer_font_size = (isset($options[ $name_prefix . 'answer_font_size' ]) && $options[ $name_prefix . 'answer_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'answer_font_size' ] ) ) : 15;

            // Answer font size mobile
            $survey_answer_font_size_on_mobile = (isset($options[ $name_prefix . 'answer_font_size_on_mobile' ]) && $options[ $name_prefix . 'answer_font_size_on_mobile' ] != '') ? absint ( intval( $options[ $name_prefix . 'answer_font_size_on_mobile' ] ) ) : 15;

            // Answer view
            $survey_answers_view = (isset($options[ $name_prefix . 'answers_view' ]) && $options[ $name_prefix . 'answers_view' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'answers_view' ] ) ) : 'list';
            
            // Answer view alignment
            $survey_answers_view_alignment = (isset($options[ $name_prefix . 'answers_view_alignment' ]) && $options[ $name_prefix . 'answers_view_alignment' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'answers_view_alignment' ] ) ) : 'space-around';
            $survey_answers_view_alignment = ($survey_answers_view == 'list' && in_array($survey_answers_view_alignment , $survey_answers_alignment_grid_types)) ? 'flex-start' : $survey_answers_view_alignment;

            // Answer object-fit
            $survey_answers_object_fit = (isset($options[ $name_prefix . 'answers_object_fit' ]) && $options[ $name_prefix . 'answers_object_fit' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'answers_object_fit' ] ) ) : 'cover';

            // Answer padding
            $survey_answers_padding = (isset($options[ $name_prefix . 'answers_padding' ]) && $options[ $name_prefix . 'answers_padding' ] != '') ? absint ( intval( $options[ $name_prefix . 'answers_padding' ] ) ) : 8;

            // Answer Gap
            $survey_answers_gap = (isset($options[ $name_prefix . 'answers_gap' ]) && $options[ $name_prefix . 'answers_gap' ] != '') ? absint ( intval( $options[ $name_prefix . 'answers_gap' ] ) ) : 0;

            // Answer image size
            $survey_answers_image_size = (isset($options[ $name_prefix . 'answers_image_size' ]) && $options[ $name_prefix . 'answers_image_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'answers_image_size' ] ) ) : 195;

        // =========== Answers Styles End   ===========


        // =========== Buttons Styles Start ===========

            // Buttons background color
            $survey_buttons_bg_color = (isset($options[ $name_prefix . 'buttons_bg_color' ]) && $options[ $name_prefix . 'buttons_bg_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'buttons_bg_color' ] ) ) : '#fff';
            
            // Buttons size
            $survey_buttons_size = (isset($options[ $name_prefix . 'buttons_size' ]) && $options[ $name_prefix . 'buttons_size' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'buttons_size' ] ) ) : 'medium';

            // Buttons font size
            $survey_buttons_font_size = (isset($options[ $name_prefix . 'buttons_font_size' ]) && $options[ $name_prefix . 'buttons_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_font_size' ] ) ) : 14;

            // Buttons mobile font size
            $survey_buttons_mobile_font_size = (isset($options[ $name_prefix . 'buttons_mobile_font_size' ]) && $options[ $name_prefix . 'buttons_mobile_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_mobile_font_size' ] ) ) : 14;

            // Buttons Left / Right padding
            $survey_buttons_left_right_padding = (isset($options[ $name_prefix . 'buttons_left_right_padding' ]) && $options[ $name_prefix . 'buttons_left_right_padding' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_left_right_padding' ] ) ) : 24;

            // Buttons Top / Bottom padding
            $survey_buttons_top_bottom_padding = (isset($options[ $name_prefix . 'buttons_top_bottom_padding' ]) && $options[ $name_prefix . 'buttons_top_bottom_padding' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_top_bottom_padding' ] ) ) : 0;

            // Buttons border radius
            $survey_buttons_border_radius = (isset($options[ $name_prefix . 'buttons_border_radius' ]) && $options[ $name_prefix . 'buttons_border_radius' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_border_radius' ] ) ) : 4;

            // Buttons alignment
            $survey_buttons_alignment = (isset($options[ $name_prefix . 'buttons_alignment' ]) && $options[ $name_prefix . 'buttons_alignment' ] != '') ? esc_attr( $options[ $name_prefix . 'buttons_alignment' ] ) : 'left';
            
            // Buttons top distance
            $survey_buttons_top_distance = (isset($options[ $name_prefix . 'buttons_top_distance' ]) && $options[ $name_prefix . 'buttons_top_distance' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_top_distance' ] ) ) : 10;
           
        // ===========  Buttons Styles End  ===========


    // =============================================================
    // ======================    Styles Tab    =====================
    // ========================     END     ========================

        $survey_colors = "<style id='ays-survey-custom-css-additional'>
            #ays-survey-form .ays-survey-section-head-wrap .ays-survey-section-head {
                border-top-color: ".$survey_color.";
            }
            #ays-survey-form .ays-survey-section-head-wrap .ays-survey-section-head-top .ays-survey-section-counter {
                background-color: ".$survey_color.";
            }
            #ays-survey-form .ays-survey-input:focus ~ .ays-survey-input-underline-animation {
                background-color: ".$survey_color.";
            }
            #ays-survey-form .dropdown-item:hover,
            #ays-survey-form .dropdown-item:focus {
                background-color: ".$survey_color."29;
            }
            #ays-survey-form .dropdown-item:active {
                background-color: ".$survey_color.";
            }
            #ays-survey-form .switch-checkbox-thumb {
                border-color: ".$survey_color.";
            }
        </style>";

    // =======================  //  ======================= // ======================= // ======================= // ======================= //

    // =============================================================
    // ======================  Settings Tab  =======================
    // ========================    START   =========================

        // Show survey title
        $options[ $name_prefix . 'show_title' ] = isset($options[ $name_prefix . 'show_title' ]) ? $options[ $name_prefix . 'show_title' ] : 'on';
        $survey_show_title = (isset($options[ $name_prefix . 'show_title' ]) && $options[ $name_prefix . 'show_title' ] == 'on') ? true : false;

        // Show survey section header
        $options[ $name_prefix . 'show_section_header' ] = isset($options[ $name_prefix . 'show_section_header' ]) ? $options[ $name_prefix . 'show_section_header' ] : 'on';
        $survey_show_section_header = (isset($options[ $name_prefix . 'show_section_header' ]) && $options[ $name_prefix . 'show_section_header' ] == 'on') ? true : false;

        // Enable randomize answers
        $options[ $name_prefix . 'enable_randomize_answers' ] = isset($options[ $name_prefix . 'enable_randomize_answers' ]) ? $options[ $name_prefix . 'enable_randomize_answers' ] : 'off';
        $survey_enable_randomize_answers = (isset($options[ $name_prefix . 'enable_randomize_answers' ]) && $options[ $name_prefix . 'enable_randomize_answers' ] == 'on') ? true : false;

        // Enable randomize questions
        $options[ $name_prefix . 'enable_randomize_questions' ] = isset($options[ $name_prefix . 'enable_randomize_questions' ]) ? $options[ $name_prefix . 'enable_randomize_questions' ] : 'off';
        $survey_enable_randomize_questions = (isset($options[ $name_prefix . 'enable_randomize_questions' ]) && $options[ $name_prefix . 'enable_randomize_questions' ] == 'on') ? true : false;

        // Enable previous button
        $options[ $name_prefix . 'enable_previous_button' ] = isset($options[ $name_prefix . 'enable_previous_button' ]) ? $options[ $name_prefix . 'enable_previous_button' ] : 'off';
        $survey_enable_previous_button = (isset($options[ $name_prefix . 'enable_previous_button' ]) && $options[ $name_prefix . 'enable_previous_button' ] == 'on') ? true : false;

        // Enable clear answer button
        $options[ $name_prefix . 'enable_clear_answer' ] = isset($options[ $name_prefix . 'enable_clear_answer' ]) ? $options[ $name_prefix . 'enable_clear_answer' ] : 'off';
        $survey_enable_clear_answer = (isset($options[ $name_prefix . 'enable_clear_answer' ]) && $options[ $name_prefix . 'enable_clear_answer' ] == 'on') ? true : false;

        // Allow HTML in answers
        $options[ $name_prefix . 'allow_html_in_answers' ] = isset($options[ $name_prefix . 'allow_html_in_answers' ]) ? $options[ $name_prefix . 'allow_html_in_answers' ] : 'off';
        $survey_allow_html_in_answers = (isset($options[ $name_prefix . 'allow_html_in_answers' ]) && $options[ $name_prefix . 'allow_html_in_answers' ] == 'on') ? true : false;

        // Allow HTML in section description
        $options[ $name_prefix . 'allow_html_in_section_description' ] = isset($options[ $name_prefix . 'allow_html_in_section_description' ]) ? $options[ $name_prefix . 'allow_html_in_section_description' ] : 'off';
        $survey_allow_html_in_section_description = (isset($options[ $name_prefix . 'allow_html_in_section_description' ]) && $options[ $name_prefix . 'allow_html_in_section_description' ] == 'on') ? true : false;

        // Enable confirmation box for leaving the page
        $options[ $name_prefix . 'enable_leave_page' ] = isset($options[ $name_prefix . 'enable_leave_page' ]) ? $options[ $name_prefix . 'enable_leave_page' ] : 'on';
        $survey_enable_leave_page = (isset($options[ $name_prefix . 'enable_leave_page' ]) && $options[ $name_prefix . 'enable_leave_page' ] == 'on') ? true : false;

        // Autofill information
        $options[ $name_prefix . 'enable_i_autofill' ] = isset($options[ $name_prefix . 'enable_info_autofill' ]) ? $options[ $name_prefix . 'enable_info_autofill' ] : 'off';
        $survey_info_autofill = (isset($options[ $name_prefix . 'enable_info_autofill' ]) && $options[ $name_prefix . 'enable_info_autofill' ] == 'on') ? "checked" : '';

        //---- Schedule Start  ---- //

            // Schedule the Survey
            $options[ $name_prefix . 'enable_schedule' ] = isset($options[ $name_prefix . 'enable_schedule' ]) ? $options[ $name_prefix . 'enable_schedule' ] : 'off';
            $survey_enable_schedule = (isset($options[ $name_prefix . 'enable_schedule' ]) && $options[ $name_prefix . 'enable_schedule' ] == 'on') ? true : false;

            if ($survey_enable_schedule) {
                $activateTimeVal = (isset($options[ $name_prefix . 'schedule_active' ]) && $options[ $name_prefix . 'schedule_active' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'schedule_active' ] ) ) : current_time( 'mysql' );
                $deactivateTimeVal = (isset($options[ $name_prefix . 'schedule_deactive' ]) && $options[ $name_prefix . 'schedule_deactive' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'schedule_deactive' ] ) ) : current_time( 'mysql' );

                $activateTime             = strtotime($activateTimeVal);
                $survey_schedule_active   = date('Y-m-d H:i:s', $activateTime);
                $deactivateTime           = strtotime($deactivateTimeVal);
                $survey_schedule_deactive = date('Y-m-d H:i:s', $deactivateTime);
            } else {
                $survey_schedule_active   = current_time( 'mysql' );
                $survey_schedule_deactive = current_time( 'mysql' );
            }

            // Show timer
            $options[ $name_prefix . 'schedule_show_timer' ] = isset($options[ $name_prefix . 'schedule_show_timer' ]) ? $options[ $name_prefix . 'schedule_show_timer' ] : 'off';
            $survey_schedule_show_timer = (isset($options[ $name_prefix . 'schedule_show_timer' ]) && $options[ $name_prefix . 'schedule_show_timer' ] == 'on') ? true : false;

            // Show countdown / start date
            $survey_show_timer_type = (isset($options[ $name_prefix . 'show_timer_type' ]) && $options[ $name_prefix . 'show_timer_type' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'show_timer_type' ] ) ) : 'countdown';

            // Pre start message
            $survey_schedule_pre_start_message = (isset($options[ $name_prefix . 'schedule_pre_start_message' ]) &&  $options[ $name_prefix . 'schedule_pre_start_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'schedule_pre_start_message' ] ) ) : __("The survey will be available soon!", "survey-maker");

            // Expiration message
            $survey_schedule_expiration_message = (isset($options[ $name_prefix . 'schedule_expiration_message' ]) &&  $options[ $name_prefix . 'schedule_expiration_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'schedule_expiration_message' ] ) ) : __("This survey has expired!", "survey-maker");

        //---- Schedule End  ---- //

        // Auto Numbering
        $survey_auto_numbering = (isset($options[ $name_prefix . 'auto_numbering' ]) && $options[ $name_prefix . 'auto_numbering' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'auto_numbering' ] ) ) : 'none';

        // Full screen mode
        $survey_full_screen = (isset($options["survey_full_screen_mode"]) && $options["survey_full_screen_mode"] == "on") ? "checked" : "";
        $options[ $name_prefix . 'full_screen_button_color' ] = isset($options[ $name_prefix . 'full_screen_button_color' ]) ? $options[ $name_prefix . 'full_screen_button_color' ] : $options[ $name_prefix . 'text_color' ];
        $survey_full_screen_button_color = (isset($options[ $name_prefix . 'full_screen_button_color' ]) && $options[ $name_prefix . 'full_screen_button_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'full_screen_button_color' ] ) ) : '#fff';

        // Survey progress bar
        $survey_enable_progress_bar = (isset($options[$name_prefix . 'enable_progress_bar']) && $options[$name_prefix . 'enable_progress_bar'] == "on") ? "checked" : "";
        $survey_hide_section_pagination_text = (isset($options[$name_prefix . 'hide_section_pagination_text']) && $options[$name_prefix . 'hide_section_pagination_text'] == "on") ? "checked" : "";
        $survey_pagination_positioning = (isset($options[$name_prefix . 'pagination_positioning']) && $options[$name_prefix . 'pagination_positioning'] != "") ? esc_attr($options[$name_prefix . 'pagination_positioning']) : "none";
        $survey_hide_section_bar = (isset($options[$name_prefix . 'hide_section_bar']) && $options[$name_prefix . 'hide_section_bar'] == "on") ? "checked" : "";
        $survey_progress_bar_text = (isset($options[$name_prefix . 'progress_bar_text']) && $options[$name_prefix . 'progress_bar_text'] != "") ? esc_attr($options[$name_prefix . 'progress_bar_text']) : "Page";
        $options[ $name_prefix . 'pagination_text_color' ] = isset($options[ $name_prefix . 'pagination_text_color' ]) ? $options[ $name_prefix . 'pagination_text_color' ] : $options[ $name_prefix . 'text_color' ];
        $survey_pagination_text_color = (isset($options[ $name_prefix . 'pagination_text_color' ]) && $options[ $name_prefix . 'pagination_text_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'pagination_text_color' ] ) ) : '#fff';
        // Survey show sections questions count
        $survey_show_sections_questions_count = (isset($options[$name_prefix . 'show_sections_questions_count']) && $options[$name_prefix . 'show_sections_questions_count'] == "on") ? "checked" : "";

        // Survey required questions message
        $options[ $name_prefix . 'required_questions_message' ] = isset($options[ $name_prefix . 'required_questions_message' ]) ? $options[ $name_prefix . 'required_questions_message' ] : 'This is a required question';
        $survey_required_questions_message = (isset($options[$name_prefix . 'required_questions_message']) && $options[$name_prefix . 'required_questions_message'] != "") ? stripslashes(esc_attr($options[$name_prefix . 'required_questions_message'])) : "";

        // Auto numbering questions
        $survey_auto_numbering_questions = (isset($options[ $name_prefix . 'auto_numbering_questions' ]) && $options[ $name_prefix . 'auto_numbering_questions' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'auto_numbering_questions' ] ) ) : 'none';


    // =============================================================
    // =================== Results Settings Tab  ===================
    // ========================    START   =========================


        // Redirect after submit
        $options[ $name_prefix . 'redirect_after_submit' ] = isset($options[ $name_prefix . 'redirect_after_submit' ]) ? $options[ $name_prefix . 'redirect_after_submit' ] : 'off';
        $survey_redirect_after_submit = (isset($options[ $name_prefix . 'redirect_after_submit' ]) && $options[ $name_prefix . 'redirect_after_submit' ] == 'on') ? true : false;

        // Redirect URL
        $survey_submit_redirect_url = (isset($options[ $name_prefix . 'submit_redirect_url' ]) && $options[ $name_prefix . 'submit_redirect_url' ] != '') ? stripslashes ( esc_url( $options[ $name_prefix . 'submit_redirect_url' ] ) ) : '';

        // Redirect delay (sec)
        $survey_submit_redirect_delay = (isset($options[ $name_prefix . 'submit_redirect_delay' ]) && $options[ $name_prefix . 'submit_redirect_delay' ] != '') ? absint ( intval( $options[ $name_prefix . 'submit_redirect_delay' ] ) ) : '';

        // Redirect in new tab
        $survey_submit_redirect_new_tab = ( isset($options[ $name_prefix . 'submit_redirect_new_tab' ]) && $options[ $name_prefix . 'submit_redirect_new_tab' ] == 'on' ) ? true  : false;

        // Enable EXIT button
        $options[ $name_prefix . 'enable_exit_button' ] = isset($options[ $name_prefix . 'enable_exit_button' ]) ? $options[ $name_prefix . 'enable_exit_button' ] : 'off';
        $survey_enable_exit_button = (isset($options[ $name_prefix . 'enable_exit_button' ]) && $options[ $name_prefix . 'enable_exit_button' ] == 'on') ? true : false;

        // Redirect URL
        $survey_exit_redirect_url = (isset($options[ $name_prefix . 'exit_redirect_url' ]) && $options[ $name_prefix . 'exit_redirect_url' ] != '') ? stripslashes ( esc_url( $options[ $name_prefix . 'exit_redirect_url' ] ) ) : '';

        // Enable restart button
        $options[ $name_prefix . 'enable_restart_button' ] = isset($options[ $name_prefix . 'enable_restart_button' ]) ? $options[ $name_prefix . 'enable_restart_button' ] : 'off';
        $survey_enable_restart_button = (isset($options[ $name_prefix . 'enable_restart_button' ]) && $options[ $name_prefix . 'enable_restart_button' ] == 'on') ? true : false;

        // Thank you message
        $ays_survey_final_result_text = (isset($options[ $name_prefix . 'final_result_text' ]) &&  $options[ $name_prefix . 'final_result_text' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'final_result_text' ] ) ) : '';
        
        // Survey main url
        $survey_main_url = (isset($options[ $name_prefix . 'main_url' ]) &&  $options[ $name_prefix . 'main_url' ] != '') ? stripslashes( esc_url( $options[ $name_prefix . 'main_url' ] ) ) : '';

        // Show questions as html
        $options[ $name_prefix . 'show_questions_as_html' ] = isset($options[ $name_prefix . 'show_questions_as_html' ]) ? $options[ $name_prefix . 'show_questions_as_html' ] : 'on';
        $survey_show_questions_as_html = (isset($options[ $name_prefix . 'show_questions_as_html' ]) && $options[ $name_prefix . 'show_questions_as_html' ] == 'on') ? true : false;

        // Select survey loader
        $survey_loader = (isset($options[ $name_prefix . 'loader' ]) && $options[ $name_prefix . 'loader' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'loader' ] ) ) : 'default';
        
        // Loader text
        $survey_loader_text = (isset($options[ $name_prefix . 'loader_text' ]) && $options[ $name_prefix . 'loader_text' ] != '') ? stripslashes(esc_attr( $options[ $name_prefix . 'loader_text' ] ))  : '';
        // Loader Gif
        $survey_loader_gif = (isset($options[ $name_prefix . 'loader_gif' ]) && $options[ $name_prefix . 'loader_gif' ] != '') ? esc_url( $options[ $name_prefix . 'loader_gif' ] )  : '';
        $survey_loader_gif_width = (isset($options[ $name_prefix . 'loader_gif_width' ]) && $options[ $name_prefix . 'loader_gif_width' ] != '') ? stripslashes(esc_attr( $options[ $name_prefix . 'loader_gif_width' ] ))  : '';

        // Social share buttons
        $survey_social_buttons   = ( isset( $options[ $name_prefix . 'social_buttons' ] ) && $options[ $name_prefix . 'social_buttons' ] == 'on' ) ? 'checked' : '';
        $survey_social_buttons_heading = ( isset( $options[ $name_prefix . 'social_buttons_heading' ] ) && $options[ $name_prefix . 'social_buttons_heading' ] != '' ) ? stripslashes( wpautop( $options[ $name_prefix . 'social_buttons_heading' ] ) ) : '';
        $survey_social_button_ln = ( isset( $options[ $name_prefix . 'social_button_ln' ] ) && $options[ $name_prefix . 'social_button_ln' ] == 'on' ) ? 'checked' : '';
        $survey_social_button_fb = ( isset( $options[ $name_prefix . 'social_button_fb' ] ) && $options[ $name_prefix . 'social_button_fb' ] == 'on' ) ? 'checked' : '';
        $survey_social_button_tr = ( isset( $options[ $name_prefix . 'social_button_tr' ] ) && $options[ $name_prefix . 'social_button_tr' ] == 'on' ) ? 'checked' : '';
        $survey_social_button_vk = ( isset( $options[ $name_prefix . 'social_button_vk' ] ) && $options[ $name_prefix . 'social_button_vk' ] == 'on' ) ? 'checked' : '';
        


    // =============================================================
    // =================== Results Settings Tab  ===================
    // ========================    END    ==========================

    // =======================  //  ======================= // ======================= // ======================= // ======================= //

    // =============================================================
    // ===================    Limitation Tab     ===================
    // ========================    START   =========================

        // Maximum number of attempts per user
        $options[ $name_prefix . 'limit_users' ] = isset($options[ $name_prefix . 'limit_users' ]) ? $options[ $name_prefix . 'limit_users' ] : 'off';
        $survey_limit_users = (isset($options[ $name_prefix . 'limit_users' ]) && $options[ $name_prefix . 'limit_users' ] == 'on') ? true : false;

        // Detects users by IP / ID
        $survey_limit_users_by = (isset($options[ $name_prefix . 'limit_users_by' ]) && $options[ $name_prefix . 'limit_users_by' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'limit_users_by' ] ) ) : 'ip';

        // Attempts count
        $survey_max_pass_count = (isset($options[ $name_prefix . 'max_pass_count' ]) && $options[ $name_prefix . 'max_pass_count' ] != '') ? absint ( intval( $options[ $name_prefix . 'max_pass_count' ] ) ) : 1;

        // Limitation Message
        $survey_limitation_message = (isset($options[ $name_prefix . 'limitation_message' ]) &&  $options[ $name_prefix . 'limitation_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'limitation_message' ] ) ) : '';

        // Redirect Url
        $survey_redirect_url = (isset($options[ $name_prefix . 'redirect_url' ]) && $options[ $name_prefix . 'redirect_url' ] != '') ?  $options[ $name_prefix . 'redirect_url' ]  : '';

        // Redirect Delay
        $survey_redirect_delay = (isset($options[ $name_prefix . 'redirect_delay' ]) && $options[ $name_prefix . 'redirect_delay' ] != '') ? absint ( intval( $options[ $name_prefix . 'redirect_delay' ] ) ) : 0;

        // Only for logged in users
        $options[ $name_prefix . 'enable_logged_users' ] = isset($options[ $name_prefix . 'enable_logged_users' ]) ? $options[ $name_prefix . 'enable_logged_users' ] : 'off';
        $survey_enable_logged_users = (isset($options[ $name_prefix . 'enable_logged_users' ]) && $options[ $name_prefix . 'enable_logged_users' ] == 'on') ? true : false;

        // Message - Only for logged in users
        $survey_logged_in_message = (isset($options[ $name_prefix . 'logged_in_message' ]) &&  $options[ $name_prefix . 'logged_in_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'logged_in_message' ] ) ) : '';

        // Show loged in form
        $options[ $name_prefix . 'show_login_form' ] = isset($options[ $name_prefix . 'show_login_form' ]) ? $options[ $name_prefix . 'show_login_form' ] : 'off';
        $survey_show_login_form = (isset($options[ $name_prefix . 'show_login_form' ]) && $options[ $name_prefix . 'show_login_form' ] == 'on') ? true : false;

        //limitation takers count
        $options[ $name_prefix . 'enable_takers_count' ] = (isset( $options[ $name_prefix . 'enable_takers_count' ] ) && $options[ $name_prefix . 'enable_takers_count' ] == 'on') ? stripslashes ( $options[ $name_prefix . 'enable_takers_count' ] ) : 'off';
        $enable_takers_count = (isset($options[ $name_prefix . 'enable_takers_count' ]) && $options[ $name_prefix . 'enable_takers_count' ] == 'on') ? true : false;

        //Takers Count
        $survey_takers_count = (isset($options[ $name_prefix . 'takers_count' ]) && $options[ $name_prefix . 'takers_count' ] != '') ? absint ( intval( $options[ $name_prefix . 'takers_count' ] ) ) : 1;

    // =============================================================
    // ===================    Limitation Tab     ===================
    // ========================    END    ==========================

    // =======================  //  ======================= // ======================= // ======================= // ======================= //

    // =============================================================
    // =====================    E-Mail Tab     =====================
    // ========================    START   =========================


        // Send Mail To User
        $options[ $name_prefix . 'enable_mail_user' ] = isset($options[ $name_prefix . 'enable_mail_user' ]) ? $options[ $name_prefix . 'enable_mail_user' ] : 'off';
        $survey_enable_mail_user = (isset($options[ $name_prefix . 'enable_mail_user' ]) && $options[ $name_prefix . 'enable_mail_user' ] == 'on') ? true : false;

        // Email message
        $survey_mail_message = (isset($options[ $name_prefix . 'mail_message' ]) &&  $options[ $name_prefix . 'mail_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'mail_message' ] ) ) : '';

        // Send email to admin
        $options[ $name_prefix . 'enable_mail_admin' ] = isset($options[ $name_prefix . 'enable_mail_admin' ]) ? $options[ $name_prefix . 'enable_mail_admin' ] : 'off';
        $survey_enable_mail_admin = (isset($options[ $name_prefix . 'enable_mail_admin' ]) && $options[ $name_prefix . 'enable_mail_admin' ] == 'on') ? true : false;

        // Send email to site admin ( SuperAdmin )
        $options[ $name_prefix . 'send_mail_to_site_admin' ] = isset($options[ $name_prefix . 'send_mail_to_site_admin' ]) ? $options[ $name_prefix . 'send_mail_to_site_admin' ] : 'on';
        $survey_send_mail_to_site_admin = (isset($options[ $name_prefix . 'send_mail_to_site_admin' ]) && $options[ $name_prefix . 'send_mail_to_site_admin' ] == 'on') ? true : false;

        // Additional emails
        $survey_additional_emails = (isset($options[ $name_prefix . 'additional_emails' ]) && $options[ $name_prefix . 'additional_emails' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'additional_emails' ] ) ) : '';

        // Email message
        $survey_mail_message_admin = (isset($options[ $name_prefix . 'mail_message_admin' ]) &&  $options[ $name_prefix . 'mail_message_admin' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'mail_message_admin' ] ) ) : '';

        //---- Email configuration Start  ---- //

            // From email
            $survey_email_configuration_from_email = (isset($options[ $name_prefix . 'email_configuration_from_email' ]) &&  $options[ $name_prefix . 'email_configuration_from_email' ] != '') ? stripslashes( sanitize_email( $options[ $name_prefix . 'email_configuration_from_email' ] ) ) : '';

            // From name
            $survey_email_configuration_from_name = (isset($options[ $name_prefix . 'email_configuration_from_name' ]) && $options[ $name_prefix . 'email_configuration_from_name' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'email_configuration_from_name' ] ) ) : '';

            // Subject
            $survey_email_configuration_from_subject = (isset($options[ $name_prefix . 'email_configuration_from_subject' ]) && $options[ $name_prefix . 'email_configuration_from_subject' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'email_configuration_from_subject' ] ) ) : '';

            // Reply to email
            $survey_email_configuration_replyto_email = (isset($options[ $name_prefix . 'email_configuration_replyto_email' ]) &&  $options[ $name_prefix . 'email_configuration_replyto_email' ] != '') ? stripslashes( sanitize_email( $options[ $name_prefix . 'email_configuration_replyto_email' ] ) ) : '';

            // Reply to name
            $survey_email_configuration_replyto_name = (isset($options[ $name_prefix . 'email_configuration_replyto_name' ]) && $options[ $name_prefix . 'email_configuration_replyto_name' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'email_configuration_replyto_name' ] ) ) : '';

        //---- Email configuration End ---- //


    // =============================================================
    // =====================    E-Mail Tab     =====================
    // ========================    END    ==========================

$next_survey_id = "";
if ( isset( $id ) && !is_null( $id ) ) {
    $next_survey = $this->get_next_or_prev_survey_by_id( $id, "next" );
    $next_survey_id = (isset( $next_survey['id'] ) && $next_survey['id'] != "") ? absint( $next_survey['id'] ) : null;

    $prev_survey = $this->get_next_or_prev_survey_by_id( $id, "prev" );
    $prev_survey_id = (isset( $prev_survey['id'] ) && $prev_survey['id'] != "") ? absint( $prev_survey['id'] ) : null;

}

$get_all_surveys = Survey_Maker_Data::get_surveys('DESC');

