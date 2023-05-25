<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/admin
 * @author     Survey Maker team <info@ays-pro.com>
 */
class Survey_Maker_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The surveys list table object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $surveys_obj    The surveys list table object.
	 */
    private $surveys_obj;

	/**
	 * The surveys categories list table object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $surveys_categories_obj    The surveys categories list table object.
	 */
    private $surveys_categories_obj;

	/**
	 * The survey questions list table object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $questions_obj    The survey questions list table object.
	 */
    private $questions_obj;

	/**
	 * The survey questions categories list table object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $question_categories_obj    The survey questions categories list table object.
	 */
    private $question_categories_obj;

	/**
	 * The survey submissions list table object.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $results_obj    The survey submissions list table object.
	 */
    private $submissions_obj;

	/**
	 * The survey questions categories list table object for each survey.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $each_result_obj    The survey submissions list table object for each survey.
	 */
    private $each_submission_obj;

	/**
	 * The settings object of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      object    $settings_obj    The settings object of this plugin.
	 */
    private $settings_obj;

	/**
	 * The capability of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $capability    The capability for users access to this plugin.
	 */
    private $capability;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);
        // $per_page_array = array(
        //     'quizes_per_page',
        //     'questions_per_page',
        //     'quiz_categories_per_page',
        //     'question_categories_per_page',
        //     'attributes_per_page',
        //     'quiz_results_per_page',
        //     'quiz_each_results_per_page',
        //     'quiz_orders_per_page',
        // );
        // foreach($per_page_array as $option_name){
        //     add_filter('set_screen_option_'.$option_name, array(__CLASS__, 'set_screen'), 10, 3);
        // }

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook_suffix ) {

        wp_enqueue_style( $this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
        
        if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_style( $this->plugin_name . '-sweetalert-css', SURVEY_MAKER_PUBLIC_URL . '/css/survey-maker-sweetalert2.min.css', array(), $this->version, 'all');
        }

        if (false === strpos($hook_suffix, $this->plugin_name))
            return;
            
        // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
        // wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
        // wp_enqueue_style( 'jquery-ui' );

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_style( $this->plugin_name . '-banner.css', plugin_dir_url(__FILE__) . 'css/banner.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-survey-maker-banner.css', plugin_dir_url(__FILE__) . 'css/survey-maker-banner.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-animate.css', plugin_dir_url(__FILE__) . 'css/animate.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-animations.css', plugin_dir_url(__FILE__) . 'css/animations.css', array(), $this->version, 'all');
        // wp_enqueue_style( $this->plugin_name . '-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), $this->version, 'all');
        
        wp_enqueue_style($this->plugin_name . '-font-awesome', SURVEY_MAKER_PUBLIC_URL . '/css/survey-maker-font-awesome.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-font-awesome-icons', plugin_dir_url(__FILE__) . 'css/ays-font-awesome.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-select2', SURVEY_MAKER_PUBLIC_URL .  '/css/survey-maker-select2.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-transition', SURVEY_MAKER_PUBLIC_URL .  '/css/transition.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-dropdown', SURVEY_MAKER_PUBLIC_URL .  '/css/dropdown.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-popup', plugin_dir_url(__FILE__) . 'css/popup.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-data-bootstrap', plugin_dir_url(__FILE__) . 'css/dataTables.bootstrap4.min.css', array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name . '-datetimepicker', plugin_dir_url(__FILE__) . 'css/jquery-ui-timepicker-addon.css', array(), $this->version, 'all');

        wp_enqueue_style( $this->plugin_name . "-general", plugin_dir_url( __FILE__ ) . 'css/survey-maker-general.css', array(), time(), 'all' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/survey-maker-admin.css', array(), time(), 'all' );
        wp_enqueue_style( $this->plugin_name . "-loaders", plugin_dir_url(__FILE__) . 'css/loaders.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook_suffix ) {
        global $wp_version;

        $version1 = $wp_version;
        $operator = '>=';
        $version2 = '5.5';
        $versionCompare = $this->aysSurveyMakerVersionCompare($version1, $operator, $version2);

        if ($versionCompare) {
            wp_enqueue_script( $this->plugin_name.'-wp-load-scripts', plugin_dir_url(__FILE__) . 'js/survey-maker-wp-load-scripts.js', array(), $this->version, true);
        }

        if (false !== strpos($hook_suffix, "plugins.php")){
            wp_enqueue_script( $this->plugin_name . '-sweetalert-js', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-sweetalert2.all.min.js', array('jquery'), $this->version, true );
            wp_enqueue_script( $this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'js/admin.js', array( 'jquery' ), $this->version, true );
            wp_localize_script( $this->plugin_name . '-admin', 'SurveyMakerAdmin', array( 
            	'ajaxUrl' => admin_url( 'admin-ajax.php' )
            ) );
        }
        
        if (false === strpos($hook_suffix, $this->plugin_name))
            return;

        $survey_banner_date = $this->ays_survey_update_banner_time();
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-effects-core' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_media();
        wp_enqueue_script( $this->plugin_name . '-color-picker-alpha', plugin_dir_url(__FILE__) . 'js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), $this->version, true );
        $color_picker_strings = array(
            'clear'            => __( 'Clear', "survey-maker" ),
            'clearAriaLabel'   => __( 'Clear color', "survey-maker" ),
            'defaultString'    => __( 'Default', "survey-maker" ),
            'defaultAriaLabel' => __( 'Select default color', "survey-maker" ),
            'pick'             => __( 'Select Color', "survey-maker" ),
            'defaultLabel'     => __( 'Color value', "survey-maker" ),
        );
        wp_localize_script( $this->plugin_name . '-color-picker-alpha', 'wpColorPickerL10n', $color_picker_strings );


		/* 
        ========================================== 
           * Bootstrap
           * select2
           * jQuery DataTables
        ========================================== 
        */
        wp_enqueue_script( $this->plugin_name . "-popper", plugin_dir_url(__FILE__) . 'js/popper.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . "-bootstrap", plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-select2js', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-select2.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-sweetalert-js', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-sweetalert2.all.min.js', array('jquery'), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-datatable-min', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-datatable.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-transition-min', SURVEY_MAKER_PUBLIC_URL . '/js/transition.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-dropdown-min', SURVEY_MAKER_PUBLIC_URL . '/js/dropdown.min.js', array('jquery'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . "-db4.min.js", plugin_dir_url( __FILE__ ) . 'js/dataTables.bootstrap4.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . "-datetimepicker", plugin_dir_url( __FILE__ ) . 'js/jquery-ui-timepicker-addon.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-autosize', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-autosize.js', array( 'jquery' ), $this->version, false );

        /* 
        ================================================
           Survey admin dashboard scripts (Google charts)
        ================================================
        */
        if ( strpos($hook_suffix, 'each-submission') !== false ) {
            wp_enqueue_script( $this->plugin_name . '-charts-google', plugin_dir_url(__FILE__) . 'js/google-chart.js', array('jquery'), $this->version, true);
            wp_enqueue_script( $this->plugin_name . '-charts', plugin_dir_url(__FILE__) . 'js/partials/survey-maker-admin-submissions-charts.js', array('jquery'), $this->version, true);
        }

        /* 
        ================================================
           Quiz admin dashboard scripts (and for AJAX)
        ================================================
        */
        wp_enqueue_script( $this->plugin_name . "-survey-styles", plugin_dir_url(__FILE__) . 'js/partials/survey-maker-admin-survey-styles.js', array('jquery', 'wp-color-picker'), $this->version, true);
        wp_enqueue_script( $this->plugin_name . "-functions", plugin_dir_url(__FILE__) . 'js/functions.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
        wp_enqueue_script( $this->plugin_name . '-ajax', plugin_dir_url(__FILE__) . 'js/survey-maker-admin-ajax.js', array('jquery'), $this->version, true);
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/survey-maker-admin.js', array( 'jquery' ), $this->version, true );
        wp_localize_script( $this->plugin_name, 'SurveyMakerAdmin', array( 
            'surveyBannerDate'    => $survey_banner_date,
        	'ajaxUrl' => admin_url( 'admin-ajax.php' ),            
            'nonce' => wp_create_nonce( 'ajax-nonce' ),
            'inputAnswerText'                    => __( 'Your answer', "survey-maker" ),
            // 'shortAnswerText'                   => __( 'Short answer text', "survey-maker" ),
            // 'numberAnswerText'                  => __( 'Number answer text', "survey-maker" ),
            'emailField'                        => __( 'Your email', "survey-maker" ),
            'nameField'                         => __( 'Your name', "survey-maker" ),
            'selectUserRoles'                   => __( 'Select user roles', "survey-maker" ),
            'addQuestion'                       => __( 'Add question', "survey-maker" ),
            'addSection'                        => __( 'Add section', "survey-maker" ),
            'duplicate'                         => __( 'Duplicate', "survey-maker" ),
            'delete'                            => __( 'Delete', "survey-maker" ),
            'addImage'                          => __( 'Add Image', "survey-maker" ),
            'editImage'                         => __( 'Edit Image', "survey-maker" ),
            'removeImage'                       => __( 'Remove Image', "survey-maker" ),
            'collapseSectionQuestions'          => __( 'Collapse section questions', "survey-maker" ),
            'expandSectionQuestions'            => __( 'Expand section questions', "survey-maker" ),
            'selectQuestionDefaultType'         => __( 'Select question default type', "survey-maker" ),
            'chooseAnswer'                      => __( 'Choose answer', "survey-maker" ),
            'yes'                               => __( 'Yes', "survey-maker" ),
            'cancel'                            => __( 'Cancel', "survey-maker" ),
            'questionDeleteConfirmation'        => __( 'Are you sure you want to delete this question?', "survey-maker" ),
            'sectionDeleteConfirmation'         => __( 'Are you sure you want to delete this section?', "survey-maker" ),
            'loadResource'                      => __( "Can't load resource.", "survey-maker" ),
            'somethingWentWrong'                => __( "Maybe something went wrong.", "survey-maker" ),
            'dataDeleted'                       => __( "Maybe the data has been deleted.", "survey-maker" ),
            'minimumCountOfQuestions'           => __( 'Sorry minimum count of questions should be 1', "survey-maker" ),
            'enableMaxSelectionCount'           => __( 'Enable selection count', "survey-maker" ),
            'enableSelectionCount'              => __( 'Enable selection count', "survey-maker" ),
            'disableSelectionCount'             => __( 'Disable selection count', "survey-maker" ),
            'enableMaxSelectionCount'           => __( 'Enable selection count', "survey-maker" ),
            'disableMaxSelectionCount'          => __( 'Disable max selection count', "survey-maker" ),
            'enableWordLimitation'              => __( 'Enable word limitation', "survey-maker" ),
            'disableWordLimitation'             => __( 'Disable word limitation', "survey-maker" ),
            'enableNumberLimitation'            => __( 'Enable limitation', "survey-maker" ),
            'disableNumberLimitation'           => __( 'Disable limitation', "survey-maker" ),
            'successfullySent'                  => __( 'Successfully sent', "survey-maker" ),
            'failed'                            => __( 'Failed', "survey-maker" ),
            'selectPage'                        => __( 'Select page', "survey-maker" ),
            'selectPostType'                    => __( 'Select post type', "survey-maker" ),
            'copied'                            => __( 'Copied!', "survey-maker"),
            'clickForCopy'                      => __( 'Click for copy', "survey-maker"),
            'moveToSection'                     => __( 'Move to section', "survey-maker"),
            'icons' => array(
                'radioButtonUnchecked' => SURVEY_MAKER_ADMIN_URL . '/images/icons/radio-button-unchecked.svg',
                'checkboxUnchecked' => SURVEY_MAKER_ADMIN_URL . '/images/icons/checkbox-unchecked.svg',
            ),
            'nextSurveyPage' => __( 'Are you sure you want to go to the next survey page?', "survey-maker"),
            'prevSurveyPage' => __( 'Are you sure you want to go to the previous question page?', "survey-maker"),
            'addQuestionImageCaption' => __( 'Add a caption', "survey-maker"),
            'closeQuestionImageCaption' => __( 'Close caption', "survey-maker"),
        ) );
        wp_localize_script($this->plugin_name . '-ajax', 'survey_maker_ajax', array(
            "emptyEmailError"   => __( 'Email field is empty', "survey-maker"),
            "invalidEmailError" => __( 'Invalid Email address', "survey-maker"),
            "emptyWebsiteError"   => __( 'Website field is empty', "survey-maker"),
            "invalidWebsiteError" => __( 'Invalid website address', "survey-maker"),
            "thankYouMessage"     => __( 'Your request was successfully submitted. We will get in touch with you until November 10. Thank you!', "survey-maker"),
        ));

    }
    
    public function codemirror_enqueue_scripts($hook) {
        if(strpos($hook, $this->plugin_name) !== false){
            if(function_exists('wp_enqueue_code_editor')){
                $cm_settings['codeEditor'] = wp_enqueue_code_editor(array(
                    'type' => 'text/css',
                    'codemirror' => array(
                        'inputStyle' => 'contenteditable',
                        'theme' => 'cobalt',
                    )
                ));

                wp_enqueue_script('wp-theme-plugin-editor');
                wp_localize_script('wp-theme-plugin-editor', 'cm_settings', $cm_settings);

                wp_enqueue_style('wp-codemirror');
            }
        }
    }

    public function aysSurveyMakerVersionCompare($version1, $operator, $version2) {
   
        $_fv = intval ( trim ( str_replace ( '.', '', $version1 ) ) );
        $_sv = intval ( trim ( str_replace ( '.', '', $version2 ) ) );
       
        if (strlen ( $_fv ) > strlen ( $_sv )) {
            $_sv = str_pad ( $_sv, strlen ( $_fv ), 0 );
        }
       
        if (strlen ( $_fv ) < strlen ( $_sv )) {
            $_fv = str_pad ( $_fv, strlen ( $_sv ), 0 );
        }
       
        return version_compare ( ( string ) $_fv, ( string ) $_sv, $operator );
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu(){

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
        global $wpdb;
        $sql = "SELECT COUNT(*) FROM " . esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions WHERE `read` = 0 OR `read` = 2 ";
        $unread_results_count = intval( $wpdb->get_var( $sql ) );
        $menu_item = ($unread_results_count == 0) ? 'Survey Maker' : 'Survey Maker' . '<span class="ays-survey-menu-badge ays-survey-results-bage">' . $unread_results_count . '</span>';
        
        $this->capability = $this->survey_maker_capabilities();
        $capability = $this->capability;
                
        $hook_survey_maker =add_menu_page(
            'Survey Maker', 
            $menu_item,
            $this->capability,
            $this->plugin_name,
            array($this, 'display_plugin_surveys_page'), 
            SURVEY_MAKER_ADMIN_URL . '/images/icons/survey_logo.png',
            '6.21'
        );
        add_action( "load-$hook_survey_maker", array( $this, 'add_tabs' ));
    }

    public function add_plugin_surveys_submenu(){
        $hook_survey_maker = add_submenu_page(
            $this->plugin_name,
            __('Surveys', "survey-maker"),
            __('Surveys', "survey-maker"),
            $this->capability,
            $this->plugin_name,
            array($this, 'display_plugin_surveys_page')
        );

        add_action("load-$hook_survey_maker", array($this, 'screen_option_surveys'));
        add_action("load-$hook_survey_maker", array($this, 'add_tabs'));
    }

    public function add_plugin_export_import_submenu(){
        $hook_exp_imp = add_submenu_page(
            $this->plugin_name,
            __('Export / Import', "survey-maker"),
            __('Export / Import', "survey-maker"),
            $this->capability,
            $this->plugin_name . '-export-import',
            array($this, 'display_plugin_export_import_page')
        );

        // add_action("load-$hook_exp_imp", array($this, 'screen_option_questions'));        
        add_action("load-$hook_exp_imp", array($this, 'add_tabs'));
    }

    public function add_plugin_survey_categories_submenu(){
        $hook_survey_categories = add_submenu_page(
            $this->plugin_name,
            __('Survey Categories', "survey-maker"),
            __('Survey Categories', "survey-maker"),
            $this->capability,
            $this->plugin_name . '-survey-categories',
            array($this, 'display_plugin_survey_categories_page')
        );

        add_action("load-$hook_survey_categories", array($this, 'screen_option_survey_categories'));
        add_action("load-$hook_survey_categories", array($this, 'add_tabs'));
    }

    public function add_plugin_submissions_submenu(){
        global $wpdb;
        
        $sql = "SELECT COUNT(*) FROM " . esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions WHERE `read` = 0 OR `read` = 2 ";
        $unread_results_count = intval( $wpdb->get_var( $sql ) );

        $results_text = __('Submissions', "survey-maker");
        $menu_item = ( $unread_results_count == 0 ) ? $results_text : $results_text . '<span class="ays-survey-menu-badge ays-survey-results-bage">' . $unread_results_count . '</span>';

        $hook_submissions = add_submenu_page(
            $this->plugin_name,
            $results_text,
            $menu_item,
            $this->capability,
            $this->plugin_name . '-submissions',
            array($this, 'display_plugin_submissions_page')
        );

        add_action("load-$hook_submissions", array($this, 'screen_option_submissions'));
        add_action("load-$hook_submissions", array($this, 'add_tabs'));
        
        $hook_each_submission = add_submenu_page(
            'each_submission_slug',
            __('Each', "survey-maker"),
            null,
            $this->capability,
            $this->plugin_name . '-each-submission',
            array($this, 'display_plugin_each_submission_page')
        );

        add_action("load-$hook_each_submission", array($this, 'screen_option_each_survey_submission'));
        add_action("load-$hook_each_submission", array($this, 'add_tabs'));

        add_filter('parent_file', array($this,'survey_maker_select_submenu'));
    }

    public function add_plugin_dashboard_submenu(){
        $hook_quizes = add_submenu_page(
            $this->plugin_name,
            __('How to use', "survey-maker"),
            __('How to use', "survey-maker"),
            $this->capability,
            $this->plugin_name . '-dashboard',
            array($this, 'display_plugin_setup_page')
        );
        add_action("load-$hook_quizes", array($this, 'add_tabs'));
    }

    public function add_plugin_general_settings_submenu(){
        $hook_settings = add_submenu_page( $this->plugin_name,
            __('General Settings', "survey-maker"),
            __('General Settings', "survey-maker"),
            'manage_options',
            $this->plugin_name . '-settings',
            array($this, 'display_plugin_settings_page') 
        );
        add_action("load-$hook_settings", array($this, 'screen_option_settings'));
        add_action("load-$hook_settings", array($this, 'add_tabs'));
    }

    public function add_plugin_featured_plugins_submenu(){
        $hook_featured_plugins = add_submenu_page( $this->plugin_name,
            __('Our products', "survey-maker"),
            __('Our products', "survey-maker"),
            $this->capability,
            $this->plugin_name . '-our-products',
            array($this, 'display_plugin_featured_plugins_page') 
        );
        add_action("load-$hook_featured_plugins", array($this, 'add_tabs'));
    }

    public function add_plugin_survey_features_plugins_submenu(){
        $hook_pro_features = add_submenu_page( $this->plugin_name,
            __('PRO Features', "survey-maker"),
            __('PRO Features', "survey-maker"),
            $this->capability,
            $this->plugin_name . '-survey-features',
            array($this, 'display_plugin_features_page') 
        );
        add_action("load-$hook_pro_features", array($this, 'add_tabs'));
    }

    public function add_plugin_subscribe_email(){
        $hook_grab_your_gift = add_submenu_page(
            $this->plugin_name,
            __('Grab your GIFT', "survey-maker"),
            __('Grab your GIFT', "survey-maker"),
            'manage_options',
            $this->plugin_name . '-survey-subscribe-email',
            array($this, 'display_plugin_subscribe_email')
        );

        add_action("load-$hook_grab_your_gift", array( $this, 'add_tabs' ));
    }

    public function add_plugin_popup_survey(){
        $hook_popup = add_submenu_page( $this->plugin_name,
            __('Popup survey', "survey-maker"),
            __('Popup survey', "survey-maker"),
            $this->capability,
            $this->plugin_name . '-popup-survey',
            array($this, 'display_plugin_popup_page') 
        );
        add_action("load-$hook_popup", array($this, 'add_tabs'));
    }

    public function survey_maker_select_submenu($file) {
        global $plugin_page;
        if ($this->plugin_name."-each-submission" == $plugin_page) {
            $plugin_page = $this->plugin_name."-submissions";
        }
        return $file;
    }
    
    protected function survey_maker_capabilities(){
        global $wpdb;
        return 'manage_options';

        $sql = "SELECT meta_value FROM {$wpdb->prefix}aysquiz_settings WHERE `meta_key` = 'user_roles'";
        $result = $wpdb->get_var($sql);
        
        $capability = 'manage_options';
        if($result !== null){
            $ays_user_roles = json_decode($result, true);
            if(is_user_logged_in()){
                $current_user = wp_get_current_user();
                $current_user_roles = $current_user->roles;
                $ishmar = 0;
                foreach($current_user_roles as $r){
                    if(in_array($r, $ays_user_roles)){
                        $ishmar++;
                    }
                }
                if($ishmar > 0){
                    $capability = "read";
                }
            }
        }
        return $capability;
    }


    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */
    public function add_action_links($links){
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url('admin.php?page=' . $this->plugin_name) . '">' . __('Settings', "survey-maker") . '</a>',
            '<a href="https://ays-demo.com/wordpress-survey-plugin-free-demo/" target="_blank">' . __('Demo', "survey-maker") . '</a>',
            '<a href="https://ays-pro.com/wordpress/survey-maker?utm_source=dashboard&utm_medium=survey&utm_campaign=free" target="_blank" style="color:#93003C;font-weight:bold;">' . __('Go Pro', "survey-maker") . '</a>',
        );
        return array_merge($settings_link, $links);

    }

    
    public function add_survey_row_meta( $links, $file ) {
        if ( SURVEY_MAKER_BASENAME == $file ) {
            $row_meta = array(
                'ays-survey-support'       => '<a href="' . esc_url( 'https://wordpress.org/support/plugin/survey-maker/' ) . '" target="_blank">' . esc_html__( 'Free Support', "survey-maker" ) . '</a>',
                'ays-survey-documentation' => '<a href="' . esc_url( 'https://ays-pro.com/wordpress-survey-maker-user-manual' ) . '" target="_blank">' . esc_html__( 'Documentation', "survey-maker" ) . '</a>',
                'ays-survey-rate-us' => '<a href="' . esc_url( 'https://wordpress.org/support/plugin/survey-maker/reviews/?rate=5#new-post' ) . '" target="_blank">' . esc_html__( 'Rate us', "survey-maker" ) . '</a>',
                'ays-survey-rate-us' => '<a href="' . esc_url( 'https://www.youtube.com/channel/UC-1vioc90xaKjE7stq30wmA' ) . '" target="_blank">' . esc_html__( 'Video tutorial', "survey-maker" ) . '</a>',
                );

            return array_merge( $links, $row_meta );
        }
        return $links;
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_setup_page(){
        include_once('partials/survey-maker-admin-display.php');
    }

    public function display_plugin_surveys_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';
        switch ($action) {
            case 'add':
                include_once('partials/surveys/actions/survey-maker-surveys-actions.php');
                break;
            case 'edit':
                include_once('partials/surveys/actions/survey-maker-surveys-actions.php');
                break;
            default:
                include_once('partials/surveys/survey-maker-surveys-display.php');
        }
    }

    public function display_plugin_survey_categories_page(){
        $action = (isset($_GET['action'])) ? sanitize_text_field($_GET['action']) : '';

        switch ($action) {
            case 'add':
                include_once('partials/surveys/actions/survey-maker-survey-categories-actions.php');
                break;
            case 'edit':
                include_once('partials/surveys/actions/survey-maker-survey-categories-actions.php');
                break;
            default:
                include_once('partials/surveys/survey-maker-survey-categories-display.php');
        }
    }

    public function display_plugin_submissions_page(){

        include_once('partials/submissions/survey-maker-submissions-display.php');
    }
    
    public function display_plugin_each_submission_page(){
        include_once 'partials/submissions/survey-maker-each-submission-display.php';
    }
    
    public function display_plugin_settings_page(){        
        include_once('partials/settings/survey-maker-settings.php');
    }

    public function display_plugin_export_import_page(){        
        include_once('partials/export-import/survey-maker-export-import-display.php');
    }

    public function display_plugin_subscribe_email(){
        include_once('partials/subscribe/survey-maker-subscribe-email-display.php');
    }

    public function display_plugin_featured_plugins_page(){
        include_once('partials/features/survey-maker-plugin-featured-display.php');
    }
    
    public function display_plugin_features_page(){
        include_once('partials/features/survey-maker-features-display.php');
    }

    public function display_plugin_popup_page(){
        include_once('partials/popup/survey-maker-popups-display.php');
    }


    public static function set_screen($status, $option, $value){
        return $value;
    }

    public function screen_option_surveys(){
        $option = 'per_page';
        $args = array(
            'label' => __('Surveys', "survey-maker"),
            'default' => 20,
            'option' => 'surveys_per_page'
        );

        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }

        $this->surveys_obj = new Surveys_List_Table($this->plugin_name);
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_survey_categories(){
        $option = 'per_page';
        $args = array(
            'label' => __('Survey Categories', "survey-maker"),
            'default' => 20,
            'option' => 'survey_categories_per_page'
        );
        
        if( ! ( isset( $_GET['action'] ) && ( $_GET['action'] == 'add' || $_GET['action'] == 'edit' ) ) ){
            add_screen_option($option, $args);
        }

        $this->surveys_categories_obj = new Survey_Categories_List_Table($this->plugin_name);
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_questions(){
        $option = 'per_page';
        $args = array(
            'label' => __('Questions', "survey-maker"),
            'default' => 20,
            'option' => 'survey_questions_per_page'
        );

        add_screen_option($option, $args);
        $this->questions_obj = new Survey_Questions_List_Table($this->plugin_name);
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }

    public function screen_option_questions_categories(){
        $option = 'per_page';
        $args = array(
            'label' => __('Question Categories', "survey-maker"),
            'default' => 20,
            'option' => 'survey_question_categories_per_page'
        );

        add_screen_option($option, $args);
        $this->question_categories_obj = new Survey_Question_Categories_List_Table($this->plugin_name);
    }

    public function screen_option_submissions(){
        $option = 'per_page';
        $args = array(
            'label' => __('Submissions', "survey-maker"),
            'default' => 20,
            'option' => 'survey_submissions_results_per_page'
        );

        add_screen_option($option, $args);
        $this->submissions_obj = new Submissions_List_Table( $this->plugin_name );
    }

    public function screen_option_each_survey_submission() {
        $option = 'per_page';
        $args = array(
            'label' => __('Results', "survey-maker"),
            'default' => 50,
            'option' => 'survey_each_submission_results_per_page',
        );

        add_screen_option($option, $args);
        $this->each_submission_obj = new Survey_Each_Submission_List_Table($this->plugin_name);
    }
    
    public function screen_option_settings(){
        $this->settings_obj = new Survey_Maker_Settings_Actions($this->plugin_name);
    }

    public function deactivate_plugin_option(){
        $request_value = sanitize_text_field($_REQUEST['upgrade_plugin']);
        $upgrade_option = get_option( 'ays_survey_maker_upgrade_plugin', '' );
        if($upgrade_option === ''){
            add_option( 'ays_survey_maker_upgrade_plugin', $request_value );
        }else{
            update_option( 'ays_survey_maker_upgrade_plugin', $request_value );
        }
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode( array( 'option' => get_option( 'ays_survey_maker_upgrade_plugin', '' ) ) );
        wp_die();
    }

    public function survey_maker_admin_footer($a){
        if(isset($_REQUEST['page'])){
            if(false !== strpos( sanitize_text_field( $_REQUEST['page'] ), $this->plugin_name)){
                ?>
                <p style="font-size:13px;text-align:center;font-style:italic;">
                    <span style="margin-left:0px;margin-right:10px;" class="ays_heart_beat"><i class="ays_fa ays_fa_heart_o animated"></i></span>
                    <span><?php echo __( "If you love our plugin, please do big favor and rate us on", "survey-maker"); ?></span> 
                    <a target="_blank" href='https://wordpress.org/support/plugin/survey-maker/reviews/?rate=5#new-post'>WordPress.org</a>
                    <span class="ays_heart_beat"><i class="ays_fa ays_fa_heart_o animated"></i></span>
                </p>
            <?php
            }
        }
    }

    public static function ays_restriction_string($type, $x, $length){
        $output = "";
        switch($type){
            case "char":                
                if(strlen($x)<=$length){
                    $output = $x;
                } else {
                    $output = substr($x,0,$length) . '...';
                }
                break;
            case "word":
                $res = explode(" ", $x);
                if(count($res)<=$length){
                    $output = implode(" ",$res);
                } else {
                    $res = array_slice($res,0,$length);
                    $output = implode(" ",$res) . '...';
                }
            break;
        }
        return $output;
    }    
    
    public static function validateDate($date, $format = 'Y-m-d H:i:s'){
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function get_max_id( $table ) {
        global $wpdb;
        $db_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . $table;

        $sql = "SELECT MAX(id) FROM {$db_table}";

        $result = intval( $wpdb->get_var( $sql ) );

        return $result;
    }

    public function get_all_surveys(){
        global $wpdb;
        $surveys_table = $wpdb->prefix . "ayssurvey_surveys";
        $surveys = $wpdb->get_results("SELECT * FROM {$surveys_table}");
        return $surveys;
    }

    public static function string_starts_with_number($string){
        $match = preg_match('/^\d/', $string);
        if($match === 1){
            return true;
        }else{
            return false;
        }
    }

    public function get_question_answers( $question_id ) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}ayssurvey_answers WHERE question_id=" . absint( $question_id );

        $results = $wpdb->get_results( $sql, 'ARRAY_A' );
        foreach ($results as $key => &$result) {
            unset($result['id']);
            unset($result['question_id']);
        }

        return $results;
    }
    
    public function ays_survey_question_results( $survey_id, $submission_ids = null ){
        global $wpdb;

        if($survey_id === null){
            return array(
                'total_count' => 0,
                'questions' => array()
            );
        }

        $submitions_questiions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        $answer_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";
        $question_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $submitions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $survey_section_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "sections";
        $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $survey_options_sql = "SELECT options FROM {$surveys_table} WHERE id =". absint( $survey_id );
        $survey_options = $wpdb->get_var( $survey_options_sql );

        $survey_options = isset( $survey_options ) && $survey_options != '' ? json_decode( $survey_options, true ) : array();

        // Allow HTML in answers
        $survey_options[ 'survey_allow_html_in_answers' ] = isset($survey_options[ 'survey_allow_html_in_answers' ]) ? $survey_options[ 'survey_allow_html_in_answers' ] : 'off';
        $allow_html_in_answers = (isset($survey_options[ 'survey_allow_html_in_answers' ]) && $survey_options[ 'survey_allow_html_in_answers' ] == 'on') ? true : false;

        $question_ids = "SELECT question_ids FROM {$surveys_table} WHERE id =". absint( $survey_id );
        $question_ids_results = $wpdb->get_var( $question_ids );
        $ays_question_id = ($question_ids_results != '') ? $question_ids_results : null;

        if($ays_question_id == null){
            return array(
                'total_count' => 0,
                'questions' => array()
            );
        }

        $questions_ids_arr = explode(',',$ays_question_id);
        $answer_id = "SELECT a.id, a.answer, COUNT(s_q.answer_id) AS answer_count
                    FROM {$answer_table} AS a
                    LEFT JOIN {$submitions_questiions_table} AS s_q 
                    ON a.id = s_q.answer_id
                    WHERE s_q.survey_id=".absint( $survey_id ) ."
                    GROUP BY a.id";

        $answer_id_result = $wpdb->get_results($answer_id,'ARRAY_A');

        $for_checkbox = "SELECT a.id, a.answer, COUNT(s_q.answer_id) AS answer_count
                    FROM {$answer_table} AS a
                    LEFT JOIN {$submitions_questiions_table} AS s_q 
                    ON a.id = s_q.answer_id OR FIND_IN_SET( a.id, s_q.user_answer )
                    WHERE s_q.type = 'checkbox'
                    AND s_q.survey_id=".absint( $survey_id ) ."
                    GROUP BY a.id";

        $for_checkbox_result = $wpdb->get_results($for_checkbox,'ARRAY_A');

        $for_text_type = "SELECT a.id, a.answer, COUNT(s_q.id) AS answer_count
                    FROM {$answer_table} AS a
                    LEFT JOIN {$submitions_questiions_table} AS s_q 
                    ON a.id = s_q.answer_id OR FIND_IN_SET( a.id, s_q.user_answer )
                    WHERE s_q.type IN ('name', 'email', 'text', 'short_text', 'number')
                    AND s_q.survey_id=".absint( $survey_id ) ."
                    GROUP BY a.id";

        $for_text_type_result = $wpdb->get_results($for_checkbox,'ARRAY_A');
        $answer_count = array();
        $question_type = '';
        foreach ($answer_id_result as $key => $answer_count_by_id) {
            $ays_survey_answer_count = (isset($answer_count_by_id['answer_count']) && $answer_count_by_id['answer_count'] !="") ? absint(intval($answer_count_by_id['answer_count'])) : '';
            $answer_count[$answer_count_by_id['id']] = $ays_survey_answer_count;
        }

        foreach ($for_checkbox_result as $key => $answer_count_by_id) {
            $ays_survey_answer_count = (isset($answer_count_by_id['answer_count']) && $answer_count_by_id['answer_count'] !="") ? absint(intval($answer_count_by_id['answer_count'])) : '';
            $answer_count[$answer_count_by_id['id']] = $ays_survey_answer_count;
        }

        foreach ($for_text_type_result as $key => $answer_count_by_id) {
            $ays_survey_answer_count = (isset($answer_count_by_id['answer_count']) && $answer_count_by_id['answer_count'] !="") ? absint(intval($answer_count_by_id['answer_count'])) : '';
            $answer_count[$answer_count_by_id['id']] = $ays_survey_answer_count;
        }

        $question_by_ids = Survey_Maker_Data::get_question_by_ids( $questions_ids_arr );

        $select_answer_q_type = "SELECT type, user_answer, id, question_id
            FROM {$submitions_questiions_table}
            WHERE user_answer != '' 
                AND type != 'checkbox' 
                AND survey_id=". absint( $survey_id );

        $submission_answer_other = "SELECT question_id, user_variant
            FROM {$submitions_questiions_table}
            WHERE user_variant != ''
                AND survey_id=". absint( $survey_id );

        if( $submission_ids !== null ){
            if( is_array( $submission_ids ) ){
                $select_answer_q_type .= " AND submission_id IN (" . esc_sql( implode( ',', $submission_ids ) ) . ") ";
                $submission_answer_other .= " AND submission_id IN (" . esc_sql( implode( ',', $submission_ids ) ) . ") ";
            }
        }
            
        $result_answers_q_type = $wpdb->get_results($select_answer_q_type,'ARRAY_A');
        $result_answers_other = $wpdb->get_results($submission_answer_other,'ARRAY_A');
        $text_answer = array();
        foreach($result_answers_q_type as $key => $result_answer_q_type){
            $text_answer[$result_answer_q_type['type']][$result_answer_q_type['question_id']][] = $result_answer_q_type['user_answer'];
        }
        
        $other_answers = array();
        foreach($result_answers_other as $key => $result_answer_other){
            $other_answers[$result_answer_other['question_id']][] = $result_answer_other['user_variant'];
        }

        $text_types = array(
            'text',
            'short_text',
            'number',
            'name',
            'email',
        );

        //Question types different charts
        $ays_submissions_count  = array();
        $question_results = array();

        $total_count = 0;
        foreach ($question_by_ids as $key => $question) {
            $answers        = $question->answers;
            $question_id    = $question->id;
            $question_title = $question->question;
            $question_type  = $question->type;
            //questions
            $question_results[$question_id]['question_id'] = $question_id;
            $question_results[$question_id]['question'] = $question_title;
            $ays_answer = array();
            $question_answer_ids = array();
            //

            foreach ($answers as $key => $answer) {
                $answer_id    = $answer->id;
                $answer_title = $answer->answer;
                
                $ays_answer[$answer_id] = isset( $answer_count[$answer_id] ) ? $answer_count[$answer_id] : 0;
                $question_answer_ids[$answer_id] = $allow_html_in_answers ? sanitize_text_field( $answer_title ) : $answer_title;
            }
            
            //sum of submissions count per questions
            if($question_type == "checkbox"){
                $sub_checkbox_count = $this->ays_survey_get_submission_count($question->id, $question_type, $survey_id);
                $sum_of_count = $sub_checkbox_count;
            }else{
                $sum_of_count = array_sum( array_values( $ays_answer ) );
            }
            $question_results[$question_id]['otherAnswers'] = isset( $other_answers[$question->id] ) ? $other_answers[$question->id] : array();

            if( in_array( $question->type, $text_types ) ){
                $question_results[$question_id]['answers'] = isset( $text_answer[$question->type] ) ? $text_answer[$question->type] : '';
                $question_results[$question_id]['answerTitles'] = isset( $text_answer[$question->type] ) ? $text_answer[$question->type] : '';
                $question_results[$question_id]['sum_of_answers_count'] = isset( $text_answer[$question->type][$question->id] ) ? count( $text_answer[$question->type][$question->id] ) : 0;
                $question_results[$question_id]['sum_of_same_answers']  = isset( $text_answer[$question->type][$question->id] ) ? array_count_values( $text_answer[$question->type][$question->id] ) : 0;
            }else{
                $question_results[$question_id]['answers'] = $ays_answer;
                $question_results[$question_id]['answerTitles'] = $question_answer_ids;
                $question_results[$question_id]['sum_of_answers_count'] = $sum_of_count;
                if( $sum_of_count == 0 ){
                    $question_results[$question_id]['answers'] = array();
                }
            }

            // Answers for charts
            if( !empty( $question_results[$question_id]['otherAnswers'] ) ){
                $question_results[$question_id]['answers'][0] = count( $question_results[$question_id]['otherAnswers'] );
                $question_results[$question_id]['answerTitles'][0] = __( '"Other" answer(s)', "survey-maker" );
                $question_results[$question_id]['same_other_count'] = array_count_values( $question_results[$question_id]['otherAnswers'] );

                if($question_type == "radio" || $question_type == "yesorno"){
                    $question_results[$question_id]['sum_of_answers_count'] += count( $question_results[$question_id]['otherAnswers'] );
                }

            }
            //

            $total_count += intval( $question_results[$question_id]['sum_of_answers_count'] );

            $question_results[$question_id]['question_type'] = $question->type;
        }

        return array(
            'total_count' => $total_count,
            'questions' => $question_results
        );
    }
    
    public function ays_survey_get_last_submission_id( $survey_id ){
        global $wpdb;

        if($survey_id === null){
            return array();
        }

        $submitions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";

        //submission of each result
        $submission = "SELECT * FROM {$submitions_table} WHERE survey_id=". absint( $survey_id ) ." ORDER BY id DESC LIMIT 1 ";
        $last_submission = $wpdb->get_row( $submission, 'ARRAY_A' );
        
        if( $last_submission == null ){
            return array();
        }
        return $last_submission;
    }

    public function ays_survey_individual_results_for_one_submission( $submission, $survey ){
        global $wpdb;
        $survey_id = isset( $survey['id'] ) ? absint( intval( $survey['id'] ) ) : null;

        if( is_null( $survey_id ) || empty( $submission )){
            return array(
                'sections' => array()
            );
        }

        $submitions_questiions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        $submitions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";

        $ays_individual_questions_for_one_submission = array();
        $question_answer_id = array();
        $submission_id = isset( $submission['id'] ) && $submission['id'] != '' ? $submission['id'] : null;

        if( is_null( $submission_id ) ){
            return array(
                'sections' => array()
            );
        }

        $checkbox_ids = '';
        
        $individual_questions = "SELECT * FROM {$submitions_questiions_table} WHERE submission_id=" . absint( $submission_id );
        $individual_questions_results = $wpdb->get_results($individual_questions,'ARRAY_A');

        // Get user info
        $which_needed = "id,user_ip,user_id,user_name,user_email,submission_date";
        $individual_users = "SELECT ".$which_needed." FROM ".$submitions_table." WHERE id=" . absint( $submission_id );
        $individual_users_results = $wpdb->get_row($individual_users,'ARRAY_A');
        $user_id = isset($individual_users_results['user_id']) && $individual_users_results['user_id'] != "" ? $individual_users_results['user_id'] : 0;
        $user_real_name = __("Guest" , "survey-maker"); 
        $user_real_email = ""; 
        if($user_id > 0){
            $user_data = get_userdata($user_id);
            $user_real_name = $user_data->data->display_name;
            $user_real_email = $user_data->data->user_email;
        }
        if(!isset($individual_users_results['user_name']) || (isset($individual_users_results['user_name']) && $individual_users_results['user_name'] == "")){
            $individual_users_results['user_name'] = $user_real_name;
        }

        if(!isset($individual_users_results['user_email']) || (isset($individual_users_results['user_email']) && $individual_users_results['user_email'] == "")){
            $individual_users_results['user_email'] = $user_real_email;
        }
        $individual_users_results['user_name'] =  stripslashes(nl2br( htmlentities($individual_users_results['user_name'])));
        // Survey questions IDs
        $question_ids = isset( $survey['question_ids'] ) && $survey['question_ids'] != '' ? $survey['question_ids'] : '';

        // Section Ids
        $sections_ids = (isset( $survey['section_ids' ] ) && $survey['section_ids'] != '') ? $survey['section_ids'] : '';

        $sections = Survey_Maker_Data::get_suervey_sections_with_questions( $sections_ids, $question_ids );

        $text_types = array(
            'text',
            'short_text',
            'number',
            'name',
            'email',
        );

        foreach ($individual_questions_results as $key => $individual_questions_result) {
            if($individual_questions_result['type'] == 'checkbox'){
                $checkbox_ids = $individual_questions_result['user_answer'] != '' ? explode(',', $individual_questions_result['user_answer']) : array();
                $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = $checkbox_ids;

                $question_answer_id[ $individual_questions_result['question_id'] ]['otherAnswer'] = isset($individual_questions_result['user_variant']) && $individual_questions_result['user_variant'] != '' ? stripslashes(htmlentities($individual_questions_result['user_variant'])) : '';
            }elseif( in_array( $individual_questions_result['type'], $text_types ) ){

                $question_answer_id[ $individual_questions_result['question_id'] ] = stripslashes(htmlentities($individual_questions_result['user_answer']));
            }elseif($individual_questions_result['type'] == 'radio'){

                $other_answer = isset($individual_questions_result['user_variant']) && $individual_questions_result['user_variant'] != '' ? stripslashes(htmlentities($individual_questions_result['user_variant'])) : '';
                $question_answer_id[ $individual_questions_result['question_id'] ]['otherAnswer'] = $other_answer;
                $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = $individual_questions_result['answer_id'];
            }else{
                $question_answer_id[ $individual_questions_result['question_id'] ] = $individual_questions_result['answer_id'];
            }
        }

        $ays_individual_questions_for_one_submission['submission_id'] = $submission['id'];
        $ays_individual_questions_for_one_submission['questions'] = $question_answer_id;
        $ays_individual_questions_for_one_submission['sections'] = $sections;
        $ays_individual_questions_for_one_submission['user_info'] = $individual_users_results;
        return $ays_individual_questions_for_one_submission;
    }
    
    public function get_submission_count_and_ids(){
        global $wpdb;
        $survey_id = isset($_GET['survey']) ? intval($_GET['survey']) : null;

        if($survey_id === null){
            return false;
        }
        $submitions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
       
        //submission of each result
        $submission_ids = "SELECT id,
                            (SELECT COUNT(id) FROM {$submitions_table} i 
                            WHERE i.survey_id=j.survey_id) AS count_submission 
                            FROM {$submitions_table} j 
                            WHERE survey_id=". absint( $survey_id ) ."
                            ORDER BY id";
        $submission_ids_result = $wpdb->get_results($submission_ids,'ARRAY_A');
        $submission_count = '';
        $submissions_id_arr = array();
        foreach ($submission_ids_result as $key => $submission_id_result) {
            $submission_id_count = $submission_id_result['count_submission'];
            $submission_count = intval($submission_id_count);
            $submissions_id_arr[] = $submission_id_result['id'];
        }
        $submissions_id_str = implode(',', $submissions_id_arr );
        
        $submission_count_and_ids = array(
            'submission_count' => $submission_count,
            'submission_ids' => $submissions_id_str
        );

        return $submission_count_and_ids;
    }

    public function ays_survey_submission_report(){
        global $wpdb;
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ays_survey_submission_report' &&  wp_verify_nonce( $_REQUEST['nonce'], 'ajax-nonce' )) {

            $survey_id = (isset($_REQUEST['surveyId']) && $_REQUEST['surveyId'] != "") ? intval(sanitize_text_field($_REQUEST['surveyId'])) : null;
            if($survey_id === null){
                return false;
            }
            
            $sql = "SELECT * FROM " . $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys WHERE id =" . absint( $survey_id );
            $survey = $wpdb->get_row( $sql, 'ARRAY_A' );

            $submission_id = (isset($_REQUEST['submissionId']) && $_REQUEST['submissionId'] != '') ? absint( sanitize_text_field( $_REQUEST['submissionId'] ) ) : null;
            if($submission_id == null){
                return false;
            }
            $submission = array(
                'id' => $submission_id
            );

            $results = $this->ays_survey_individual_results_for_one_submission( $submission, $survey );
            $individual_user_name   = isset($results['user_info']['user_name']) && isset($results['user_info']['user_name']) ? $results['user_info']['user_name'] : "";
            $individual_user_email  = isset($results['user_info']['user_email']) && isset($results['user_info']['user_email'])  ? esc_attr($results['user_info']['user_email']) : "";
            $individual_user_ip     = isset($results['user_info']['user_ip']) && isset($results['user_info']['user_ip'])  ? esc_attr($results['user_info']['user_ip']) : "";
            $individual_user_date   = isset($results['user_info']['submission_date']) && isset($results['user_info']['submission_date'])  ? esc_attr($results['user_info']['submission_date']) : "";
            $individual_user_sub_id = isset($results['user_info']['id']) && isset($results['user_info']['id'])  ? esc_attr($results['user_info']['id']) : "";
            $survey_data_clipboard = array(
                "user_name"   => $individual_user_name,
                "user_email"  => $individual_user_email,
                "user_ip"     => $individual_user_ip,
                "user_date"   => $individual_user_date,
                "user_sub_id" => $individual_user_sub_id,
            );
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            $response = array(
                'status' => true,
                'questions' => $results['questions'],
                'user_info' => $results['user_info'],
                'user_info_for_copy' => Survey_Maker_Data::ays_survey_copy_text_formater($survey_data_clipboard)
            );
            echo json_encode($response);
            wp_die();
        }
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        $response = array(
            'status' => false
        );
        echo json_encode($response);
        wp_die();
    }

    // Survey Maker Elementor widget init
    public function survey_maker_el_widgets_registered() {
        // We check if the Elementor plugin has been installed / activated.
        if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {
            // get our own widgets up and running:
            // copied from widgets-manager.php
            if ( class_exists( 'Elementor\Plugin' ) ) {
                if ( is_callable( 'Elementor\Plugin', 'instance' ) ) {
                    $elementor = Elementor\Plugin::instance();
                    if ( isset( $elementor->widgets_manager ) ) {
                        if ( method_exists( $elementor->widgets_manager, 'register_widget_type' ) ) {
                            wp_enqueue_style($this->plugin_name . '-admin', plugin_dir_url(__FILE__) . 'css/admin.css', array(), $this->version, 'all');
                            wp_enqueue_style( SURVEY_MAKER_NAME . "-dropdown", SURVEY_MAKER_PUBLIC_URL . '/css/dropdown.min.css', array(), SURVEY_MAKER_VERSION, 'all' );
                            $widget_file   = 'plugins/elementor/survey-maker-elementor.php';
                            $template_file = locate_template( $widget_file );
                            if ( !$template_file || !is_readable( $template_file ) ) {
                                $template_file = SURVEY_MAKER_DIR . 'pb_templates/survey-maker-elementor.php';
                            }
                            if ( $template_file && is_readable( $template_file ) ) {
                                require_once $template_file;
                                Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Elementor\Widget_Survey_Maker_Elementor() );
                            }
                        }
                    }
                }
            }
        }
    }

    // Get Submissions count ( Checkbox )
    public function ays_survey_get_submission_count($id, $type, $survey_id){
        global $wpdb;
        $submitions_table   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $submitions_q_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        $results = array();
        if($type == 'checkbox'){
            $sql = "SELECT COUNT(submission_id) AS sub_count
                    FROM {$submitions_q_table}
                    WHERE question_id = ". absint( $id )."
                    AND user_answer != ''
                    AND type='". esc_sql( $type ) ."'
                    AND survey_id=". absint( $survey_id );
            $results = $wpdb->get_row($sql,'ARRAY_A');
        }
        $submission_count = isset($results['sub_count']) && $results['sub_count'] != "" && $results['sub_count'] > 0 ? $results['sub_count'] : 0;
        return $submission_count;
    }

    public function ays_survey_sale_baner(){
        /*   OLD INFO OPEN AFTER HALLOWEN START  */
        // if(isset($_POST['ays_survey_sale_btn'])){
        //     update_option('ays_survey_sale_btn', 1);
        //     update_option('ays_survey_maker_sale_date', current_time( 'mysql' ));
        // }

        // if(isset($_POST['ays_survey_sale_btn_for_two_months'])){
        //     update_option('ays_survey_sale_btn_for_two_months', 1);
        //     update_option('ays_survey_maker_sale_date', current_time( 'mysql' ));
        // }
    
        // $ays_survey_maker_sale_date = get_option('ays_survey_maker_sale_date');
        // $ays_survey_maker_sale_two_months = get_option('ays_survey_sale_btn_for_two_months');

        // $val = 60*60*24*5;
        // if($ays_survey_maker_sale_two_months == 1){
        //     $val = 60*60*24*61;
        // }

        // $current_date = current_time( 'mysql' );
        // $date_diff = strtotime($current_date) - intval(strtotime($ays_survey_maker_sale_date)) ;
        
        // $days_diff = $date_diff / $val;
    
        // if(intval($days_diff) > 0 ){
        //     update_option('ays_survey_sale_btn', 0);
        //     update_option('ays_survey_sale_btn_for_two_months', 0);
        // }
    
        // $ays_survey_maker_flag = intval(get_option('ays_survey_sale_btn'));
        // $ays_survey_maker_flag += intval(get_option('ays_survey_sale_btn_for_two_months'));
        // if( $ays_survey_maker_flag == 0 ){
        //     if (isset($_GET['page']) && strpos($_GET['page'], SURVEY_MAKER_NAME) !== false) {
        //         if( !(Survey_Maker_Admin::get_max_id('surveys') <= 1) ){
        //             // $this->ays_survey_sale_message($ays_survey_maker_flag);
        //             // $this->ays_survey_spring_bundle_small_message($ays_survey_maker_flag);
        //             $this->ays_survey_maker_helloween_message($ays_survey_maker_flag);
        //         }
        //     }
        // }
        /*   OLD INFO OPEN AFTER HALLOWEN END  */

        // ONLY FOR Black Friday
        if(isset($_POST['ays_survey_sale_btn'])){
            update_option('ays_survey_sale_btn', 1);
            update_option('ays_survey_sale_date', current_time( 'mysql' ));
        }
    
        $ays_survey_sale_date = get_option('ays_survey_sale_date');

        $val = 60*60*24*5;

        $current_date = current_time( 'mysql' );
        $date_diff = strtotime($current_date) - intval(strtotime($ays_survey_sale_date)) ;
        
        $days_diff = $date_diff / $val;
    
        if(intval($days_diff) > 0 ){
            update_option('ays_survey_sale_btn', 0);
        }
    
        $ays_survey_maker_flag = intval(get_option('ays_survey_sale_btn'));
        if( $ays_survey_maker_flag == 0 ){
            if (isset($_GET['page']) && strpos($_GET['page'], SURVEY_MAKER_NAME) !== false) {
                if( !(Survey_Maker_Admin::get_max_id('surveys') <= 1) ){
                    $this->ays_survey_sale_message($ays_survey_maker_flag);
                    // $this->ays_survey_spring_bundle_small_message($ays_survey_maker_flag);
                    // $this->ays_survey_maker_helloween_message($ays_survey_maker_flag);
                    // $this->ays_survey_black_friday_message($ays_survey_maker_flag);
                    // $this->ays_survey_christmas_message($ays_survey_maker_flag);
                }
            }
        }
    }

    public function ays_survey_sale_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-survey-dicount-month-main" class="notice notice-success is-dismissible ays_survey_dicount_info">';
                $content[] = '<div id="ays-survey-dicount-month" class="ays_survey_dicount_month">';
                    $content[] = '<a href="https://ays-pro.com/mega-bundle?utm_source=dashboard-mega&utm_medium=free-mega&utm_campaign=survey-mega" target="_blank" class="ays-survey-sale-banner-link" ><img src="' . esc_attr(SURVEY_MAKER_ADMIN_URL) . '/images/mega_bundle_logo_box.png"></a>';

                    $content[] = '<div class="ays-survey-dicount-wrap-box">';

                        $content[] = '<strong style="font-weight: bold;">';
                            $content[] = 'Limited Time <span class="ays-survey-dicount-wrap-color" style="color:#E85011;">50%</span> SALE on <br><span><a href="https://ays-pro.com/mega-bundle?utm_source=dashboard-mega&utm_medium=free-mega&utm_campaign=survey-mega" target="_blank" class="ays-survey-dicount-wrap-color ays-survey-dicount-wrap-text-decoration" style="color:#E85011; text-decoration: underline;">Mega Bundle</a> (Quiz + Survey + Poll)!</span>';
                        $content[] = '<br>';
                        $content[] = '</strong>';
                        $content[] = '<strong>';
                                $content[] = __( "Hurry up", "survey-maker" ) . '! ' . '<a href="https://ays-pro.com/mega-bundle?utm_source=dashboard-mega&utm_medium=free-mega&utm_campaign=survey-mega" target="_blank">'. __( "Check it out", "survey-maker" ) .'!</a>';
                        $content[] = '</strong>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-survey-dicount-wrap-box">';

                        $content[] = '<div id="ays-survey-maker-countdown-main-container">';
                            $content[] = '<div class="ays-survey-maker-countdown-container">';

                                $content[] = '<div id="ays-survey-countdown">';
                                    $content[] = '<ul>';
                                        $content[] = '<li><span id="ays-survey-countdown-days"></span>days</li>';
                                        $content[] = '<li><span id="ays-survey-countdown-hours"></span>Hours</li>';
                                        $content[] = '<li><span id="ays-survey-countdown-minutes"></span>Minutes</li>';
                                        $content[] = '<li><span id="ays-survey-countdown-seconds"></span>Seconds</li>';
                                    $content[] = '</ul>';
                                $content[] = '</div>';

                                $content[] = '<div id="ays-survey-countdown-content" class="emoji">';
                                    $content[] = '<span>🚀</span>';
                                    $content[] = '<span>⌛</span>';
                                    $content[] = '<span>🔥</span>';
                                    $content[] = '<span>💣</span>';
                                $content[] = '</div>';

                            $content[] = '</div>';

                        $content[] = '</div>';
                            
                    $content[] = '</div>';

                    $content[] = '<div class="ays-survey-dicount-wrap-box ays-buy-now-button-box">';
                        $content[] = '<a href="https://ays-pro.com/mega-bundle?utm_source=dashboard-mega&utm_medium=free-mega&utm_campaign=survey-mega" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank" style="" >' . __( 'Buy Now !', "survey-maker" ) . '</a>';
                    $content[] = '</div>';

                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;" class="ays-survey-dismiss-buttons-container-for-form">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-survey-dismiss-buttons-content">';
                            $content[] = '<button class="btn btn-link ays-button" name="ays_survey_sale_btn" style="height: 32px; margin-left: 0;padding-left: 0; color: #979797;">Dismiss ad</button>';
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';

            $content[] = '</div>';

            $content = implode( '', $content );
            // echo $content;
            echo html_entity_decode(esc_html( $content ));
        }
    }

    // Black Friday
    // public static function ays_survey_black_friday_message($ishmar){
    //     if($ishmar == 0 ){
    //         $content = array();

    //         $content[] = '<div id="ays-survey-dicount-black-friday-month-main" class="notice notice-success is-dismissible ays_survey_dicount_info">';
    //             $content[] = '<div id="ays-survey-dicount-black-friday-month" class="ays_survey_dicount_month">';
    //                 $content[] = '<div class="ays-survey-dicount-black-friday-box">';
    //                     $content[] = '<div class="ays-survey-dicount-black-friday-wrap-box ays-survey-dicount-black-friday-wrap-box-80" style="width: 70%;">';
    //                         $content[] = '<div class="ays-survey-dicount-black-friday-title-row">' . __( 'Limited Time', "survey-maker" ) .' '. '<a href="https://ays-pro.com/wordpress/survey-maker" class="ays-survey-dicount-black-friday-button-sale" target="_blank">' . __( 'Sale', "survey-maker" ) . '</a>' . '</div>';
    //                         $content[] = '<div class="ays-survey-dicount-black-friday-title-row">' . __( 'Survey Maker plugin', "survey-maker" ) . '</div>';
    //                     $content[] = '</div>';

    //                     $content[] = '<div class="ays-survey-dicount-black-friday-wrap-box ays-survey-dicount-black-friday-wrap-text-box">';
    //                         $content[] = '<div class="ays-survey-dicount-black-friday-text-row">' . __( '20% off', "survey-maker" ) . '</div>';
    //                     $content[] = '</div>';

    //                     $content[] = '<div class="ays-survey-dicount-black-friday-wrap-box" style="width: 25%;">';
    //                         $content[] = '<div id="ays-survey-maker-countdown-main-container">';
    //                             $content[] = '<div class="ays-survey-countdown-container">';
    //                                 $content[] = '<div id="ays-survey-countdown" style="display: block;">';
    //                                     $content[] = '<ul>';
    //                                         $content[] = '<li><span id="ays-survey-countdown-days">0</span>' . __( 'Days', "survey-maker" ) . '</li>';
    //                                         $content[] = '<li><span id="ays-survey-countdown-hours">0</span>' . __( 'Hours', "survey-maker" ) . '</li>';
    //                                         $content[] = '<li><span id="ays-survey-countdown-minutes">0</span>' . __( 'Minutes', "survey-maker" ) . '</li>';
    //                                         $content[] = '<li><span id="ays-survey-countdown-seconds">0</span>' . __( 'Seconds', "survey-maker" ) . '</li>';
    //                                     $content[] = '</ul>';
    //                                 $content[] = '</div>';
    //                                 $content[] = '<div id="ays-survey-countdown-content" class="emoji" style="display: none;">';
    //                                     $content[] = '<span>🚀</span>';
    //                                     $content[] = '<span>⌛</span>';
    //                                     $content[] = '<span>🔥</span>';
    //                                     $content[] = '<span>💣</span>';
    //                                 $content[] = '</div>';
    //                             $content[] = '</div>';
    //                         $content[] = '</div>';
    //                     $content[] = '</div>';

    //                     $content[] = '<div class="ays-survey-dicount-black-friday-wrap-box" style="width: 25%;">';
    //                         $content[] = '<a href="https://ays-pro.com/wordpress/survey-maker" class="ays-survey-dicount-black-friday-button-buy-now" target="_blank">' . __( 'Get Your Deal', "survey-maker" ) . '</a>';
    //                     $content[] = '</div>';
    //                 $content[] = '</div>';
    //             $content[] = '</div>';

    //             $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-survey-dismiss-buttons-container-for-form-black-friday">';
    //                 $content[] = '<form action="" method="POST">';
    //                     $content[] = '<div id="ays-survey-dismiss-buttons-content-black-friday">';
    //                         $content[] = '<button class="btn btn-link ays-button-black-friday" name="ays_survey_black_fr_sale_btn" style="">' . __( 'Dismiss ad', "survey-maker" ) . '</button>';
    //                     $content[] = '</div>';
    //                 $content[] = '</form>';
    //             $content[] = '</div>';
    //         $content[] = '</div>';

    //         $content = implode( '', $content );

    //         echo $content;
    //     }
    // }

    // Christmas banner
    public static function ays_survey_christmas_message($ishmar){
        if($ishmar == 0 ){
            $content = array();

            $content[] = '<div id="ays-survey-dicount-christmas-month-main" class="notice notice-success is-dismissible ays_survey_dicount_info">';
                $content[] = '<div id="ays-survey-dicount-christmas-month" class="ays_survey_dicount_month">';
                    $content[] = '<div class="ays-survey-dicount-christmas-box">';
                        $content[] = '<div class="ays-survey-dicount-christmas-wrap-box ays-survey-dicount-christmas-wrap-box-80">';
                            $content[] = '<div class="ays-survey-dicount-christmas-title-row">' . __( 'Limited Time', "survey-maker" ) .' '. '<a href="https://ays-pro.com/wordpress/survey-maker" class="ays-survey-dicount-christmas-button-sale" target="_blank">20%</a>' . ' SALE</div>';
                            $content[] = '<div class="ays-survey-dicount-christmas-title-row">' . __( 'Survey Maker Plugin', "survey-maker" ) . '</div>';
                        $content[] = '</div>';

                            $content[] = '<div class="ays-survey-dicount-christmas-wrap-box" style="width: 25%;">';
                                $content[] = '<div id="ays-survey-maker-countdown-main-container">';
                                    $content[] = '<div class="ays-survey-countdown-container">';
                                        $content[] = '<div id="ays-survey-countdown" style="display: block;">';
                                            $content[] = '<ul>';
                                                $content[] = '<li><span id="ays-survey-countdown-days"></span>' . __( 'Days', "survey-maker" ) . '</li>';
                                                $content[] = '<li><span id="ays-survey-countdown-hours"></span>' . __( 'Hours', "survey-maker" ) . '</li>';
                                                $content[] = '<li><span id="ays-survey-countdown-minutes"></span>' . __( 'Minutes', "survey-maker" ) . '</li>';
                                                $content[] = '<li><span id="ays-survey-countdown-seconds"></span>' . __( 'Seconds', "survey-maker" ) . '</li>';
                                            $content[] = '</ul>';
                                        $content[] = '</div>';
                                        $content[] = '<div id="ays-survey-countdown-content" class="emoji" style="display: none;">';
                                            $content[] = '<span>🚀</span>';
                                            $content[] = '<span>⌛</span>';
                                            $content[] = '<span>🔥</span>';
                                            $content[] = '<span>💣</span>';
                                        $content[] = '</div>';
                                    $content[] = '</div>';
                                $content[] = '</div>';
                            $content[] = '</div>';

                        $content[] = '<div class="ays-survey-dicount-christmas-wrap-box" style="width: 25%;">';
                            $content[] = '<a href="https://ays-pro.com/wordpress/survey-maker" class="ays-survey-dicount-christmas-button-buy-now" target="_blank">' . __( 'BUY NOW', "survey-maker" ) . '!</a>';
                        $content[] = '</div>';
                    $content[] = '</div>';
                $content[] = '</div>';

                $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-survey-dismiss-buttons-container-for-form-christmas">';
                    $content[] = '<form action="" method="POST">';
                        $content[] = '<div id="ays-survey-dismiss-buttons-content-christmas">';
                            $content[] = '<button class="btn btn-link ays-button-christmas" name="ays_survey_sale_btn" style="">' . __( 'Dismiss ad', "survey-maker" ) . '</button>';
                        $content[] = '</div>';
                    $content[] = '</form>';
                $content[] = '</div>';
            $content[] = '</div>';

            $content = implode( '', $content );

            echo $content;
        }
    }
    
    

    
    // Winter bundle
    // public function ays_survey_maker_sale_message($flag){
	// 	$content = array();
	// 	$content[] = '<div id="ays-survey-winter-dicount-main">';
	// 		$content[] = '<div id="ays-survey-dicount-month-main" class="notice notice-success is-dismissible ays_survey_dicount_info">';
	// 			$content[] = '<div id="ays-survey-dicount-month" class="ays_survey_dicount_month">';
	// 				$content[] = '<a href="https://ays-pro.com/mega-bundle" target="_blank" class="ays-survey-sale-banner-link"><img src="' . SURVEY_MAKER_ADMIN_URL . '/images/mega_bundle_logo_box.png"></a>';

	// 				$content[] = '<div class="ays-survey-dicount-wrap-box">';

	// 					$content[] = '<strong>';
	// 						$content[] = __( "The BIGGEST <span class='ays-survey-dicount-wrap-color' style='color:#001E64;'>50%</span> SALE on <br><span><a href='https://ays-pro.com/mega-bundle' target='_blank' class='ays-survey-dicount-wrap-color ays-survey-dicount-wrap-text-decoration' style='display:block;color:#001E64'>Mega Bundle</a></span> (Quiz+Survey+Poll)!", SURVEY_MAKER_ADMIN_URL );
	// 					$content[] = '</strong>';

	// 					$content[] = '<br>';

	// 					$content[] = '<strong>';
	// 							$content[] = __( "Hurry up! Ending on. <a href='https://ays-pro.com/mega-bundle' target='_blank' style='color:#001E64;'>Check it out!</a>", SURVEY_MAKER_ADMIN_URL );
	// 					$content[] = '</strong>';
							
	// 				$content[] = '</div>';

	// 				$content[] = '<div class="ays-survey-dicount-wrap-box">';

	// 					$content[] = '<div id="ays-survey-maker-countdown-main-container">';
	// 						$content[] = '<div class="ays-survey-maker-countdown-container">';

	// 							$content[] = '<div id="ays-survey-countdown">';
	// 								$content[] = '<ul>';
	// 									$content[] = '<li><span id="ays-survey-countdown-days"></span>days</li>';
	// 									$content[] = '<li><span id="ays-survey-countdown-hours"></span>Hours</li>';
	// 									$content[] = '<li><span id="ays-survey-countdown-minutes"></span>Minutes</li>';
	// 									$content[] = '<li><span id="ays-survey-countdown-seconds"></span>Seconds</li>';
	// 								$content[] = '</ul>';
	// 							$content[] = '</div>';

	// 							$content[] = '<div id="ays-survey-countdown-content" class="emoji">';
	// 								$content[] = '<span>🚀</span>';
	// 								$content[] = '<span>⌛</span>';
	// 								$content[] = '<span>🔥</span>';
	// 								$content[] = '<span>💣</span>';
	// 							$content[] = '</div>';

	// 						$content[] = '</div>';

	// 						$content[] = '<form action="" method="POST">';
	// 							$content[] = '<button class="btn btn-link ays-button" name="ays_survey_maker_winter_sale_btn" style="height: 32px;color:#0041B8" value="winter_bundle">Dismiss ad</button>';
	// 							$content[] = '<button class="btn btn-link ays-button" name="ays_survey_maker_winter_sale_btn_for_two_months" style="height: 32px; padding-left: 0;color:#0041B8" value="winter_bundle">Dismiss ad for 2 months</button>';
	// 						$content[] = '</form>';

	// 					$content[] = '</div>';
							
	// 				$content[] = '</div>';

	// 				$content[] = '<a href="https://ays-pro.com/mega-bundle" class="button button-primary ays-button" id="ays-survey-button-top-buy-now" target="_blank">' . __( 'Buy Now !', SURVEY_MAKER_ADMIN_URL ) . '</a>';
	// 			$content[] = '</div>';
	// 		$content[] = '</div>';
	// 	// $content[] = '</div>';
	// 	$content = implode( '', $content );
	// 	echo $content;
    // }

    // public static function ays_survey_spring_bundle_small_message($ishmar){
    //     if($ishmar == 0 ){
    //         $content = array();

    //         $content[] = '<div id="ays-survey-dicount-month-main" class="notice notice-success is-dismissible ays_survey_dicount_info">';
    //             $content[] = '<div id="ays-survey-dicount-month" class="ays_survey_dicount_month">';
    //                 $content[] = '<a href="https://ays-pro.com/mega-bundle" target="_blank" class="ays-survey-sale-banner-link"><img src="' . SURVEY_MAKER_ADMIN_URL . '/images/mega_bundle_logo_box.png"></a>';

    //                 $content[] = '<div class="ays-survey-dicount-wrap-box">';
    //                     $content[] = '<p>';
    //                         $content[] = '<strong>';
    //                             $content[] = __( "Spring is here! <span class='ays-survey-dicount-wrap-color'>50%</span> SALE on <span><a href='https://ays-pro.com/mega-bundle' target='_blank' class='ays-survey-dicount-wrap-color ays-survey-dicount-wrap-text-decoration'>Mega Bundle</a></span><span style='display: block;'>Quiz + Survey + Poll</span>", SURVEY_MAKER_ADMIN_URL );
    //                         $content[] = '</strong>';
    //                     $content[] = '</p>';
    //                 $content[] = '</div>';

    //                 $content[] = '<div class="ays-survey-dicount-wrap-box">';

    //                     $content[] = '<div id="ays-survey-countdown-main-container">';

    //                         $content[] = '<form action="" method="POST" class="ays-survey-btn-form">';
    //                             $content[] = '<button class="btn btn-link ays-button" name="ays_survey_sale_btn_small_spring" style="height: 32px; margin-left: 0;padding-left: 0">Dismiss ad</button>';
    //                             $content[] = '<button class="btn btn-link ays-button" name="ays_survey_sale_btn_spring_small_for_two_months" style="height: 32px; padding-left: 0">Dismiss ad for 2 months</button>';
    //                         $content[] = '</form>';

    //                     $content[] = '</div>';
                            
    //                 $content[] = '</div>';

    //                 $content[] = '<a href="https://ays-pro.com/mega-bundle" class="button button-primary ays-button" id="ays-button-top-buy-now" target="_blank">' . __( 'Buy Now !', SURVEY_MAKER_ADMIN_URL ) . '</a>';
    //             $content[] = '</div>';
    //         $content[] = '</div>';

    //         $content = implode( '', $content );
    //         echo $content;
    //     }
    // }

    public function ays_live_preivew_content(){

        $content = isset($_REQUEST['content']) && $_REQUEST['content'] != '' ? wp_kses_post( $_REQUEST['content'] ) : null;
        if($content === null){
            ob_end_clean();
            $ob_get_clean = ob_get_clean();
            echo json_encode(array(
                'status' => false,
            ));
        }
        // $content = Survey_Maker_Data::ays_autoembed( $content );
        $content = stripslashes( wpautop( $content ) );
        ob_end_clean();
        $ob_get_clean = ob_get_clean();
        echo json_encode(array(
            'status' => true,
            'content' => $content,
        ));
        wp_die();
    }
    
	public function add_tabs() {
		$screen = get_current_screen();
	
		if ( ! $screen) {
			return;
		}
        
        $title   = __( 'General Information:', "survey-maker");
        $content_text = 'Get real-time feedback with the Survey Maker plugin. You are free to generate unlimited online surveys with unlimited questions and sections. Easily create your customer satisfaction surveys, employee engagement forms, market researches, event planning questionnaires with this plugin.
                        <br><br>Increase users’ track to your WordPress website with the Survey Maker features. Build smarter surveys with LogicJump, advance your questionnaires with Conditional Results, earn money with Paid Surveys, generate leads super easily, get valuable feedback.';

        $sidebar_content = '<p><strong>' . __( 'For more information:', "survey-maker") . '</strong></p>' .
                            '<p>
                                <a href="https://www.youtube.com/watch?v=Q1qi649acb0" target="_blank">' . __( 'Youtube video tutorials' , "survey-maker" ) . '</a>
                            </p>' .
                            '<p>
                                <a href="https://ays-pro.com/wordpress-survey-maker-user-manual" target="_blank">' . __( 'Documentation', "survey-maker" ) . '</a>
                            </p>' .
                            '<p>
                                <a href="https://ays-pro.com/wordpress/survey-maker?utm_source=wordpress&utm_medium=ays-plugins&utm_campaign=survey" target="_blank">' . __( 'Survey Maker plugin pro version', "survey-maker" ) . '</a>
                            </p>' .
                            '<p>
                                <a href="https://ays-demo.com/wordpress-survey-plugin-pro-demo/" target="_blank">' . __( 'Survey Maker plugin demo', "survey-maker" ) . '</a>
                            </p>';

        
        $content =  '<h2>' . __( 'Survey Maker Information', "survey-maker") . '</h2>'
                   .'<p>' .sprintf(__( '%s',  "survey-maker" ), $content_text).'</p>';

        $help_tab_content = array(
            'id'      => 'survey_maker_help_tab',
            'title'   => $title,
            'content' => $content
        );
        
		$screen->add_help_tab($help_tab_content);

		$screen->set_help_sidebar($sidebar_content);
	}

    public function get_next_or_prev_survey_by_id( $id, $type = "next" ) {
        global $wpdb;

        $surveys_table = esc_sql( $wpdb->prefix . "ayssurvey_surveys" );

        $where = array();
        $where_condition = "";

        $id     = (isset( $id ) && $id != "" && absint($id) != 0) ? absint( sanitize_text_field( $id ) ) : null;
        $type   = (isset( $type ) && $type != "") ? sanitize_text_field( $type ) : "next";

        if ( is_null( $id ) || $id == 0 ) {
            return null;
        }

        switch ( $type ) {
            case 'prev':
                $where[] = ' `id` < ' . $id . ' ORDER BY `id` DESC ';
            break;
            case 'next':
            default:
                $where[] = ' `id` > ' . $id;
                break;
        }

        if( ! empty($where) ){
            $where_condition = " WHERE " . implode( " AND ", $where );
        }

        $sql = "SELECT `id` FROM {$surveys_table} ". $where_condition ." LIMIT 1;";
        $results = $wpdb->get_row( $sql, 'ARRAY_A' );

        return $results;

    }

    public function ays_survey_update_banner_time(){

        $date = time() + ( 3 * 24 * 60 * 60 ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS);
        // $date = time() + ( 60 ) + (int) ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS); // for testing | 1 min
        $next_3_days = date('M d, Y H:i:s', $date);

        $ays_survey_banner_time = get_option('ays_survey_banner_time');

        if ( !$ays_survey_banner_time || is_null( $ays_survey_banner_time ) ) {
            update_option('ays_survey_banner_time', $next_3_days ); 
        }

        $get_ays_survey_banner_time = get_option('ays_survey_banner_time');

        $val = 60*60*24*0.5; // half day
        // $val = 60; // for testing | 1 min

        $current_date = current_time( 'mysql' );
        $date_diff = strtotime($current_date) - intval(strtotime($get_ays_survey_banner_time));

        $days_diff = $date_diff / $val;
        if(intval($days_diff) > 0 ){
            update_option('ays_survey_banner_time', $next_3_days);
        }

        return $get_ays_survey_banner_time;
    }

    // public static function ays_survey_maker_helloween_message($ishmar){
    //     if($ishmar == 0 ){
    //          
    //          ========================================================================================================================================
    //          ========================================================================================================================================
    //          ====== NOTE* IF WE NEED OPEN THIS FUNCTION LATER, REMEMBER TO FIX ALL TRANSLATION ISSUES WITH HTML TAGS LOCATED IN __() FUNCTIONS ======
    //          ========================================================================================================================================
    //          ========================================================================================================================================
    //         
    //         $content = array();

    //         $content[] = '<div id="ays-survey-dicount-month-main-helloween" class="notice notice-success is-dismissible ays_survey_maker_dicount_info">';
    //             $content[] = '<div id="ays-survey-dicount-month-helloween" class="ays_survey_maker_dicount_month_helloween">';
    //                 $content[] = '<div class="ays-survey-dicount-wrap-box-helloween-limited">';

    //                     $content[] = '<p>';
    //                         $content[] = __( "Limited Time 
    //                         <span class='ays-survey-dicount-wrap-color-helloween' style='color:#b2ff00;'>50%</span> 
    //                         <span>
    //                             SALE on
    //                         </span> 
    //                         <br>
    //                         <span style='' class='ays-survey-helloween-bundle'>
    //                             <a href='https://ays-pro.com/halloween-bundle' target='_blank' class='ays-survey-dicount-wrap-color-helloween ays-survey-dicount-wrap-text-decoration-helloween' style='display:block; color:#b2ff00;margin-right:6px;'>
    //                                 Helloween Bundle
    //                             </a>
    //                             (Copy + Poll + Survey + Popup)!
    //                         </span>", "survey-maker" );
    //                     $content[] = '</p>';
    //                     $content[] = '<p>';
    //                             $content[] = __( "Hurry up! 
    //                                             <a href='https://ays-pro.com/halloween-bundle' target='_blank' style='color:#ffc700;'>
    //                                                 Check it out!
    //                                             </a>", "survey-maker" );
    //                     $content[] = '</p>';
                            
    //                 $content[] = '</div>';

                    
    //                 $content[] = '<div class="ays-survey-helloween-bundle-buy-now-timer">';
    //                     $content[] = '<div class="ays-survey-dicount-wrap-box-helloween-timer">';
    //                         $content[] = '<div id="ays-survey-maker-countdown-main-container" class="ays-survey-countdown-main-container-helloween">';
    //                             $content[] = '<div class="ays-survey-countdown-container-helloween">';
    //                                 $content[] = '<div id="ays-survey-countdown">';
    //                                     $content[] = '<ul>';
    //                                         $content[] = '<li><p><span id="ays-survey-countdown-days"></span><span>days</span></p></li>';
    //                                         $content[] = '<li><p><span id="ays-survey-countdown-hours"></span><span>Hours</span></p></li>';
    //                                         $content[] = '<li><p><span id="ays-survey-countdown-minutes"></span><span>Mins</span></p></li>';
    //                                         $content[] = '<li><p><span id="ays-survey-countdown-seconds"></span><span>Secs</span></p></li>';
    //                                     $content[] = '</ul>';
    //                                 $content[] = '</div>';

    //                                 $content[] = '<div id="ays-survey-countdown-content" class="emoji">';
    //                                     $content[] = '<span>🚀</span>';
    //                                     $content[] = '<span>⌛</span>';
    //                                     $content[] = '<span>🔥</span>';
    //                                     $content[] = '<span>💣</span>';
    //                                 $content[] = '</div>';

    //                             $content[] = '</div>';

    //                         $content[] = '</div>';
                                
    //                     $content[] = '</div>';
    //                     $content[] = '<div class="ays-survey-dicount-wrap-box ays-buy-now-button-box-helloween">';
    //                         $content[] = '<a href="https://ays-pro.com/halloween-bundle" class="button button-primary ays-buy-now-button-helloween" id="ays-button-top-buy-now-helloween" target="_blank" style="" >' . __( 'Buy Now !', "survey-maker" ) . '</a>';
    //                     $content[] = '</div>';
    //                 $content[] = '</div>';

    //             $content[] = '</div>';

    //             $content[] = '<div style="position: absolute;right: 0;bottom: 1px;"  class="ays-survey-dismiss-buttons-container-for-form-helloween">';
    //                 $content[] = '<form action="" method="POST">';
    //                     $content[] = '<div id="ays-survey-dismiss-buttons-content-helloween">';
    //                         $content[] = '<button class="btn btn-link ays-button-helloween" name="ays_survey_maker_halloween_sale_btn" style="">Dismiss ad</button>';
    //                     $content[] = '</div>';
    //                 $content[] = '</form>';
    //             $content[] = '</div>';
    //             // $content[] = '<button type="button" class="notice-dismiss">';
    //             // $content[] = '</button>';
    //         $content[] = '</div>';

    //         $content = implode( '', $content );

    //         echo $content;
    //     }
    // }

    // public function ays_survey_subscribe_email(){
    //     $subscribe_email   = isset($_REQUEST['email']) && $_REQUEST['email'] != "" ? sanitize_email($_REQUEST['email']) : "";
    //     $subscribe_website = isset($_REQUEST['website']) && $_REQUEST['website'] != "" ? sanitize_url($_REQUEST['website']) : "";
    //     // $url = "http://localhost/survey-grab-gift/";
    //     $url = "https://ays-pro.com/add-on-email/survey-grab-gift/";
    //     if($subscribe_email != "" && $subscribe_website != ''){
    //         $current_date = date("Y-m-d");
    //         $current_user_ip = Survey_Maker_Data::get_user_ip();
    //         $send_request = wp_remote_post($url, array(
    //             'headers'     => array("Content-Type: application/json; charset=UTF-8"),
    //             'body'        => json_encode( array(
    //                 "email"   => $subscribe_email,
    //                 "website" => $subscribe_website,
    //                 "user_ip" => $current_user_ip,
    //                 "subscirbe_date" => $current_date
    //             ) ),
    //         ) );
    //         $response = wp_remote_retrieve_body($send_request);
    //         $response = json_decode($response, true);
    //         if(isset($response) && is_array($response)){
    //             $response_code = isset($response['code']) && $response['code'] != "" ? $response['code'] : "";
    //             $response_message = isset($response['msg']) && $response['msg'] != "" ? $response['msg'] : "";
    //             $response_status = $response_code > 0 ? true : false;
    //             echo json_encode(array(
    //                 "status" => $response_code,
    //                 "message" => $response_message
    //             ));
    //             wp_die();
    //         }       
    //         else{
    //             echo json_encode(array(
    //                 "status" => false,
    //                 "message" => "Something went wrong. Please try again"
    //             ));
    //             wp_die();
    //         }
    //     }
    // }


}
