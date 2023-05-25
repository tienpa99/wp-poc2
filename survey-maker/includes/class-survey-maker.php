<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 * @author     Survey Maker team <info@ays-pro.com>
 */
class Survey_Maker {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Survey_Maker_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SURVEY_MAKER_VERSION' ) ) {
			$this->version = SURVEY_MAKER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'survey-maker';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_integrations_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Survey_Maker_Loader. Orchestrates the hooks of the plugin.
	 * - Survey_Maker_i18n. Defines internationalization functionality.
	 * - Survey_Maker_Admin. Defines all hooks for the admin area.
	 * - Survey_Maker_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

        if ( ! class_exists( 'WP_List_Table' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
        }

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-survey-maker-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-survey-maker-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-survey-maker-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-survey-maker-public.php';

		/**
		 * The class responsible for extra shortcodes on the front page
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-extra-shortcode.php';

		/*
		 * The class is responsible for showing surveys in wordpress default WP_LIST_TABLE style
		 */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-surveys-list-table.php';

        /*
         * The class is responsible for showing survey categories in wordpress default WP_LIST_TABLE style
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-survey-categories-list-table.php';

        /*
         * The class is responsible for showing survey submissions in wordpress default WP_LIST_TABLE style
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-submissions-list-table.php';
        
        /*
         * The class is responsible for showing each survey submissions in wordpress default WP_LIST_TABLE style
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-survey-maker-each-submission-list-table.php';
        
        /*
         * The class is responsible for showing survey submissions in wordpress default WP_LIST_TABLE style
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/settings/survey-maker-settings-actions.php';

		/**
		 * The class responsible for defining all functions for getting all survey data
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-survey-maker-data.php';

		/**
		 * The class responsible for defining all functions for getting all survey integrations
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-survey-maker-integrations.php';

		
		/**
		 * The class responsible for defining all functions for generating General Settings shorcodes content
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-submissions-summary-shortcode.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-most-popular-shortcode.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/class-survey-maker-survey-links-by-category.php';


		$this->loader = new Survey_Maker_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Survey_Maker_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Survey_Maker_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Survey_Maker_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        // Add menu item
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_surveys_submenu', 90 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_survey_categories_submenu', 95 );
        // $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_questions_categories_submenu', 100 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_submissions_submenu', 105 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_export_import_submenu', 110 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_general_settings_submenu', 115 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_dashboard_submenu', 125 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_featured_plugins_submenu', 135 );
        // $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_survey_features_plugins_submenu', 140 );
		// $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_subscribe_email', 140 );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_survey_features_plugins_submenu', 145 );

		// Pro
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_popup_survey', 100 );
		
		$this->loader->add_action( 'wp_ajax_ays_survey_submission_report', $plugin_admin, 'ays_survey_submission_report' );
		$this->loader->add_action( 'wp_ajax_nopriv_ays_survey_submission_report', $plugin_admin, 'ays_survey_submission_report' );

        // Deactivate plugin AJAx action
        $this->loader->add_action( 'wp_ajax_deactivate_plugin_option_sm', $plugin_admin, 'deactivate_plugin_option' );
        $this->loader->add_action( 'wp_ajax_nopriv_deactivate_plugin_option_sm', $plugin_admin, 'deactivate_plugin_option' );

		// Live preview
		$this->loader->add_action( 'wp_ajax_ays_live_preivew_content', $plugin_admin, 'ays_live_preivew_content' );
        $this->loader->add_action( 'wp_ajax_nopriv_ays_live_preivew_content', $plugin_admin, 'ays_live_preivew_content' );
		
		// Grab your gift
		// $this->loader->add_action( 'wp_ajax_ays_survey_subscribe_email', $plugin_admin, 'ays_survey_subscribe_email' );
        // $this->loader->add_action( 'wp_ajax_nopriv_ays_survey_subscribe_email', $plugin_admin, 'ays_survey_subscribe_email' );

        // Add Settings link to the plugin
        $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
        $this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

		// CodeMirror Editor
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'codemirror_enqueue_scripts');

        $this->loader->add_action( 'in_admin_footer', $plugin_admin, 'survey_maker_admin_footer', 1 );

        $this->loader->add_action( 'elementor/widgets/widgets_registered', $plugin_admin, 'survey_maker_el_widgets_registered' );

		// $this->loader->add_action( 'elementor/editor/before_enqueue_scripts', $plugin_admin, 'sm_enqueue_elementor_styles' );

		// Add aditional links to the plugin
		$this->loader->add_filter( 'plugin_row_meta', $plugin_admin, 'add_survey_row_meta' , 10 , 2 );

		// Sale Banner
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'ays_survey_sale_baner', 1 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Survey_Maker_Public( $this->get_plugin_name(), $this->get_version() );

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$plugin_public_submissions_summary 	= new Survey_Maker_Submissions_Summary( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_extra_shortcodes = new Ays_Survey_Maker_Extra_Shortcodes_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_most_popular_shortcodes = new Ays_Survey_Maker_Most_Popular_Shortcodes_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_public_survey_links_by_category_shortcodes = new Ays_Survey_Maker_Links_By_Category_Shortcodes_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles_early' );
		
        // Public AJAX action
        $this->loader->add_action( 'wp_ajax_ays_survey_ajax', $plugin_public, 'ays_survey_ajax' );
        $this->loader->add_action( 'wp_ajax_nopriv_ays_survey_ajax', $plugin_public, 'ays_survey_ajax' );

	}

	/**
	 * Register all of the hooks related to the integrations functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_integrations_hooks() {
		
		$plugin_integrations = new Survey_Maker_Integrations( $this->get_plugin_name(), $this->get_version() );

		// Survey Maker Integrations / survey page
		$this->loader->add_action( 'ays_sm_survey_page_integrations', $plugin_integrations, 'ays_survey_page_integrations_content' );		
		
		// Survey Maker Integrations / settings page
		$this->loader->add_action( 'ays_sm_settings_page_integrations', $plugin_integrations, 'ays_settings_page_integrations_content' );

		// ===== MailChimp integration ====
		// MailChimp integration / survey page
		$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_mailchimp_content', 1, 2 );

		// MailChimp integration / settings page
		$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_mailchimp_content', 1, 2 );

		// ===== Campaign Monitor integration =====
		// Campaign Monitor integration / survey page
		$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_camp_monitor_content', 1, 2 );

		// Campaign Monitor integration / settings page
		$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_campaign_monitor_content', 1, 2 );

		// ===== Zapier integration =====
		// Zapier integration / survey page
        $this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_zapier_content', 1, 2 );

		// Zapier integration / settings page
        $this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_zapier_content', 1, 2 );

		// ===== Active Campaign integration =====
		// Active Campaign integration / survey page
		$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_active_camp_content', 1, 2 );

		// Active Campaign integration / settings page
		$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_active_camp_content', 1, 2 );
		
		// ===== Slack integration =====
		// Slack integration / survey page
		$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_slack_content', 1, 2 );

		// Slack integration / settings page
		$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_slack_content', 1, 2 );

		// ===== Google integration =====
		// Google integration / survey page
		$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_google_sheet_content', 1, 2 );

		// Google integration / settings page
		$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_google_content', 1, 2 );

		// ===== GamiPress integration =====
			// GamiPress integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_gamipress_content', 80, 2 );
		// ===== GamiPress integration =====

        // ===== SendGrid integration =====
			// SendGrid integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_sendgrid_content', 70, 2 );
        // ===== SendGrid integration =====

        // ===== Madmimi integration =====
			// Madmimi integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_mad_mimi_content', 90, 2 );
			// Madmimi integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_mad_mimi_content', 1, 2 );
        // ===== Madmimi integration =====

        // ===== GetResponse integration =====
			// GetResponse integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_get_response_content', 100, 2 );
			// GetResponse integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_get_response_content', 1, 2 );
        // ===== GetResponse integration =====

        // ===== ConvertKit integration =====
			// ConvertKit integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_convert_kit_content', 110, 2 );
			// ConvertKit integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_convert_kit_content', 1, 2 );
        // ===== ConvertKit integration =====

		// // ===== PayPal integration ====
		 	// PayPal integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_PayPal_content', 125, 2 );

			// PayPal integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_PayPal_content', 125, 2 );
		// // ===== PayPal integration ====
				 
		// // ===== Stripe integration ====
			// Stripe integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_Stripe_content', 130, 2 );
				 
			// Stripe integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_Stripe_content', 130, 2 );
		// // ===== Stripe integration ====

		// // ===== reCAPTCHA integration ====
			// reCAPTCHA integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_recaptcha_content', 135, 2 );

			// reCAPTCHA integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_recaptcha_content', 135, 2 );
		// // ===== reCAPTCHA integration ====

		// // ===== Aweber integration ====
			// Aweber integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_aweber_settings_page_content', 140, 2 );

			// Aweber integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_aweber_content', 140, 2 );
		// // ===== Aweber integration ====

		// // ===== MailPoet integration ====
			// MailPoet integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_mailpoet_content', 145, 2 );

			// MailPoet integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_mailpoet_content', 145, 2 );
		// // ===== MailPoet integration ====

		// // ===== MyCred integration ====
			// MyCred integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_mycred_settings_page_content', 150, 2 );
		// // ===== MyCred integration ====
		
		// // ===== Klaviyo integration ====
			// Klaviyo integration / settings page
			$this->loader->add_filter( 'ays_sm_settings_page_integrations_contents', $plugin_integrations, 'ays_settings_page_klaviyo_content', 155, 2 );

			// Klaviyo integration / survey page
			$this->loader->add_filter( 'ays_sm_survey_page_integrations_contents', $plugin_integrations, 'ays_survey_page_klaviyo_content', 155, 2 );
		// // ===== Klaviyo integration ====


	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Survey_Maker_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
