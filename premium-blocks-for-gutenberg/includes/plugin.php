<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define class 'PBG_Plugin' if not Exists
if ( ! class_exists( 'PBG_Plugin' ) ) {

	/**
	 * Define PBG_Plugin class
	 */
	class PBG_Plugin {

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance = null;

		/**
		 * Premium Addons Settings Page Slug
		 *
		 * @var page_slug
		 */
		protected $page_slug = 'pb_panel';

		/**
		 * Constructor for the class
		 */
		public function __construct() {
			 // Enqueue the required files
			$this->pbg_setup();
			add_filter( 'plugin_action_links_' . PREMIUM_BLOCKS_BASENAME, array( $this, 'add_action_links' ), 10, 2 );
			add_action( 'plugins_loaded', array( $this, 'load_plugin' ) );

			if ( ! $this->is_gutenberg_active() ) {
				return;
			}
		}

		/*
		 * Triggers initial functions
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return void
		 */
		public function pbg_setup() {
			$this->load_domain();

			$this->init_files();
		}

		public function add_action_links( $links ) {
			$new_links[] = sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'admin.php?page=' . $this->page_slug . '&path=welcome' ), __( 'Settings', 'premium-blocks-for-gutenberg' ) );

			return $new_links + $links;
		}

		/*
		 * Load Premium Block for Gutenberg text domain
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return void
		 */
		public function load_domain() {
			 load_plugin_textdomain( 'premium-blocks-for-gutenberg', false, dirname( PREMIUM_BLOCKS_BASENAME ) . '/languages/' );
		}

		/*
		 * Load necessary files
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return void
		 */
		public function load_plugin() {
			 require_once PREMIUM_BLOCKS_PATH . 'includes/premium-blocks-css.php';
		}

		/**
		 * Setup the post select API endpoint.
		 *
		 * @return void
		 */
		public function is_gutenberg_active() {
			 return function_exists( 'register_block_type' );
		}

		/**
		 * Init files
		 *
		 * @return void
		 */
		public function init_files() {
			require_once PREMIUM_BLOCKS_PATH . 'classes/class-pbg-assets-generator.php';

			if ( is_admin() ) {
				require_once PREMIUM_BLOCKS_PATH . 'admin/includes/rollback.php';
			}
			require_once PREMIUM_BLOCKS_PATH . 'admin/includes/pb-panel/class-pb-panel.php';

			require_once PREMIUM_BLOCKS_PATH . 'classes/class-pbg-blocks-helper.php';

			require_once PREMIUM_BLOCKS_PATH . 'classes/class-pbg-blocks-integrations.php';

			require_once PREMIUM_BLOCKS_PATH . 'includes/googe-fonts/class-pbg-webfont-loader.php';

			require_once PREMIUM_BLOCKS_PATH . 'includes/googe-fonts/class-pbg-fonts.php';

			$settings = apply_filters( 'pb_settings', get_option( 'pbg_blocks_settings', array() ) );

			if ( isset( $settings['enable-post-editor-sidebar'] ) && $settings['enable-post-editor-sidebar'] ) {
				require_once PREMIUM_BLOCKS_PATH . 'global-settings/class-pbg-global-settings.php';
			}
		}

		/**
		 * Creates and returns an instance of the class
		 *
		 * @since 1.0.0
		 * @access public
		 * return object
		 */
		public static function get_instance() {
			if ( self::$instance == null ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}
}

if ( ! function_exists( 'pbg_plugin' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	function pbg_plugin() {
		 return PBG_Plugin::get_instance();
	}
}

pbg_plugin();
