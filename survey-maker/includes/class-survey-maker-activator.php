<?php
global $ays_survey_db_version;
$ays_survey_db_version = '1.0.1';

/**
 * Fired during plugin activation
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 * @author     Survey Maker team <info@ays-pro.com>
 */
class Survey_Maker_Activator {

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    private static function activate() {
        global $wpdb;
        global $ays_survey_db_version;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $installed_ver = get_option( "ays_survey_db_version" );
        $surveys_table                  = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'surveys';
        $questions_table                = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'questions';
        $sections_table                 = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'sections';
        $survey_categories_table        = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'survey_categories';
        $question_categories_table      = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'question_categories';
        $answers_table                  = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'answers';
        $submissions_table              = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'submissions';
        $submissions_questions_table    = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'submissions_questions';
        $settings_table                 = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'settings';
        $charset_collate = $wpdb->get_charset_collate();

        if($installed_ver != $ays_survey_db_version)  {

            $sql = "CREATE TABLE `".$surveys_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `author_id` INT(16) UNSIGNED NOT NULL DEFAULT '0',
                `title` TEXT NOT NULL,
                `description` TEXT NOT NULL DEFAULT '',
                `category_ids` TEXT NOT NULL DEFAULT '',
                `question_ids` TEXT NOT NULL DEFAULT '',
                `section_ids` TEXT NOT NULL DEFAULT '',
                `sections_count` INT(11) NOT NULL DEFAULT '0',
                `questions_count` INT(11) NOT NULL DEFAULT '0',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `image` TEXT NOT NULL DEFAULT '',
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `trash_status` VARCHAR(256) NOT NULL DEFAULT '',
                `ordering` INT(16) NOT NULL,
                `post_id` INT(16) UNSIGNED DEFAULT NULL,
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$surveys_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            $sql = "CREATE TABLE `".$questions_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `author_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `section_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `category_ids` TEXT NOT NULL DEFAULT '',
                `question` TEXT NOT NULL DEFAULT '',
                `type` VARCHAR(256) NOT NULL DEFAULT '',
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `trash_status` VARCHAR(256) NOT NULL DEFAULT '',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `user_variant` TEXT NULL DEFAULT '',
                `user_explanation` TEXT NULL DEFAULT '',
                `image` TEXT NOT NULL DEFAULT '',
                `ordering` INT(11) NOT NULL DEFAULT '1',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$questions_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            $sql = "CREATE TABLE `".$survey_categories_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(256) NOT NULL DEFAULT '',
                `description` TEXT NOT NULL DEFAULT '',
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `trash_status` VARCHAR(256) NOT NULL DEFAULT '',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$survey_categories_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }
 
            $sql = "CREATE TABLE `".$sections_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(256) NOT NULL DEFAULT '',
                `description` TEXT NOT NULL DEFAULT '',
                `ordering` INT(11) NOT NULL DEFAULT '1',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$sections_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            $sql = "CREATE TABLE `".$question_categories_table."` (
                `id` INT(16) UNSIGNED NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(256) NOT NULL DEFAULT '',
                `description` TEXT NOT NULL DEFAULT '',
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `trash_status` VARCHAR(256) NOT NULL DEFAULT '',
                `date_created` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `date_modified` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$question_categories_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            $sql = "CREATE TABLE `".$answers_table."` (
                `id` INT(150) UNSIGNED NOT NULL AUTO_INCREMENT,
                `question_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `answer` TEXT NOT NULL DEFAULT '',
                `image` TEXT NOT NULL DEFAULT '',
                `ordering` INT(11) NOT NULL DEFAULT '1',
                `placeholder` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$answers_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            $sql = "CREATE TABLE `".$submissions_table."` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `survey_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `questions_ids` TEXT NOT NULL DEFAULT '',
                `user_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `user_ip` VARCHAR(256) NOT NULL DEFAULT '',
                `user_name` TEXT NOT NULL DEFAULT '',
                `user_email` TEXT NOT NULL DEFAULT '',
                `start_date` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `end_date` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `submission_date` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00',
                `duration` VARCHAR(256) NOT NULL DEFAULT '0',
                `questions_count` VARCHAR(256) NOT NULL DEFAULT '0',
                `unique_code` VARCHAR(256) NOT NULL DEFAULT '',
                `read` tinyint(3) NOT NULL DEFAULT 0,
                `status` VARCHAR(256) NOT NULL DEFAULT 'published',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$submissions_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            $sql = "CREATE TABLE `".$submissions_questions_table."` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `submission_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `question_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `section_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `survey_id` INT(11) UNSIGNED NOT NULL DEFAULT '0',
                `user_id` INT(11) NOT NULL DEFAULT '0',
                `answer_id` INT(11) NOT NULL DEFAULT '0',
                `user_answer` TEXT NOT NULL DEFAULT '',
                `user_variant` TEXT NOT NULL DEFAULT '',
                `user_explanation` TEXT NOT NULL DEFAULT '',
                `type` TEXT NOT NULL DEFAULT '',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$submissions_questions_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            $sql = "CREATE TABLE `".$settings_table."` (
                `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                `meta_key` TEXT NOT NULL DEFAULT '',
                `meta_value` TEXT NOT NULL DEFAULT '',
                `note` TEXT NOT NULL DEFAULT '',
                `options` TEXT NOT NULL DEFAULT '',
                PRIMARY KEY (`id`)
            )$charset_collate;";

            $sql_schema = "SELECT * FROM INFORMATION_SCHEMA.TABLES
                           WHERE table_schema = '".DB_NAME."' AND table_name = '".$settings_table."' ";
            $results = $wpdb->get_results($sql_schema);

            if(empty($results)){
                $wpdb->query( $sql );
            }else{
                dbDelta( $sql );
            }

            update_option( 'ays_survey_db_version', $ays_survey_db_version );
            
            $survey_categories = $wpdb->get_var( "SELECT COUNT(*) FROM " . $survey_categories_table );            
            if( intval($survey_categories) == 0 ){
                $wpdb->query("TRUNCATE TABLE `{$survey_categories_table}`");
                $wpdb->insert( $survey_categories_table, array(
                    'title' => 'Uncategorized',
                    'description' => '',
                    'status' => 'published',
                    'date_created' => current_time( 'mysql' ),
                    'date_modified' => current_time( 'mysql' ),
                ) );
            }

            $question_categories = $wpdb->get_var( "SELECT COUNT(*) FROM " . $question_categories_table );            
            if( intval($question_categories) == 0 ){
                $wpdb->query("TRUNCATE TABLE `{$question_categories_table}`");
                $wpdb->insert( $question_categories_table, array(
                    'title' => 'Uncategorized',
                    'description' => '',
                    'status' => 'published',
                    'date_created' => current_time( 'mysql' ),
                    'date_modified' => current_time( 'mysql' ),
                ) );
            }

            $survey_questions = $wpdb->get_var("SELECT COUNT(*) FROM " . $questions_table);
            $surveys_count    = $wpdb->get_var("SELECT COUNT(*) FROM " . $surveys_table);
            $sections_count   = $wpdb->get_var("SELECT COUNT(*) FROM " . $sections_table);
            if($survey_questions == 0 && $surveys_count == 0 && $sections_count == 0){
                $user_id = get_current_user_id();
                $default_options = array(
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
            
                    'survey_question_font_size' => 16,
                    'survey_question_image_width' => '',
                    'survey_question_title_alignment' => 'left',
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
                    'survey_answers_view' => 'grid',                    
                    'survey_answers_view_alignment' => 'flex-start',
                    'survey_answers_object_fit' => 'cover',
                    'survey_answers_padding' => 8,
                    'survey_answers_gap' => 0,
            
                    'survey_buttons_size' => 'medium',
                    'survey_buttons_font_size' => 14,
                    'survey_buttons_left_right_padding' => 20,
                    'survey_buttons_top_bottom_padding' => 0,
                    'survey_buttons_border_radius' => 3,                    
                    'survey_buttons_alignment' => 'left',
                    'survey_buttons_top_distance' => 10,
                    // Settings Tab
                    'survey_show_title' => 'off',
                    'survey_show_section_header' => 'on',
                    'survey_enable_randomize_answers' => 'off',
                    'survey_enable_randomize_questions' => 'off',
                    'survey_enable_clear_answer' => 'on',
                    'survey_enable_previous_button' => 'on',
                    'survey_allow_html_in_answers' => 'off',
                    'survey_enable_leave_page' => 'on',
                    'survey_enable_info_autofill' => 'off',
                    'survey_enable_schedule' => 'off',
                    'survey_schedule_active' => current_time( 'mysql' ),
                    'survey_schedule_deactive' => current_time( 'mysql' ),
                    'survey_schedule_show_timer' => 'off',
                    'survey_show_timer_type' => 'countdown',
                    'survey_schedule_pre_start_message' =>  __("The survey will be available soon!", "survey-maker"),
                    'survey_schedule_expiration_message' => __("This survey has expired!", "survey-maker"),
                    'survey_auto_numbering' => 'none',            
                    // Result Settings Tab
                    'survey_redirect_after_submit' => 'off',
                    'survey_submit_redirect_url' => '',
                    'survey_submit_redirect_delay' => '',
                    'survey_submit_redirect_new_tab' => 'off',
                    'survey_enable_exit_button' => 'off',
                    'survey_exit_redirect_url' => '',
                    'survey_enable_restart_button' => 'on',
                    'survey_final_result_text' => '',
                    'survey_show_questions_as_html' => 'on',
                    'survey_loader' => 'ripple',            
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
                // Survey
                $survey_default = array(
                    array(
                        'author_id'       => $user_id,
                        'title'           => 'Customer Satisfaction Survey Template',
                        'description'     => '',
                        'category_ids'    => '1',
                        'question_ids'    => '1,2,3,4,5,6,7',
                        'section_ids'     => '1,2,3,4',
                        'sections_count'  => '4',
                        'questions_count' => '7',
                        'date_created'    => current_time( 'mysql' ),
                        'date_modified'   => current_time( 'mysql' ),
                        'image'    => '',
                        'status'   => 'published',
                        'ordering' => '1',
                        'post_id'  => '0',
                        'options'  => json_encode($default_options),
                    )
                );
                // Sections 
                $sections_default = array(
                    array(
                        array(
                            'title'         => "Customer Satisfaction Survey Template",
                            'description'   => "Please help us improve our products/services by completing this questionnaire.",
                            'ordering'      => 1,
                            'options'       => json_encode( array("collapses" => "expanded") ),
                        ),
                        array(
                            'title'         => "Part 2/4: Service/Product Assessment",
                            'description'   => "",
                            'ordering'      => 2,
                            'options'       => json_encode( array("collapses" => "expanded") ),
                        ),
                        array(
                            'title'         => "Part 3/4:Customer Care",
                            'description'   => "",
                            'ordering'      => 3,
                            'options'       => json_encode( array("collapses" => "expanded") ),
                        ),
                        array(
                            'title'         => "Part 4/4: Additional Feedback",
                            'description'   => "",
                            'ordering'      => 4,
                            'options'       => json_encode( array("collapses" => "expanded") ),
                        ),
                    )
                );
                // Questions options 
                $question_options = array(
                    'required'  => "off",
                    'collapsed' => "expanded",
                    'enable_max_selection_count' => "off",
                    'max_selection_count' => "",
                    'min_selection_count' => "",
                    'with_editor' => "off",
                );

                $question_options_req_on = array(
                    'required'  => "on",
                    'collapsed' => "expanded",
                    'enable_max_selection_count' => "off",
                    'max_selection_count' => "",
                    'min_selection_count' => "",
                    'with_editor' => "off",
                );

                // Questions
                $questions_default = array(
                    array(
                        array(
                            'author_id'         => $user_id,
                            'section_id'        => 1,
                            'category_ids'      => "1",
                            'question'          => "Would you recommend this company to a friend or colleague?",
                            'type'              => "yesorno",
                            'status'            => "published",
                            'trash_status'      => "",
                            'date_created'      => current_time( 'mysql' ),
                            'date_modified'     => current_time( 'mysql' ),
                            'user_variant'      => "off",
                            'user_explanation'  => "",
                            'image'             => "",
                            'ordering'          => 1,
                            'options'           => json_encode($question_options),
                        ),
                        array(
                            'author_id'         => $user_id,
                            'section_id'        => 1,
                            'category_ids'      => "1",
                            'question'          => "Overall, how satisfied or dissatisfied are you with our company?",
                            'type'              => "radio",
                            'status'            => "published",
                            'trash_status'      => "",
                            'date_created'      => current_time( 'mysql' ),
                            'date_modified'     => current_time( 'mysql' ),
                            'user_variant'      => "off",
                            'user_explanation'  => "",
                            'image'             => "",
                            'ordering'          => 2,
                            'options'           => json_encode($question_options),
                        ),
                        array(
                            'author_id'         => $user_id,
                            'section_id'        => 2,
                            'category_ids'      => "1",
                            'question'          => "Which of the following words would you use to describe our products/services? Select all that apply.",
                            'type'              => "checkbox",
                            'status'            => "published",
                            'trash_status'      => "",
                            'date_created'      => current_time( 'mysql' ),
                            'date_modified'     => current_time( 'mysql' ),
                            'user_variant'      => "on",
                            'user_explanation'  => "",
                            'image'             => "",
                            'ordering'          => 1,
                            'options'           => json_encode($question_options),
                        ),
                        array(
                            'author_id'         => $user_id,
                            'section_id'        => 2,
                            'category_ids'      => "1",
                            'question'          => "How would you rate the quality of the website? (from 1 to 10)",
                            'type'              => "number",
                            'status'            => "published",
                            'trash_status'      => "",
                            'date_created'      => current_time( 'mysql' ),
                            'date_modified'     => current_time( 'mysql' ),
                            'user_variant'      => "off",
                            'user_explanation'  => "",
                            'image'             => "",
                            'ordering'          => 2,
                            'options'           => json_encode($question_options_req_on),
                        ),
                        array(
                            'author_id'         => $user_id,
                            'section_id'        => 3,
                            'category_ids'      => "1",
                            'question'          => "How responsive have we been to your questions or concerns about our products/services?",
                            'type'              => "select",
                            'status'            => "published",
                            'trash_status'      => "",
                            'date_created'      => current_time( 'mysql' ),
                            'date_modified'     => current_time( 'mysql' ),
                            'user_variant'      => "off",
                            'user_explanation'  => "",
                            'image'             => "",
                            'ordering'          => 1,
                            'options'           => json_encode($question_options),
                        ),
                        array(
                            'author_id'         => $user_id,
                            'section_id'        => 3,
                            'category_ids'      => "1",
                            'question'          => "At what email address would you like to be contacted?",
                            'type'              => "email",
                            'status'            => "published",
                            'trash_status'      => "",
                            'date_created'      => current_time( 'mysql' ),
                            'date_modified'     => current_time( 'mysql' ),
                            'user_variant'      => "off",
                            'user_explanation'  => "",
                            'image'             => "",
                            'ordering'          => 1,
                            'options'           => json_encode($question_options),
                        ),
                        array(
                            'author_id'         => $user_id,
                            'section_id'        => 4,
                            'category_ids'      => "1",
                            'question'          => "Do you have any other comments, questions, or concerns?",
                            'type'              => "text",
                            'status'            => "published",
                            'trash_status'      => "",
                            'date_created'      => current_time( 'mysql' ),
                            'date_modified'     => current_time( 'mysql' ),
                            'user_variant'      => "off",
                            'user_explanation'  => "",
                            'image'             => "",
                            'ordering'          => 2,
                            'options'           => json_encode($question_options),
                        ),
                    )
                );

                $answers_default = array(
                    array(
                        // question 1
                        array(
                            'question_id'       => 1,
                            'answer'            => "Yes",
                            'image'             => "",
                            'ordering'          => 1,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 1,
                            'answer'            => "No",
                            'image'             => "",
                            'ordering'          => 2,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 1,
                            'answer'            => "Maybe",
                            'image'             => "",
                            'ordering'          => 3,
                            'placeholder'       => "",
                        ),
                        // question 2
                        array(
                            'question_id'       => 2,
                            'answer'            => "Very satisfied",
                            'image'             => "",
                            'ordering'          => 1,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 2,
                            'answer'            => "Somewhat satisfied",
                            'image'             => "",
                            'ordering'          => 2,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 2,
                            'answer'            => "Somewhat dissatisfied",
                            'image'             => "",
                            'ordering'          => 3,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 2,
                            'answer'            => "Very dissatisfied",
                            'image'             => "",
                            'ordering'          => 4,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 2,
                            'answer'            => "Neither satisfied nor dissatisfied",
                            'image'             => "",
                            'ordering'          => 5,
                            'placeholder'       => "",
                        ),
                        // question 3
                        array(
                            'question_id'       => 3,
                            'answer'            => "Reliable",
                            'image'             => "",
                            'ordering'          => 1,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 3,
                            'answer'            => "High quality",
                            'image'             => "",
                            'ordering'          => 2,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 3,
                            'answer'            => "Useful",
                            'image'             => "",
                            'ordering'          => 3,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 3,
                            'answer'            => "Good value for money",
                            'image'             => "",
                            'ordering'          => 4,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 3,
                            'answer'            => "Unique",
                            'image'             => "",
                            'ordering'          => 5,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 3,
                            'answer'            => "Overpriced",
                            'image'             => "",
                            'ordering'          => 6,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 3,
                            'answer'            => "Ineffective",
                            'image'             => "",
                            'ordering'          => 7,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 3,
                            'answer'            => "Poor quality",
                            'image'             => "",
                            'ordering'          => 8,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 3,
                            'answer'            => "Unreliable",
                            'image'             => "",
                            'ordering'          => 9,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 3,
                            'answer'            => "Impractical",
                            'image'             => "",
                            'ordering'          => 10,
                            'placeholder'       => "",
                        ),
                        // question 4 is a short type
                        // question 5
                        array(
                            'question_id'       => 5,
                            'answer'            => "Extremely responsive",
                            'image'             => "",
                            'ordering'          => 1,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 5,
                            'answer'            => "Very responsive",
                            'image'             => "",
                            'ordering'          => 2,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 5,
                            'answer'            => "Somewhat responsive",
                            'image'             => "",
                            'ordering'          => 3,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 5,
                            'answer'            => "Not so responsive",
                            'image'             => "",
                            'ordering'          => 4,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 5,
                            'answer'            => "Not at all responsive",
                            'image'             => "",
                            'ordering'          => 5,
                            'placeholder'       => "",
                        ),
                        array(
                            'question_id'       => 5,
                            'answer'            => "Not applicable",
                            'image'             => "",
                            'ordering'          => 6,
                            'placeholder'       => "",
                        ),
                        // question 6 is an email type
                        // question 7 is a paragraph type
                    )
                );
                foreach($survey_default as $key => $survey){
                    foreach($sections_default[$key] as $section_key => $section){
                        $wpdb->insert($sections_table, $section);
                    }
                    foreach($questions_default[$key] as $section_key => $section){
                        $wpdb->insert($questions_table, $section);
                    }
                    foreach($answers_default[$key] as $answer_key => $answer){
                        $wpdb->insert($answers_table, $answer);
                    }
                    $wpdb->insert($surveys_table, $survey);
                }
            }
        }
        
        $metas = array(
            "user_roles",
            "mailchimp",
            "monitor",
            "slack",
            "active_camp",
            "zapier",
            "buttons_texts",
            "survey_default_options",
            "options",
            "buttons_texts"
        );
        
        foreach($metas as $meta_key){
            $meta_val = "";
            if($meta_key == "user_roles"){
                $meta_val = json_encode(array('administrator'));
            }
            $sql = "SELECT COUNT(*) FROM `".$settings_table."` WHERE `meta_key` = '". esc_sql( $meta_key ) ."'";
            $result = $wpdb->get_var($sql);
            if(intval($result) == 0){
                $result = $wpdb->insert(
                    $settings_table,
                    array(
                        'meta_key'    => $meta_key,
                        'meta_value'  => $meta_val,
                        'note'        => "",
                        'options'     => ""
                    ),
                    array( '%s', '%s', '%s', '%s' )
                );
            }
        }
        
    }

    public static function ays_survey_update_db_check() {
        global $ays_survey_db_version;
        if ( get_site_option( 'ays_survey_db_version' ) != $ays_survey_db_version ) {
            self::activate();
        }
    }

}
