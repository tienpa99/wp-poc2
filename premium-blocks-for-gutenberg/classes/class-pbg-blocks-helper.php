<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Define PBG_Blocks_Helper class
 */
class PBG_Blocks_Helper {

	/**
	 * Class instance
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 * Blocks
	 *
	 * @var blocks
	 */
	public static $blocks;

	/**
	 * Config
	 *
	 * @var config
	 */
	public static $config;

	/**
	 * Stylesheet
	 *
	 * @since 1.13.4
	 *
	 * @var stylesheet
	 */
	public static $stylesheet;

	/**
	 * Page Blocks Variable
	 *
	 * @since 1.6.0
	 *
	 * @var instance
	 */
	public static $page_blocks;

	/**
	 * Member Variable
	 *
	 * @since 0.0.1
	 *
	 * @var instance
	 */
	public static $block_atts;

	/**
	 * PBG Block Flag
	 *
	 * @since 1.8.2
	 *
	 * @var premium_flag
	 */
	public static $premium_flag = false;

	/**
	 * Current Block List
	 *
	 * @since 1.8.2
	 *
	 * @var current_block_list
	 */
	public static $current_block_list = array();

	/**
	 * Global features
	 *
	 * @since 1.8.2
	 *
	 * @var array
	 */
	public $global_features;

	/**
	 * Performance Settings
	 *
	 * @since 2.0.14
	 *
	 * @var array
	 */
	public $performance_settings;

	/**
	 * Blocks Frontend Assets
	 *
	 * @since 2.0.14
	 *
	 * @var Pbg_Assets_Generator
	 */
	public $blocks_frondend_assets;

	/**
	 * Blocks Frontend CSS Deps
	 *
	 * @since 2.0.14
	 *
	 * @var array
	 */
	public $blocks_frontend_css_deps = array();

	/**
	 * Constructor for the class
	 */
	public function __construct() {
		// Blocks Frontend Assets.
		$this->blocks_frondend_assets = new Pbg_Assets_Generator( 'frontend' );
		// Global Features.
		$this->global_features = apply_filters( 'pb_global_features', get_option( 'pbg_global_features', array() ) );
		// Performance Settings.
		$this->performance_settings = apply_filters( 'pb_performance_options', get_option( 'pbg_performance_options', array() ) );
		// Gets Active Blocks.
		self::$blocks = apply_filters( 'pb_options', get_option( 'pb_options', array() ) );
		// Gets Plugin Admin Settings.

		self::$config = apply_filters( 'pb_settings', get_option( 'pbg_blocks_settings', array() ) );
		$allow_json   = isset( self::$config['premium-upload-json'] ) ? self::$config['premium-upload-json'] : true;
		if ( $allow_json ) {
			add_filter( 'upload_mimes', array( $this, 'pbg_mime_types' ) ); // phpcs:ignore WordPressVIPMinimum.Hooks.RestrictedHooks.upload_mimes
			add_filter( 'wp_check_filetype_and_ext', array( $this, 'fix_mime_type_json' ), 75, 4 );
		}
		add_action( 'init', array( $this, 'on_init' ), 20 );
		// Enqueue Editor Assets.
		add_action( 'enqueue_block_editor_assets', array( $this, 'pbg_editor' ) );
		// Enqueue Frontend Styles.
		add_action( 'enqueue_block_assets', array( $this, 'pbg_frontend' ) );
		// Enqueue Frontend Scripts.
		add_action( 'enqueue_block_assets', array( $this, 'add_blocks_frontend_assets' ), 10 );
		// Register Premium Blocks category.
		add_filter( 'block_categories_all', array( $this, 'register_premium_category' ), 10, 2 );
		// Generate Blocks Stylesheet.
		// add_action( 'wp', array( $this, 'generate_stylesheet' ), 99 );
		add_action( 'wp_enqueue_scripts', array( $this, 'generate_stylesheet' ), 20 );
		// Enqueue Generated stylesheet to WP Head.
		add_action( 'wp_head', array( $this, 'print_stylesheet' ), 80 );

		add_action( 'wp_enqueue_scripts', array( $this, 'load_dashicons_front_end' ) );

		add_action( 'enqueue_block_editor_assets', array( $this, 'add_blocks_editor_styles' ) );

		add_filter( 'render_block_premium/container', array( $this, 'equal_height_front_script' ), 1, 2 );

		add_action( 'wp_head', array( $this, 'add_blocks_frontend_inline_styles' ), 90 );

		add_filter( 'render_block', array( $this, 'add_block_style' ), 9, 2 );
		add_filter( 'render_block', array( $this, 'register_block_animation' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_features_script' ), 10 );
		// Add custom breakpoints.
		add_filter( 'Premium_BLocks_mobile_media_query', array( $this, 'mobile_breakpoint' ), 1 );
		add_filter( 'Premium_BLocks_tablet_media_query', array( $this, 'tablet_breakpoint' ), 1 );
		add_filter( 'Premium_BLocks_desktop_media_query', array( $this, 'desktop_breakpoint' ), 1 );

		add_filter( 'render_block_premium/instagram-feed-posts', array( $this, 'instagram_front_script' ), 1, 2 );
	}

	/**
	 * PBG Frontend
	 *
	 * Enqueue Frontend Assets for Premium Blocks.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function instagram_front_script( $content, $block ) {
		if ( apply_filters( 'pbg_disable_instagram_script', false ) ) {
			return $content;
		}
		$media_query            = array();
		$media_query['mobile']  = apply_filters( 'Premium_BLocks_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet']  = apply_filters( 'Premium_BLocks_tablet_media_query', '(max-width: 1024px)' );
		$media_query['desktop'] = apply_filters( 'Premium_BLocks_tablet_media_query', '(min-width: 1025px)' );

		// View file after move it to "assets/js".
		wp_enqueue_script(
			'premium-instagram-feed-view',
			PREMIUM_BLOCKS_URL . 'assets/js/build/instagram-feed.js',
			array( 'wp-element', 'wp-i18n' ),
			PREMIUM_BLOCKS_VERSION,
			true
		);

		wp_localize_script(
			'premium-instagram-feed-view',
			'PBGPRO_InstaFeed',
			apply_filters(
				'premium_instagram_feed_localize_script',
				array(
					'ajaxurl'     => esc_url( admin_url( 'admin-ajax.php' ) ),
					'breakPoints' => $media_query,
					'pluginURL'   => PREMIUM_BLOCKS_URL,
				)
			)
		);
		return $content;
	}

	/**
	 * Get Premium Blocks Names
	 *
	 * @return array
	 */
	public function get_premium_blocks_names() {
		$blocks_array = array(
			'premium/accordion'             => 'accordion',
			'premium/banner'                => 'banner',
			'premium/bullet-list'           => 'bullet-list',
			'premium/countup'               => 'count-up',
			'premium/counter'               => 'counter',
			'premium/dheading-block'        => 'dual-heading',
			'premium/heading'               => 'heading',
			'premium/icon'                  => 'icon',
			'premium/icon-box'              => 'icon-box',
			'premium/maps'                  => 'maps',
			'premium/pricing-table'         => 'pricing-table',
			'premium/section'               => 'section',
			'premium/testimonial'           => 'testimonials',
			'premium/video-box'             => 'video-box',
			'premium/fancy-text'            => 'fancy-text',
			'premium/lottie'                => 'lottie',
			'premium/modal'                 => 'Modal',
			'premium/image-separator'       => 'image-separator',
			'premium/person'                => 'person',
			'premium/container'             => 'container',
			'premium/content-switcher'      => 'content-switcher',
			'premium/buttons'               => 'buttons',
			'premium/author'                => 'author',
			'premium/badge'                 => 'badge',
			'premium/button'                => 'button',
			'premium/icon-group'            => 'icon-group',
			'premium/image'                 => 'image',
			'premium/price'                 => 'price',
			'premium/switcher-child'        => 'switcher-child',
			'premium/text'                  => 'text',
			'premium/instagram-feed'        => 'instagram-feed',
			'premium/instagram-feed-header' => 'instagram-feed-header',
			'premium/instagram-feed-posts'  => 'instagram-feed-posts',
		);

		return $blocks_array;
	}

	/**
	 * Mobile breakpoint
	 *
	 * @param  string $breakpoint
	 * @return string
	 */
	public function mobile_breakpoint( $breakpoint ) {
		$layout_settings = get_option( 'pbg_global_layout', array() );
		$breakpoint      = isset( $layout_settings['mobile_breakpoint'] ) ? '(max-width: ' . $layout_settings['mobile_breakpoint'] . 'px)' : $breakpoint;

		return $breakpoint;
	}

	/**
	 * Tablet breakpoint
	 *
	 * @param  string $breakpoint
	 * @return string
	 */
	public function tablet_breakpoint( $breakpoint ) {
		$layout_settings = get_option( 'pbg_global_layout', array() );
		$breakpoint      = isset( $layout_settings['tablet_breakpoint'] ) ? '(max-width: ' . $layout_settings['tablet_breakpoint'] . 'px)' : $breakpoint;
		return $breakpoint;
	}

	/**
	 * Desktop breakpoint
	 *
	 * @param  string $breakpoint
	 * @return string
	 */
	public function desktop_breakpoint( $breakpoint ) {
		$layout_settings = get_option( 'pbg_global_layout', array() );
		$breakpoint      = isset( $layout_settings['tablet_breakpoint'] ) ? '(min-width: ' . ( $layout_settings['tablet_breakpoint'] + 1 ) . 'px)' : $breakpoint;
		return $breakpoint;
	}

	/**
	 * Generate assets files feature.
	 *
	 * @return bool
	 */
	public function generate_assets_files() {
		$global_settings = apply_filters( 'pb_settings', get_option( 'pbg_blocks_settings', array() ) );
		return $global_settings['generate-assets-files'] ?? true;
	}

	/**
	 * Add block css file to the frontend assets.
	 *
	 * @param string $src The css file url.
	 */
	public function add_block_css( $src, $dependencies = array() ) {
		$this->blocks_frondend_assets->pbg_add_css( $src );
		if ( ! empty( $dependencies ) ) {
			$this->blocks_frontend_css_deps = array_merge( $this->blocks_frontend_css_deps, $dependencies );
		}
	}

	/**
	 * Add inline css to the frontend assets.
	 */
	public function add_blocks_frontend_inline_styles() {
		if ( $this->generate_assets_files() ) {
			return;
		}
		$this->add_css();
		$this->blocks_frondend_assets->add_inline_css( $this->get_custom_block_css() );
		$inline_css = $this->blocks_frondend_assets->get_inline_css();

		if ( ! empty( $inline_css ) ) {
			echo '<style id="pbg-blocks-frontend-inline-css">' . $inline_css . '</style>';
		}
	}

	/**
	 * Add css.
	 *
	 * @return void
	 */
	public function add_css() {
		 // Add block css file to the frontend assets.
		$blocks_names = $this->get_premium_blocks_names();
		foreach ( $blocks_names as $name => $slug ) {
			if ( ! $this->has_block( $name ) ) {
				continue;
			}

			if ( ! file_exists( PREMIUM_BLOCKS_PATH . "assets/css/minified/{$slug}.min.css" ) ) {
				continue;
			}

			$this->add_block_css( "assets/css/minified/{$slug}.min.css" );
		}
	}

	/**
	 * Enqueue frontend assets.
	 */
	public function add_blocks_frontend_assets() {
		if ( ! $this->generate_assets_files() ) {
			return;
		}
		$this->add_css();
		$this->blocks_frondend_assets->set_post_id( get_the_ID() );
		$this->blocks_frondend_assets->add_inline_css( $this->get_custom_block_css() );
		$css_url = $this->blocks_frondend_assets->get_css_url();

		if ( ! empty( $css_url ) ) {
			wp_enqueue_style( 'pbg-blocks-frontend-css', $css_url, array_values( $this->blocks_frontend_css_deps ), PREMIUM_BLOCKS_VERSION );
		}
	}

	/**
	 * Check if the page has a specific block.
	 *
	 * @param int    $post_id
	 * @param string $block_name
	 *
	 * @return bool
	 */
	function has_block( $block_name ) {
		$post_id = get_the_ID();
		if ( ! $post_id ) {
			return false;
		}
		$post_blocks = parse_blocks( get_post( $post_id )->post_content );

		foreach ( $post_blocks as $block ) {
			if ( $block['blockName'] === $block_name ) {
				return true;
			}

			if ( ! empty( $block['innerBlocks'] ) ) {
				$has_block = $this->check_inner_blocks( $block['innerBlocks'], $block_name );
				if ( $has_block ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Check inner blocks.
	 *
	 * @param array  $inner_blocks
	 * @param string $block_name
	 *
	 * @return bool
	 */
	function check_inner_blocks( $inner_blocks, $block_name ) {
		foreach ( $inner_blocks as $inner_block ) {
			if ( $inner_block['blockName'] === $block_name ) {
				return true;
			}

			if ( ! empty( $inner_block['innerBlocks'] ) ) {
				$has_block = $this->check_inner_blocks( $inner_block['innerBlocks'], $block_name );
				if ( $has_block ) {
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Add the clientId attribute to the block element.
	 *
	 * @param string $block_content The HTML content of the block.
	 * @param array  $block The block data.
	 *
	 * @return string The updated HTML content of the block.
	 */
	public function add_block_style( $block_content, $block ) {
		if ( $this->is_premium_block( $block['blockName'] ) ) {
			// Find the style tag and its contents.
			preg_match( '/<style(?:\s+(?:class|id)="[^"]*")*>(.*?)<\/style>/s', $block_content, $matches );

			// If a style tag was found, store its contents.
			if ( ! empty( $matches ) ) {
				$style_content = $matches[1];
				$this->add_custom_block_css( $style_content );
				// Remove all style tags and their contents from the block content.
				$block_content = preg_replace( '/<style(?:\s+(?:class|id)="[^"]*")*>(.*?)<\/style>/s', '', $block_content );
			}
		}

		return $block_content;
	}

	/**
	 * Check if any value in an array is not empty.
	 *
	 * @param array $array An array of key-value pairs to check for non-empty values.
	 * @return bool Whether any of the values in the array are not empty.
	 */
	public function check_if_any_value_not_empty( $array ) {
		if ( ! is_array( $array ) ) {
			return false;
		}
		foreach ( $array as $key => $value ) {
			if ( ! empty( $value ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if a block name is a premium block.
	 *
	 * @param string $block_name The name of the block to check.
	 *
	 * @return bool True if the block name starts with "premium/", false otherwise.
	 */
	public function is_premium_block( $block_name ) {
		if ( strpos( $block_name, 'premium/' ) !== false ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * str_replace_first
	 *
	 * @param  string $search
	 * @param  string $replace
	 * @param  string $subject
	 * @return string
	 */
	public function str_replace_first( $search, $replace, $subject ) {
		$pos = strpos( $subject, $search );
		if ( $pos !== false ) {
			return substr_replace( $subject, $replace, $pos, strlen( $search ) );
		}
		return $subject;
	}

	/**
	 * Registers blocks with features.
	 *
	 * @param string $block_content The block content.
	 * @param array  $block         The block attributes.
	 *
	 * @return string $block_content The block content.
	 */
	public function register_block_animation( $block_content, $block ) {
		if ( ! $this->global_features['premium-entrance-animation-all-blocks'] && ! $this->is_premium_block( $block['blockName'] ) ) {
			return $block_content;
		}

		if ( $this->global_features['premium-entrance-animation'] && isset( $block['attrs']['entranceAnimation'] ) && $this->check_if_any_value_not_empty( $block['attrs']['entranceAnimation']['animation'] ) ) {
			$this->entrance_animation_blocks[ $block['attrs']['entranceAnimation']['clientId'] ] = $block['attrs']['entranceAnimation'];
		}

		return $block_content;
	}

	/**
	 * Enqueues features scripts.
	 */
	public function enqueue_features_script() {
		$media_query            = array();
		$media_query['mobile']  = apply_filters( 'Premium_BLocks_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet']  = apply_filters( 'Premium_BLocks_tablet_media_query', '(max-width: 1024px)' );
		$media_query['desktop'] = apply_filters( 'Premium_BLocks_desktop_media_query', '(min-width: 1025px)' );

		if ( ! empty( $this->entrance_animation_blocks ) ) {
			$this->entrance_animation_blocks['breakPoints'] = $media_query;
			wp_enqueue_script(
				'premium-entrance-animation-view',
				PREMIUM_BLOCKS_URL . 'assets/js/build/entrance-animation-front.js',
				array(),
				PREMIUM_BLOCKS_VERSION,
				true
			);

			wp_enqueue_style(
				'pbg-entrance-animation-css',
				PREMIUM_BLOCKS_URL . 'assets/js/build/entrance-animation.css',
				array(),
				PREMIUM_BLOCKS_VERSION,
				'all'
			);

			wp_localize_script(
				'premium-entrance-animation-view',
				'PBG_EntranceAnimation',
				$this->entrance_animation_blocks
			);
		}
	}

	/**
	 * Equal Height Frontend
	 *
	 * Enqueue Frontend Assets for Premium Blocks.
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function equal_height_front_script( $content, $block ) {
		$media_query            = array();
		$media_query['mobile']  = apply_filters( 'Premium_BLocks_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet']  = apply_filters( 'Premium_BLocks_tablet_media_query', '(max-width: 1024px)' );
		$media_query['desktop'] = apply_filters( 'Premium_BLocks_desktop_media_query', '(min-width: 1025px)' );
		if ( $this->global_features['premium-equal-height'] && isset( $block['attrs']['equalHeight'] ) && $block['attrs']['equalHeight'] ) {
			wp_enqueue_script(
				'premium-equal-height-view',
				PREMIUM_BLOCKS_URL . 'assets/js/build/equal-height.js',
				array(),
				PREMIUM_BLOCKS_VERSION,
				true
			);

			wp_localize_script(
				'premium-equal-height-view',
				'PBG_EqualHeight',
				apply_filters(
					'premium_equal_height_localize_script',
					array(
						'breakPoints' => $media_query,
					)
				)
			);
		}
		return $content;
	}

	/**
	 * Add blocks editor style
	 *
	 * @return void
	 */
	public function add_blocks_editor_styles() {
		$generate_css = new Pbg_Assets_Generator( 'editor' );
		if ( $this->global_features['premium-entrance-animation'] ) {
			$generate_css->pbg_add_css( 'assets/js/build/entrance-animation.css' );
		}
		$generate_css->pbg_add_css( 'assets/css/minified/blockseditor.min.css' );
		$generate_css->pbg_add_css( 'assets/css/minified/editorpanel.min.css' );
		$generate_css->pbg_add_css( 'assets/css/minified/carousel.min.css' );
		$is_rtl = is_rtl() ? true : false;
		$is_rtl ? $generate_css->pbg_add_css( 'assets/css/minified/style-blocks-rtl.min.css' ) : '';

		if ( is_array( self::$blocks ) && ! empty( self::$blocks ) ) {
			foreach ( self::$blocks as $slug => $value ) {

				if ( false === $value ) {
					continue;
				}

				if ( 'buttons' === $slug ) {
					$this->blocks_frondend_assets->pbg_add_css( 'assets/css/minified/button.min.css' );
				}

				if ( 'pricing-table' === $slug ) {
					$generate_css->pbg_add_css( 'assets/css/minified/price.min.css' );
					$generate_css->pbg_add_css( 'assets/css/minified/badge.min.css' );
				}
				if ( 'pricing-table' === $slug || 'icon-box' === $slug || 'person' === $slug ) {
					$generate_css->pbg_add_css( 'assets/css/minified/text.min.css' );
				}
				if ( 'pricing-table' === $slug || 'icon-box' === $slug ) {
					$generate_css->pbg_add_css( 'assets/css/minified/button.min.css' );
				}
				if ( 'person' === $slug ) {
					$generate_css->pbg_add_css( 'assets/css/minified/image.min.css' );
					$generate_css->pbg_add_css( 'assets/css/minified/icon-group.min.css' );
				}
				if ( 'content-switcher' === $slug ) {
					$generate_css->pbg_add_css( 'assets/css/minified/switcher-child.min.css' );
				}
				if ( 'count-up' === $slug ) {
					$generate_css->pbg_add_css( 'assets/css/minified/counter.min.css' );
				}
				if ( 'testimonials' === $slug ) {
					$generate_css->pbg_add_css( 'assets/css/minified/author.min.css' );
				}

				if ( 'instagram-feed' === $slug ) {
					$generate_css->pbg_add_css( 'assets/css/minified/instagram-feed-header.min.css' );
					$generate_css->pbg_add_css( 'assets/css/minified/instagram-feed-posts.min.css' );
				}

				$generate_css->pbg_add_css( "assets/css/minified/{$slug}.min.css" );
			}
		}

		// Add dynamic css.
		$css_url = $generate_css->get_css_url();

		// Enqueue editor styles.
		if ( false != $css_url ) {
			wp_register_style( 'premium-blocks-editor-css', $css_url, array(), PREMIUM_BLOCKS_VERSION, 'all' );
			wp_add_inline_style( 'premium-blocks-editor-css', apply_filters( 'pbg_dynamic_css', '' ) );
		}

	}

	/**
	 * Add blocks frontend style
	 *
	 * @return void
	 */
	function load_dashicons_front_end() {
		wp_enqueue_style( 'dashicons' );
	}

	/**
	 * Load Json Files
	 */
	public function pbg_mime_types( $mimes ) {
		$mimes['json'] = 'application/json';
		$mimes['svg']  = 'image/svg+xml';
		return $mimes;
	}

	/**
	 * Load SvgShapes
	 *
	 * @since 1.0.0
	 */
	public function getSvgShapes() {
		$shape_path = PREMIUM_BLOCKS_PATH . 'assets/icons/shape';
		$shapes     = glob( $shape_path . '/*.svg', GLOB_BRACE );
		$shapeArray = array();
		if ( count( $shapes ) ) {
			foreach ( $shapes as $shape ) {
				$shapeArray[ str_replace( array( '.svg', $shape_path . '/' ), '', $shape ) ] = file_get_contents( $shape );
			}
		}
		return $shapeArray;
	}


	/**
	 * Fix File Of type JSON
	 */
	public function fix_mime_type_json( $data = null, $file = null, $filename = null, $mimes = null ) {
		$ext = isset( $data['ext'] ) ? $data['ext'] : '';
		if ( 1 > strlen( $ext ) ) {
			$exploded = explode( '.', $filename );
			$ext      = strtolower( end( $exploded ) );
		}
		if ( 'json' === $ext ) {
			$data['type'] = 'application/json';
			$data['ext']  = 'json';
		}
		return $data;
	}

	/**
	 * Enqueue Editor CSS/JS for Premium Blocks
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function pbg_editor() {
		$allow_json          = isset( self::$config['premium-upload-json'] ) ? self::$config['premium-upload-json'] : true;
		$is_fa_enabled       = isset( self::$config['premium-fa-css'] ) ? self::$config['premium-fa-css'] : true;
		$plugin_dependencies = array( 'wp-blocks', 'react', 'react-dom', 'wp-components', 'wp-compose', 'wp-data', 'wp-edit-post', 'wp-element', 'wp-hooks', 'wp-i18n', 'wp-plugins', 'wp-polyfill', 'wp-primitives', 'wp-api', 'wp-widgets', 'lodash' );
		$settings_data       = array(
			'ajaxurl'           => esc_url( admin_url( 'admin-ajax.php' ) ),
			'nonce'             => wp_create_nonce( 'pa-blog-block-nonce' ),
			'settingPath'       => admin_url( 'admin.php?page=pb_panel&path=settings' ),
			'defaultAuthImg'    => PREMIUM_BLOCKS_URL . 'assets/img/author.jpg',
			'activeBlocks'      => self::$blocks,
			'tablet_breakpoint' => PBG_TABLET_BREAKPOINT,
			'mobile_breakpoint' => PBG_MOBILE_BREAKPOINT,
			'shapes'            => $this->getSvgShapes(),
			'admin_url'         => admin_url(),
			'globalFeatures'    => $this->global_features,
			'performance'       => $this->performance_settings,
			'socialNonce'       => wp_create_nonce( 'pbg-social' ),
		);

		wp_register_script(
			'pbg-settings-js',
			PREMIUM_BLOCKS_URL . 'assets/js/build/pbg.js',
			array( 'wp-element' ),
			PREMIUM_BLOCKS_VERSION,
			true
		);

		wp_register_script(
			'pbg-blocks-js',
			PREMIUM_BLOCKS_URL . 'assets/js/build/index.js',
			array( 'wp-api-fetch', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-api', 'wp-edit-post', 'pbg-settings-js' ),
			PREMIUM_BLOCKS_VERSION,
			true
		);

		wp_localize_script(
			'pbg-blocks-js',
			'PremiumBlocksSettings',
			$settings_data
		);

		wp_localize_script(
			'pbg-settings-js',
			'PremiumBlocksSettings',
			$settings_data
		);

		wp_localize_script(
			'pbg-settings-js',
			'FontAwesomeConfig',
			array(
				'FontAwesomeEnabled' => $is_fa_enabled,
			)
		);
		wp_localize_script(
			'pbg-settings-js',
			'JsonUploadFile',
			array(
				'JsonUploadEnabled' => $allow_json,
			)
		);

		if ( $this->global_features['premium-entrance-animation'] ) {
			wp_enqueue_script(
				'pbg-entrance-animation',
				PREMIUM_BLOCKS_URL . 'assets/js/build/entrance-animation.js',
				array( 'wp-block-editor', 'wp-components', 'wp-compose', 'wp-element', 'wp-edit-post', 'wp-hooks', 'pbg-settings-js' ),
				PREMIUM_BLOCKS_VERSION,
				true
			);
			wp_localize_script(
				'pbg-entrance-animation',
				'PremiumAnimation',
				array(
					'allBlocks' => $this->global_features['premium-entrance-animation-all-blocks'],
				)
			);
		}
	}


	/**
	 * PBG Frontend
	 *
	 * Enqueue Frontend Assets for Premium Blocks.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function pbg_frontend() {
		$is_fa_enabled = isset( self::$config['premium-fa-css'] ) ? self::$config['premium-fa-css'] : true;

		$is_enabled = isset( self::$config['premium-map-api'] ) ? self::$config['premium-map-api'] : true;

		$api_key         = isset( self::$config['premium-map-key'] ) ? self::$config['premium-map-key'] : '';
		$is_maps_enabled = self::$blocks['maps'];

		$is_rtl = is_rtl() ? true : false;

		if ( $is_rtl ) {
			wp_enqueue_style(
				'pbg-style',
				PREMIUM_BLOCKS_URL . 'assets/css/minified/style-blocks-rtl.min.css',
				array(),
				PREMIUM_BLOCKS_VERSION,
				'all'
			);
		}

		if ( $is_fa_enabled ) {
			wp_enqueue_style(
				'pbg-fontawesome',
				// 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'
				'https://use.fontawesome.com/releases/v5.15.1/css/all.css'
			);
		}

		// Enqueue Google Maps API Script.
		if ( $is_maps_enabled && $is_enabled ) {
			if ( ! empty( $api_key ) && '1' != $api_key ) {
				wp_enqueue_script(
					'premium-map-block',
					'https://maps.googleapis.com/maps/api/js?key=' . $api_key,
					array(),
					PREMIUM_BLOCKS_VERSION,
					false
				);
			}
		}

		wp_localize_script(
			'pbg-blocks-js',
			'PremiumSettings',
			array(
				'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) ),
				'nonce'   => wp_create_nonce( 'pa-blog-block-nonce' ),
			)
		);
	}

	/**
	 * On init startup.
	 */
	public function on_init() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		foreach ( self::$blocks as $slug => $value ) {
			if ( false === $value ) {
				continue;
			}
			if ( file_exists( PREMIUM_BLOCKS_PATH . "blocks-config/{$slug}/index.php" ) ) {
				require_once PREMIUM_BLOCKS_PATH . "blocks-config/{$slug}/index.php";
			}
			if ( 'pricing-table' === $slug || 'icon-box' === $slug || 'person' === $slug ) {
				require_once PREMIUM_BLOCKS_PATH . 'blocks-config/text.php';
				if ( 'pricing-table' === $slug ) {
					require_once PREMIUM_BLOCKS_PATH . 'blocks-config/price.php';
					require_once PREMIUM_BLOCKS_PATH . 'blocks-config/badge.php';
				}
				if ( 'pricing-table' === $slug || 'icon-box' === $slug ) {
					require_once PREMIUM_BLOCKS_PATH . 'blocks-config/button.php';
				}
				if ( 'person' === $slug ) {
					require_once PREMIUM_BLOCKS_PATH . 'blocks-config/image.php';
					require_once PREMIUM_BLOCKS_PATH . 'blocks-config/icon-group.php';
				}
			} elseif ( $slug === 'content-switcher' ) {
				require_once PREMIUM_BLOCKS_PATH . 'blocks-config/switcher-child.php';
			} elseif ( $slug === 'count-up' ) {
				require_once PREMIUM_BLOCKS_PATH . 'blocks-config/counter.php';
			} elseif ( $slug === 'testimonials' ) {
				require_once PREMIUM_BLOCKS_PATH . 'blocks-config/author.php';
				require_once PREMIUM_BLOCKS_PATH . 'blocks-config/image.php';
				require_once PREMIUM_BLOCKS_PATH . 'blocks-config/text.php';
			} elseif ( $slug === 'buttons' ) {
				require_once PREMIUM_BLOCKS_PATH . 'blocks-config/button.php';
			} elseif ( $slug === 'instagram-feed' ) {
				require_once PREMIUM_BLOCKS_PATH . 'blocks-config/instagram-feed-header/index.php';
				require_once PREMIUM_BLOCKS_PATH . 'blocks-config/instagram-feed-posts/index.php';
			}
		}
	}

	/**
	 * Add Premium Blocks category to Blocks Categories
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @param array $categories blocks categories.
	 */
	public function register_premium_category( $categories ) {
		return array_merge(
			array(
				array(
					'slug'  => 'premium-blocks',
					'title' => __( 'Premium Blocks', 'premium-blocks-for-gutenberg' ),
				),
			),
			$categories
		);
	}


	/**
	 *
	 * Generates stylesheet and appends in head tag.
	 *
	 * @since 1.8.2
	 */
	public function generate_stylesheet() {
		 $this_post = array();
		if ( class_exists( 'WooCommerce' ) ) {

			if ( is_cart() ) {

				$id        = get_option( 'woocommerce_cart_page_id' );
				$this_post = get_post( $id );
			} elseif ( is_account_page() ) {

				$id        = get_option( 'woocommerce_myaccount_page_id' );
				$this_post = get_post( $id );
			} elseif ( is_checkout() ) {

				$id        = get_option( 'woocommerce_checkout_page_id' );
				$this_post = get_post( $id );
			} elseif ( is_checkout_pay_page() ) {

				$id        = get_option( 'woocommerce_pay_page_id' );
				$this_post = get_post( $id );
			} elseif ( is_shop() ) {

				$id        = get_option( 'woocommerce_shop_page_id' );
				$this_post = get_post( $id );
			}

			if ( is_object( $this_post ) ) {
				$this->generate_post_stylesheet( $this_post );
				return;
			}
		}

		if ( is_single() || is_page() || is_404() ) {

			global $post;
			$this_post = $post;

			if ( ! is_object( $this_post ) ) {
				return;
			}

			$this->generate_post_stylesheet( $this_post );
		} elseif ( is_archive() || is_home() || is_search() ) {

			global $wp_query;

			foreach ( $wp_query as $post ) {
				$this->generate_post_stylesheet( $post );
			}
		}
	}

	/**
	 * Render Boolean is amp or Not
	 */
	public function it_is_not_amp() {
		$not_amp = true;
		if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
			$not_amp = false;
		}
		return $not_amp;
	}

	/**
	 * Generates stylesheet in loop.
	 *
	 * @param object $this_post Current Post Object.
	 *
	 * @since 1.8.2
	 */
	public function generate_post_stylesheet( $this_post ) {
		if ( ! is_object( $this_post ) ) {
			return;
		}
		if ( ! isset( $this_post->ID ) ) {
			return;
		}
		if ( has_blocks( $this_post->ID ) ) {

			if ( isset( $this_post->post_content ) ) {

				$blocks            = $this->parse( $this_post->post_content );
				self::$page_blocks = $blocks;

				if ( ! is_array( $blocks ) || empty( $blocks ) ) {
					return;
				}

				self::$stylesheet .= $this->get_stylesheet( $blocks );
			}
		}
	}

	/**
	 * Parse Guten Block.
	 *
	 * @param string $content the content string.
	 *
	 * @since 1.1.0
	 */
	public function parse( $content ) {
		global $wp_version;

		return ( version_compare( $wp_version, '5', '>=' ) ) ? parse_blocks( $content ) : gutenberg_parse_blocks( $content );
	}

	/**
	 * Print the Stylesheet in header.
	 *
	 * @since 1.8.2
	 *
	 * @access public
	 */
	public function print_stylesheet() {
		global $content_width;
		if ( is_null( self::$stylesheet ) || '' === self::$stylesheet ) {
			return;
		}
		self::$stylesheet = str_replace( '#CONTENT_WIDTH#', $content_width . 'px', self::$stylesheet );
		ob_start();
		?>
		<style type="text/css" media="all" id="premium-style-frontend">
			<?php echo self::$stylesheet; ?>
		</style>
		<?php
		ob_end_flush();
	}

	/**
	 * Generates CSS recurrsively.
	 *
	 * @since 1.8.2
	 *
	 * @access public
	 *
	 * @param object $block The block object.
	 */
	public function get_block_css( $block ) {
		$block    = (array) $block;
		$name     = $block['blockName'];
		$css      = array();
		$block_id = '';

		if ( ! isset( $name ) ) {
			return;
		}

		if ( isset( $block['attrs'] ) && is_array( $block['attrs'] ) ) {
			$blockattr = $block['attrs'];
			if ( isset( $blockattr['block_id'] ) ) {
				$block_id = $blockattr['block_id'];
			}
		}

		self::$current_block_list[] = $name;

		if ( strpos( $name, 'premium/' ) !== false ) {
			self::$premium_flag = true;
		}

		if ( isset( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as $j => $inner_block ) {
				if ( 'core/block' === $inner_block['blockName'] ) {
					$id = ( isset( $inner_block['attrs']['ref'] ) ) ? $inner_block['attrs']['ref'] : 0;

					if ( $id ) {
						$content = get_post_field( 'post_content', $id );

						$reusable_blocks = $this->parse( $content );

						self::$stylesheet .= $this->get_stylesheet( $reusable_blocks );
					}
				} else {
					// Get CSS for the Block.
					$inner_block_css = $this->get_block_css( $inner_block );

					$css_desktop = ( isset( $css['desktop'] ) ? $css['desktop'] : '' );
					$css_tablet  = ( isset( $css['tablet'] ) ? $css['tablet'] : '' );
					$css_mobile  = ( isset( $css['mobile'] ) ? $css['mobile'] : '' );

					if ( isset( $inner_block_css['desktop'] ) ) {
						$css['desktop'] = $css_desktop . $inner_block_css['desktop'];
						$css['tablet']  = $css_tablet . $inner_block_css['tablet'];
						$css['mobile']  = $css_mobile . $inner_block_css['mobile'];
					}
				}
			}
		}

		self::$current_block_list = array_unique( self::$current_block_list );
		return $css;
	}

	/**
	 * Render Inline CSS helper function
	 *
	 * @param array  $css the css for each block.
	 * @param string $style_id the unique id for the rendered style.
	 * @param bool   $in_content the bool for whether or not it should run in content.
	 */
	public function render_inline_css( $css, $block_name = '' ) {
		if ( ! is_admin() ) {
			$this->add_custom_block_css( $css );
			if ( $block_name ) {
				$this->add_block_css( "assets/css/minified/{$block_name}.min.css" );
			}
		}
	}

	/**
	 * Check if block should render inline.
	 *
	 * @param string $name the blocks name.
	 * @param string $unique_id the blocks block_id.
	 */
	public function should_render_inline( $name, $unique_id ) {
		if ( doing_filter( 'the_content' ) || apply_filters( 'premium_blocks_force_render_inline_css_in_content', false, $name, $unique_id ) || is_customize_preview() ) {
			return true;
		}
		return false;
	}

	public function add_custom_block_css( $css ) {
		// Generate a unique ID for the style tag
		$unique_id = uniqid();

		// Add the CSS code to the global array
		global $custom_block_css;
		$custom_block_css[ $unique_id ] = $css;

		// Register a function to output the CSS code in the head of the page
	}

	public function get_custom_block_css() {

		global $custom_block_css;
		// Get the media queries.
		$media_query            = array();
		$media_query['mobile']  = apply_filters( 'Premium_BLocks_mobile_media_query', '(max-width: 767px)' );
		$media_query['tablet']  = apply_filters( 'Premium_BLocks_tablet_media_query', '(max-width: 1024px)' );
		$media_query['desktop'] = apply_filters( 'Premium_BLocks_desktop_media_query', '(min-width: 1025px)' );

		// Combine all CSS code into one string
		$combined_css_array = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);

		$combined_css = '';

		if ( is_array( $custom_block_css ) && ! empty( $custom_block_css ) ) {
			foreach ( $custom_block_css as $unique_id => $css ) {
				if ( ! is_array( $css ) ) {
					$combined_css_array['desktop'] .= $css;
					continue;
				}
				$combined_css_array['desktop'] .= $css['desktop'];
				$combined_css_array['tablet']  .= $css['tablet'];
				$combined_css_array['mobile']  .= $css['mobile'];
			}
		}

		if ( ! empty( $combined_css_array['desktop'] ) ) {
			$combined_css .= $combined_css_array['desktop'];
		}

		if ( ! empty( $combined_css_array['tablet'] ) ) {
			$combined_css .= "@media all and {$media_query['tablet']} {" . $combined_css_array['tablet'] . '}';
		}

		if ( ! empty( $combined_css_array['mobile'] ) ) {
			$combined_css .= "@media all and {$media_query['mobile']} {" . $combined_css_array['mobile'] . '}';
		}

		// Output the combined CSS code in a single style tag
		return $combined_css;
	}

	/**
	 * Converts color value from Hex to RGBA
	 *
	 * @since 1.8.2
	 *
	 * @access public
	 *
	 * @param string $hex_color value of Color.
	 */
	public function hex_to_rgba( $color, $opacity = false, $is_array = false ) {
		$default = $color;
		// Return default if no color provided.
		if ( empty( $color ) ) {
			return $default;
		}
		// Sanitize $color if "#" is provided.
		if ( '#' === $color[0] ) {
			$color = substr( $color, 1 );
		}
		// Check if color has 6 or 3 characters and get values.
		if ( strlen( $color ) === 6 ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( strlen( $color ) === 3 ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}
		// Convert hexadec to rgb.
		$rgb = array_map( 'hexdec', $hex );
		// Check if opacity is set(rgba or rgb).

		if ( false !== $opacity && '' !== $opacity ) {
			if ( abs( $opacity ) >= 1 ) {
				$opacity = $opacity / 100;
			}
			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}
		if ( $is_array ) {
			return $rgb;
		} else {
			// Return rgb(a) color string.
			return $output;
		}
	}

	/**
	 * Generates stylesheet for reusable blocks.
	 *
	 * @since 1.1.0
	 *
	 * @param array $blocks blocks array.
	 */
	public function get_stylesheet( $blocks ) {
		$desktop         = '';
		$tablet          = '';
		$mobile          = '';
		$tab_styling_css = '';
		$mob_styling_css = '';

		foreach ( $blocks as $i => $block ) {
			if ( is_array( $block ) ) {
				if ( '' === $block['blockName'] ) {
					continue;
				}
				if ( 'core/block' === $block['blockName'] ) {
					$id = ( isset( $block['attrs']['ref'] ) ) ? $block['attrs']['ref'] : 0;

					if ( $id ) {
						$content = get_post_field( 'post_content', $id );

						$reusable_blocks = $this->parse( $content );

						self::$stylesheet .= $this->get_stylesheet( $reusable_blocks );
					}
				} else {
					// Get CSS for the Block.
					$css = $this->get_block_css( $block );

					if ( isset( $css['desktop'] ) ) {
						$desktop .= $css['desktop'];
						$tablet  .= $css['tablet'];
						$mobile  .= $css['mobile'];
					}
				}
			}
		}
		if ( ! empty( $tablet ) ) {
			$tab_styling_css .= '@media only screen and (max-width: ' . PBG_TABLET_BREAKPOINT . 'px) {';
			$tab_styling_css .= $tablet;
			$tab_styling_css .= '}';
		}
		if ( ! empty( $mobile ) ) {
			$mob_styling_css .= '@media only screen and (max-width: ' . PBG_MOBILE_BREAKPOINT . 'px) {';
			$mob_styling_css .= $mobile;
			$mob_styling_css .= '}';
		}
		return $desktop . $tab_styling_css . $mob_styling_css;
	}

	/**
	 * Creates and returns an instance of the class
	 *
	 * @since 1.0.0
	 *
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
if ( ! function_exists( 'pbg_blocks_helper' ) ) {

	/**
	 * Returns an instance of the plugin class.
	 *
	 * @since  1.0.0
	 *
	 * @return object
	 */
	function pbg_blocks_helper() {
		return pbg_blocks_helper::get_instance();
	}
}
pbg_blocks_helper();
