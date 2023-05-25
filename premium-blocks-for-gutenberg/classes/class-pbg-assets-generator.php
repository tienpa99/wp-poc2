<?php
/**
 * Generator Class
 *
 * @package     Pbg
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Pbg_Assets_Generator' ) ) {

	/**
	 * Pbg_Merged_Style
	 */
	class Pbg_Assets_Generator {

		/**
		 * Css files
		 *
		 * @var array
		 */
		protected $css_files = array();

		/**
		 * Inline css
		 *
		 * @var string
		 */
		protected $inline_css = '';

		/**
		 * Merged style
		 *
		 * @var string
		 */
		protected $merged_style = '';

		/**
		 * Prefix
		 *
		 * @var string
		 */
		protected $prefix = '';

		/**
		 * Post id
		 *
		 * @var string
		 */
		protected $post_id = '';

		/**
		 * Constructor
		 */
		public function __construct( $prefix ) {
			$this->prefix = $prefix;
		}

		/**
		 * Mifiy css
		 *
		 * @param string $css
		 * @return string
		 */
		function minify_css( $css ) {
			$css = preg_replace( '/\s+/', ' ', $css ); // Remove extra spaces
			$css = preg_replace( '/\/\*(.*?)\*\//', '', $css ); // Remove comments
			$css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css ); // Remove newlines and tabs

			return $css;
		}

		/**
		 * Get inline css
		 *
		 * @return mixed
		 */
		public function get_inline_css() {
			$css_files    = $this->get_css_files();
			$files_count  = count( $css_files );
			$merged_style = '';

			/* new */
			if ( $files_count > 0 ) {
				foreach ( $css_files as $k => $file ) {
					require_once ABSPATH . 'wp-admin/includes/file.php'; // We will probably need to load this file.
					global $wp_filesystem;
					WP_Filesystem(); // Initial WP file system.
					$merged_style .= $wp_filesystem->get_contents( PREMIUM_BLOCKS_PATH . $file );
				}
			}

			// Inline css.
			$merged_style .= $this->inline_css;

			if ( ! empty( $merged_style ) ) {
				return $this->minify_css( $merged_style );
			} else {
				return false;
			}
		}

		/**
		 * Css url
		 *
		 * @return mixed
		 */
		public function get_css_url() {
			$merged_style = $this->get_inline_css();
			if ( ! $merged_style ) {
				return false;
			}

			require_once ABSPATH . 'wp-admin/includes/file.php'; // We will probably need to load this file.
			global $wp_filesystem;
			WP_Filesystem(); // Initial WP file system.
			$upload_dir = wp_upload_dir(); // Grab uploads folder array.
			$dir        = trailingslashit( $upload_dir['basedir'] ) . 'premium-blocks-for-gutenberg/'; // Set storage directory path.
			$wp_filesystem->mkdir( $dir ); // Make a new folder for storing our file.
			$wp_upload_dir = "{$upload_dir['baseurl']}/premium-blocks-for-gutenberg/";
			$date          = new DateTime();
			$unique_id     = $date->getTimestamp();
			$merged_file   = $this->post_id ? "{$dir}{$this->prefix}-style-{$unique_id}.css" : "{$dir}{$this->prefix}-style.css";
			if ( $this->post_id ) {
				$post_assets_path = get_post_meta( $this->post_id, 'pbg_post_assets_path', true );
				if ( $post_assets_path ) {
					$wp_filesystem->delete( "{$dir}{$post_assets_path}" );
				}
				update_post_meta( $this->post_id, 'pbg_post_assets_path', "{$this->prefix}-style-{$unique_id}.css" );
			}
			$wp_filesystem->put_contents( $merged_file, $merged_style, 0777 | 0644 ); // Finally, store the file :D.
			$merged_file_url = $this->post_id ? "{$wp_upload_dir}{$this->prefix}-style-{$unique_id}.css" : "{$wp_upload_dir}{$this->prefix}-style.css";

			return $merged_file_url;
		}

		/**
		 * Css files
		 *
		 * @return mixed
		 */
		public function get_css_files() {
			return apply_filters( 'pbg_add_css_file', $this->css_files );
		}

		/**
		 * Add css
		 *
		 * @param string  $src source.
		 * @param boolean $handle handle.
		 * @return void
		 */
		public function pbg_add_css( $src = null, $handle = false ) {
			if ( in_array( $src, $this->css_files ) ) {
				return;
			}
			if ( false != $handle ) {
				$this->css_files[ $handle ] = $src;
			} else {
				$this->css_files[] = $src;
			}
		}

		/**
		 * Add inline css
		 *
		 * @param string $css css.
		 * @return void
		 */
		public function add_inline_css( $css ) {
			$this->inline_css .= $css;
		}

		/**
		 * Get post id
		 *
		 * @return int
		 */
		public function set_post_id( $post_id ) {
			$this->post_id = $post_id;
		}

	}
}