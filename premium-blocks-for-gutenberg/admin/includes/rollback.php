<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! class_exists( 'PBG_Rollback' ) ) {

	/**
	 * Class PBG_Rollback.
	 */
	class PBG_Rollback {

		/**
		 * Plugin URL
		 *
		 * @var package_url
		 */
		protected $package_url;

		/**
		 * Plugin Version
		 *
		 * @var version
		 */
		protected $version;

		/**
		 * Plugin Name
		 *
		 * @var plugin_name
		 */
		protected $plugin_name;

		/**
		 * Plugin Slug
		 *
		 * @var plugin_slug
		 */
		protected $plugin_slug;

		/**
		 * Class instance
		 *
		 * @var instance
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 *
		 * @param array $args plugin args.
		 */
		public function __construct( $args = array() ) {
			foreach ( $args as $key => $value ) {
				$this->{$key} = $value;
			}
		}

		/**
		 * Print Inline Style
		 *
		 * Used to print inline style on rollback page
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private function print_inline_style() {
			?>
			<style>
				.wrap {
					overflow: hidden;
				}

				h1 {
					background: #6ec1e4;
					text-align: center;
					color: #fff !important;
					padding: 70px !important;
					text-transform: uppercase;
					letter-spacing: 1px;
				}
				h1 img {
					max-width: 300px;
					display: block;
					margin: auto auto 50px;
				}
			</style>
			<?php
		}

		/**
		 * Apply package
		 *
		 * @since 1.0.0
		 * @access private
		 */
		protected function apply_package() {
			$update_plugins = get_site_transient( 'update_plugins' );
			if ( ! is_object( $update_plugins ) ) {

				$update_plugins = new \stdClass();
			}

			$plugin_info              = new \stdClass();
			$plugin_info->new_version = $this->version;
			$plugin_info->slug        = $this->plugin_slug;
			$plugin_info->package     = $this->package_url;
			$plugin_info->url         = 'https://premiumblocks.io/';

			$update_plugins->response[ $this->plugin_name ] = $plugin_info;

			set_site_transient( 'update_plugins', $update_plugins );
		}

		/**
		 * Upgrade
		 *
		 * Rollback update
		 *
		 * @since 1.0.0
		 * @access private
		 */
		protected function upgrade() {
			require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';

			$logo_url = PREMIUM_BLOCKS_URL . 'admin/images/premium-blocks-logo.png';

			$upgrader_args = array(
				'url'    => 'update.php?action=upgrade-plugin&plugin=' . rawurlencode( $this->plugin_name ),
				'plugin' => $this->plugin_name,
				'nonce'  => 'upgrade-plugin_' . $this->plugin_name,
				'title'  => '<img src="' . $logo_url . '" alt="Premium Blocks">' . sprintf( 'Rolling Back to Previous Version' ),
			);

			$this->print_inline_style();

			$upgrader = new \Plugin_Upgrader( new \Plugin_Upgrader_Skin( $upgrader_args ) );
			$upgrader->upgrade( $this->plugin_name );
		}

		/**
		 * Run
		 *
		 * Trigger rollback functions
		 *
		 * @since 0.0.1
		 * @access private
		 */
		public function run() {
			$this->apply_package();
			$this->upgrade();
		}

		/**
		 *
		 * Creates and returns an instance of the class
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return object
		 */
		public static function get_instance() {

			if ( ! isset( self::$instance ) ) {

				self::$instance = new self();

			}

			return self::$instance;
		}

	}
}

if ( ! function_exists( 'pbg_rollback' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 *
	 * @since  1.1.1
	 * @return object
	 */
	function pbg_rollback() {
		return PBG_Rollback::get_instance();
	}
}
pbg_rollback();