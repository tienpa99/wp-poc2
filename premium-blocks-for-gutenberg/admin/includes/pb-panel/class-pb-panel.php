<?php

/**
 * Panel
 *
 * @package Pb Addons
 */

define( 'PREMIUM_BLOCKS_PANEL_DIR', PREMIUM_BLOCKS_PATH . 'admin/includes/pb-panel/' );
define( 'PREMIUM_BLOCKS_PANEL_URL', PREMIUM_BLOCKS_URL . 'admin/includes/pb-panel/' );

if ( ! class_exists( 'Pb_Panel' ) ) {

	/**
	 * Pb Panel
	 *
	 * @since 1.0.0
	 */
	class Pb_Panel {

		/**
		 * Default values
		 *
		 * @var array defaults
		 */
		private $defaults = array();

		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;
		private $menu_slug = 'pb_panel';
		/**
		 * Instance
		 *
		 * @return object
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 *  Constructor
		 */
		public function __construct() {
			 add_action( 'wp_ajax_nopriv_pb-panel-update-option', array( $this, 'update_option' ) );

			add_action( 'wp_ajax_pb-panel-update-option', array( $this, 'update_option' ) );

			add_action( 'wp_ajax_nopriv_pb-panel-update-settings', array( $this, 'update_settings' ) );

			add_action( 'wp_ajax_pb-panel-update-settings', array( $this, 'update_settings' ) );

			add_action( 'wp_ajax_nopriv_pb-panel-update-global-features', array( $this, 'update_global_features' ) );

			add_action( 'wp_ajax_pb-panel-update-global-features', array( $this, 'update_global_features' ) );

			add_action( 'wp_ajax_pb-panel-update-performance-options', array( $this, 'update_performance_options' ) );

			add_action( 'admin_menu', array( $this, 'register_custom_menu_page' ), 100 );
			add_filter( 'pb_options', array( $this, 'add_default_options' ) );
			add_filter( 'pb_settings', array( $this, 'add_default_setings' ) );
			add_filter( 'pb_global_features', array( $this, 'add_default_features' ) );
			add_filter( 'pb_performance_options', array( $this, 'add_default_performance_options' ) );
			add_action( 'admin_post_premium_gutenberg_rollback', array( $this, 'post_premium_gutenberg_rollback_new' ) );
			add_action( 'wp_ajax_pb-mail-subscribe', array( $this, 'subscribe_mail' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'pa_admin_page_scripts' ) );
			add_filter( 'plugin_action_links_' . PREMIUM_BLOCKS_BASENAME, array( $this, 'add_action_links' ) );
		}

		// Enqueue dashboard menu required assets
		// Enqueue icon for plugin in dashboard
		public function pa_admin_page_scripts() {
			wp_enqueue_style( 'pbg-icon', PREMIUM_BLOCKS_URL . 'admin/assets/pbg-font/css/pbg-font.css' );
		}
		public function add_action_links( $links ) {
			$default_url = admin_url( 'page=' . $this->menu_slug );

			$mylinks = array(
				'<a href="' . $default_url . '">' . '</a>',
			);

			return array_merge( $mylinks, $links );
		}
		public function subscribe_mail() {
			check_ajax_referer( 'pb-panel', 'nonce' );

			$email = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';

			$api_url = 'https://kemet.io/wp-json/mailchimp/v2/add';
			$request = add_query_arg(
				array(
					'email' => $email,
				),
				$api_url
			);

			$response = wp_remote_get(
				$request,
				array(
					'timeout'   => 60,
					'sslverify' => true,
				)
			);

			$body = wp_remote_retrieve_body( $response );
			$body = json_decode( $body, true );

			wp_send_json_success( $body );
		}
		public function add_default_options( $options ) {
			$default_options = array(
				'accordion'        => true,
				'banner'           => true,
				// 'button'           => true,
				'count-up'         => true,
				'dual-heading'     => true,
				'heading'          => true,
				'icon'             => true,
				'icon-box'         => true,
				'maps'             => true,
				'pricing-table'    => true,
				'section'          => true,
				'testimonials'     => true,
				'video-box'        => true,
				'fancy-text'       => true,
				'lottie'           => true,
				'Modal'            => true,
				'image-separator'  => true,
				'bullet-list'      => true,
				'person'           => true,
				'container'        => true,
				'content-switcher' => true,
				'buttons'          => true,
				'instagram-feed'   => true,
			);

			return array_merge( $default_options, $options );
		}

		public function add_default_setings( $options ) {
			$default_options = array(
				'premium-map-key'            => '',
				'premium-map-api'            => true,
				'premium-fa-css'             => true,
				'premium-upload-json'        => false,
				'enable-post-editor-sidebar' => true,
				'enable-site-editor-sidebar' => false,
				'generate-assets-files'      => false,
			);

			return array_merge( $default_options, $options );
		}

		/**
		 * Global Features Default Values.
		 *
		 * @param  array $options
		 * @return array
		 */
		public function add_default_features( $options ) {
			$default_options = array(
				'premium-equal-height'                  => true,
				'premium-entrance-animation'            => true,
				'premium-entrance-animation-all-blocks' => false,
			);

			return array_merge( $default_options, $options );
		}

		/**
		 * Global Performance Default Values.
		 *
		 * @param  array $options
		 * @return array
		 */
		public function add_default_performance_options( $options ) {
			$default_options = array(
				'premium-load-fonts-locally'   => false,
				'premium-preload-local-fonts'  => false,
				'premium-enable-allowed-fonts' => true,
				'premium-google-fonts'         => array(),
			);

			return array_merge( $default_options, $options );
		}

		/**
		 * update_option
		 *
		 * @return void
		 */
		public function update_option() {
			check_ajax_referer( 'pb-panel', 'nonce' );

			$value   = isset( $_POST['value'] ) ? json_decode( stripslashes( $_POST['value'] ), true ) : array();
			$options = apply_filters( 'pb_options', get_option( 'pb_options', array() ) );
			// $options = get_option( 'pb_options' );
			$options = ! is_array( $options ) ? array() : $options;

			if ( $value ) {
				$options = $value;
				update_option( 'pb_options', $options );

				wp_send_json_success(
					array(
						'success' => true,
						'values'  => $options,
					)
				);
			}

			wp_send_json_error();
		}

		/**
		 * Update Global Feature with ajax.
		 *
		 * @return void
		 */
		public function update_global_features() {
			check_ajax_referer( 'pb-panel', 'nonce' );

			$value    = isset( $_POST['value'] ) ? json_decode( stripslashes( $_POST['value'] ), true ) : array();
			$Settings = apply_filters( 'pb_settings', get_option( 'pbg_global_features', array() ) );
			$Settings = ! is_array( $Settings ) ? array() : $Settings;

			if ( $value ) {
				$Settings = $value;
				update_option( 'pbg_global_features', $Settings );

				wp_send_json_success(
					array(
						'success' => true,
						'setting' => $Settings,
					)
				);
			}

			wp_send_json_error();
		}

		public function update_settings() {
			 check_ajax_referer( 'pb-panel', 'nonce' );

			$value    = isset( $_POST['value'] ) ? json_decode( stripslashes( $_POST['value'] ), true ) : array();
			$Settings = apply_filters( 'pb_settings', get_option( 'pbg_blocks_settings', array() ) );
			// $options = get_option( 'pb_options' );
			$Settings = ! is_array( $Settings ) ? array() : $Settings;

			if ( $value ) {
				$Settings = $value;
				update_option( 'pbg_blocks_settings', $Settings );

				wp_send_json_success(
					array(
						'success' => true,
						'setting' => $Settings,
					)
				);
			}

			wp_send_json_error();
		}

		/**
		 * Update Performance Options with ajax.
		 *
		 * @return void
		 */
		public function update_performance_options() {
			check_ajax_referer( 'pb-panel', 'nonce' );

			$value    = isset( $_POST['value'] ) ? json_decode( stripslashes( $_POST['value'] ), true ) : array();
			$Settings = apply_filters( 'pb_performance_options', get_option( 'pbg_performance_options', array() ) );
			$Settings = ! is_array( $Settings ) ? array() : $Settings;

			if ( $value ) {
				$Settings = $value;
				update_option( 'pbg_performance_options', $Settings );

				wp_send_json_success(
					array(
						'success' => true,
						'setting' => $Settings,
					)
				);
			}

			wp_send_json_error();
		}

		/**
		 * Add Premium Blocks panel menu
		 *
		 * @return void
		 */
		public function register_custom_menu_page() {
			$page = add_menu_page(
				__( 'Premium Blocks', 'premium-blocks-for-gutenberg' ),
				__( 'Premium Blocks', 'premium-blocks-for-gutenberg' ),
				'manage_options',
				'pb_panel',
				array( $this, 'render' ),
				null
			);
			if ( ! defined( 'PREMIUM_ADMIN_PAGE' ) ) {
				define( 'PREMIUM_ADMIN_PAGE', $page );
			}
			add_action( 'admin_print_styles-' . $page, array( $this, 'enqueue_admin_script' ) );
		}

		/**
		 * Render panel html
		 *
		 * @return void
		 */
		public function render() {
			?>
			<div id="pb-dashboard"></div>
			<?php
		}

		/**
		 * Get system info
		 *
		 * @return array
		 */
		public static function get_system_info() {
			global $wpdb;
			$pb_versions = self::get_rollback_versions_options();

			$info = array(

				'home_url'             => home_url(),
				'version'              => get_bloginfo( 'version' ),
				'multisite'            => is_multisite(),
				'memory_limit'         => wp_convert_hr_to_bytes( WP_MEMORY_LIMIT ),
				'memory_limit_size'    => size_format( wp_convert_hr_to_bytes( WP_MEMORY_LIMIT ) ),
				'theme_version'        => esc_html( PREMIUM_BLOCKS_VERSION ),
				'previous_version'     => esc_html( PREMIUM_BLOCKS_STABLE_VERSION ),
				'wp_path'              => esc_html( ABSPATH ),
				'debug'                => defined( 'WP_DEBUG' ) && WP_DEBUG,
				'lang'                 => esc_html( get_locale() ),
				'server'               => isset( $_SERVER['SERVER_SOFTWARE'] ) ? esc_html( sanitize_text_field( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) ) : '',
				'php_version'          => function_exists( 'phpversion' ) ? phpversion() : '',
				'mysql_version'        => $wpdb->db_version(),
				'max_upload'           => esc_html( size_format( wp_max_upload_size() ) ),
				'ini_get'              => function_exists( 'ini_get' ),
				'pb_previous_versions' => $pb_versions,
				'rollback_url_new'     => str_replace( array( '&#038;', '&amp;' ), '&', esc_url( add_query_arg( 'version', 'VERSION', wp_nonce_url( admin_url( 'admin-post.php?action=premium_gutenberg_rollback' ), 'premium_gutenberg_rollback' ) ) ) ),
			);
			if ( function_exists( 'ini_get' ) ) {
				$info['php_memory_limit']   = esc_html( size_format( wp_convert_hr_to_bytes( ini_get( 'memory_limit' ) ) ) );
				$info['post_max_size']      = esc_html( size_format( wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) ) );
				$info['max_execution_time'] = ini_get( 'max_execution_time' );
				$info['max_input_vars']     = esc_html( ini_get( 'max_input_vars' ) );
				$info['suhosin']            = extension_loaded( 'suhosin' );
			}

			$active_plugins = (array) get_option( 'active_plugins', array() );
			$plugins        = array();
			if ( is_multisite() ) {
				$network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
				$active_plugins            = array_merge( $active_plugins, $network_activated_plugins );
			}

			foreach ( $active_plugins as $plugin ) {
				$plugin_data    = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
				$dirname        = dirname( $plugin );
				$version_string = '';
				$network_string = '';

				if ( ! empty( $plugin_data['Name'] ) ) {
					$plugins[ $plugin ] = array(
						'name'    => $plugin_data['Name'],
						'author'  => $plugin_data['Author'],
						'version' => $plugin_data['Version'],

					);

					if ( ! empty( $plugin_data['PluginURI'] ) ) {
						$plugins[ $plugin ]['PluginURI'] = $plugin_data['PluginURI'];
					}
				}
			}

			$info['active_plugins'] = $plugins;

			return $info;
		}
		/**
		 * panel_options
		 *
		 * @return array
		 */
		public static function panel_options() {
			$options = array(
				'accordion'        => array(
					'type'     => 'pb-button',
					'label'    => __( 'Accordion', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'accordion',
					'category' => array(
						'all',
						'content',
					),
				),
				'banner'           => array(
					'type'     => 'pb-button',
					'label'    => __( 'Banner', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'banner',
					'category' => array(
						'all',
						'marketing',
					),
				),
				'buttons'          => array(
					'type'     => 'pb-button',
					'label'    => __( 'Buttons', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'button',
					'category' => array(
						'all',
						'creative',
						'marketing',
						'content',
					),
				),
				'count-up'         => array(
					'type'     => 'pb-button',
					'label'    => __( 'Count Up', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'count_up',
					'category' => array(
						'all',
						'creative',
					),
				),
				'dual-heading'     => array(
					'type'     => 'pb-button',
					'label'    => __( 'Dual Heading', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'dualHeading',
					'category' => array(
						'all',
						'creative',
					),
				),
				'heading'          => array(
					'type'     => 'pb-button',
					'label'    => __( 'Heading', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'heading',
					'category' => array(
						'all',
						'content',
					),
				),
				'icon'             => array(
					'type'     => 'pb-button',
					'label'    => __( 'Icon', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'icon',
					'category' => array(
						'all',
						'creative',
					),
				),
				'icon-box'         => array(
					'type'     => 'pb-button',
					'label'    => __( 'Icon Box', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'icon_box',
					'category' => array(
						'all',
						'creative',
					),
				),
				'maps'             => array(
					'type'     => 'pb-button',
					'label'    => __( 'Google Maps', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'maps',
					'category' => array(
						'all',
						'content',
					),
				),
				'pricing-table'    => array(
					'type'     => 'pb-button',
					'label'    => __( 'Pricing Table', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'pricingTable',
					'category' => array(
						'all',
						'marketing',
					),
				),
				'section'          => array(
					'type'     => 'pb-button',
					'label'    => __( 'Section', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'section',
					'category' => array(
						'all',
						'section',
					),
				),
				'testimonials'     => array(
					'type'     => 'pb-button',
					'label'    => __( 'Testimonials', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'testimonials',
					'category' => array(
						'all',
						'creative',
						'content',
					),
				),
				'video-box'        => array(
					'type'     => 'pb-button',
					'label'    => __( 'Video Box', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'video_box',
					'category' => array(
						'all',
						'creative',
						'marketing',
					),
				),
				'fancy-text'       => array(
					'type'     => 'pb-button',
					'label'    => __( 'Fancy Text', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'fancyText',
					'category' => array(
						'all',
						'content',
						'creative',
					),
				),
				'lottie'           => array(
					'type'     => 'pb-button',
					'label'    => __( 'Lottie Animation', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'lottie',
					'category' => array(
						'all',
						'creative',
					),
				),
				'Modal'            => array(
					'type'     => 'pb-button',
					'label'    => __( 'Modal Box', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'modal',
					'category' => array(
						'all',
						'creative',
						'content',
					),
				),
				'image-separator'  => array(
					'type'     => 'pb-button',
					'label'    => __( 'Image Separator', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'image_separator',
					'category' =>
					array(
						'all',
						'content',
					),
				),
				'bullet-list'      => array(
					'type'     => 'pb-button',
					'label'    => __( 'Bullet List', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'bulletList',
					'category' =>
					array(
						'all',
						'content',
					),
				),
				'person'           => array(
					'type'     => 'pb-button',
					'label'    => __( 'Team Members', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'person',
					'category' => array(
						'all',
						'content',
					),
				),
				'container'        => array(
					'type'     => 'pb-button',
					'label'    => __( 'Container', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'container',
					'category' => array(
						'all',
						'content',
						'section',
					),
				),
				'content-switcher' => array(
					'type'     => 'pb-button',
					'label'    => __( 'Content Switcher', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'content_switcher',
					'category' => array(
						'all',
						'content',
						'creative',
					),
				),
				'instagram-feed'   => array(
					'type'     => 'pb-button',
					'label'    => __( 'Instagram Feed', 'premium-blocks-for-gutenberg' ),
					'icon'     => 'instagram_feed_icon',
					'category' => array(
						'all',
						'creative',
						'marketing',
					),
				),
			);
			return apply_filters( 'pb_panel_options', $options );
		}

		/**
		 * Enqueue a script in the WordPress admin on edit.php
		 *
		 * @return void
		 */
		public function enqueue_admin_script() {
			wp_enqueue_style( 'pb-panel-css', PREMIUM_BLOCKS_PANEL_URL . 'assets/js/build/index.css', false, PREMIUM_BLOCKS_VERSION );
			wp_enqueue_script(
				'pb-panel-js',
				PREMIUM_BLOCKS_PANEL_URL . 'assets/js/build/index.js',
				array(
					'wp-i18n',
					'wp-components',
					'wp-element',
					'wp-media-utils',
					'wp-block-editor',
					'wp-data',
					'wp-core-data',
				),
				PREMIUM_BLOCKS_VERSION,
				true
			);

			wp_localize_script(
				'pb-panel-js',
				'PremiumBlocksPanelData',
				array(
					'home_slug'          => $this->menu_slug,
					'options'            => self::panel_options(),
					'values'             => apply_filters( 'pb_options', get_option( 'pb_options', array() ) ),
					'ajaxurl'            => admin_url( 'admin-ajax.php' ),
					'nonce'              => wp_create_nonce( 'pb-panel' ),
					'system_info'        => self::get_system_info(),
					'images_url'         => PREMIUM_BLOCKS_PANEL_URL . 'assets/images/',
					'apiData'            => apply_filters( 'pb_settings', get_option( 'pbg_blocks_settings', array() ) ),
					'isBlockTheme'       => wp_is_block_theme(),
					'globalFeatures'     => apply_filters( 'pb_global_features', get_option( 'pbg_global_features', array() ) ),
					'performanceOptions' => apply_filters( 'pb_performance_options', get_option( 'pbg_performance_options', array() ) ),
				)
			);
		}
		public function post_premium_gutenberg_rollback_new() {
			 check_admin_referer( 'premium_gutenberg_rollback' );
			$plugin_slug    = basename( PREMIUM_BLOCKS_FILE, '.php' );
			$update_version = sanitize_text_field( $_GET['version'] );

			$pbg_rollback = new PBG_Rollback(
				array(
					'version'     => $update_version,
					'plugin_name' => PREMIUM_BLOCKS_BASENAME,
					'plugin_slug' => $plugin_slug,
					'package_url' => sprintf( 'https://downloads.wordpress.org/plugin/%s.%s.zip', $plugin_slug, $update_version ),
				)
			);

			$pbg_rollback->run();

			wp_die(
				'',
				__( 'Rollback to Previous Version', 'premium-gutenberg' ),
				array(
					'response' => 200,
				)
			);
		}

		/**
		 * Get Rollback versions.
		 *
		 * @since 1.23.0
		 * @return array
		 * @access public
		 */
		public static function get_rollback_versions() {
			$rollback_versions = get_transient( 'pb_rollback_versions_' . PREMIUM_BLOCKS_VERSION );

			if ( empty( $rollback_versions ) ) {

				$max_versions = 10;

				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

				$plugin_information = plugins_api(
					'plugin_information',
					array(
						'slug' => 'Premium-blocks-for-gutenberg',
					)
				);

				if ( empty( $plugin_information->versions ) || ! is_array( $plugin_information->versions ) ) {
					return array();
				}

				krsort( $plugin_information->versions );

				$rollback_versions = array();

				foreach ( $plugin_information->versions as $version => $download_link ) {

					$lowercase_version = strtolower( $version );

					$is_valid_rollback_version = ! preg_match( '/(trunk|beta|rc|dev)/i', $lowercase_version );

					if ( ! $is_valid_rollback_version ) {
						continue;
					}

					$rollback_versions[] = $version;
				}

				$rollback_versions = array_slice( $rollback_versions, 0, $max_versions, true );

				set_transient( 'pb_rollback_versions_' . PREMIUM_BLOCKS_VERSION, $rollback_versions, WEEK_IN_SECONDS );
			}

			return $rollback_versions;
		}

		public static function get_rollback_versions_options() {
			$rollback_versions = self::get_rollback_versions();

			$rollback_versions_options = array();

			foreach ( $rollback_versions as $version ) {

				$version = array(
					'label' => $version,
					'value' => $version,

				);

				$rollback_versions_options[] = $version;
			}

			return $rollback_versions_options;
		}
	}
	Pb_Panel::get_instance();
}
