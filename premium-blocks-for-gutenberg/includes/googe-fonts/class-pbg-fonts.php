<?php
/**
 * Helper class for font settings.
 *
 * @package     PBG
 * @author      PBG
 * @copyright   Copyright (c) 2019, PBG
 * @link        https://pbg.io/
 * @since       PBG 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * PBG Fonts
 */
final class PBG_Fonts {

	/**
	 * Get fonts to generate.
	 *
	 * @var array $fonts
	 */
	private static $fonts = array();

	/**
	 * Performance options.
	 *
	 * @var array $performance
	 */
	private static $performance = array();

	/**
	 * Adds data to the $fonts array for a font to be rendered.
	 *
	 * @param string $name The name key of the font to add.
	 * @param array  $variants An array of weight variants.
	 * @return void
	 */
	public static function add_font( $name, $variants = array() ) {

		if ( 'Default' == $name ) {
			return;
		}

		if ( is_array( $variants ) ) {
			$key = array_search( 'Default', $variants );
			if ( false !== $key ) {

				$variants = array_diff( $variants, array( 'Default' ) );

				if ( ! in_array( 400, $variants ) ) {
					$variants[] = 400;
				}
			}
		} elseif ( 'Default' == $variants ) {
			$variants = 400;
		}

		if ( isset( self::$fonts[ $name ] ) ) {
			foreach ( (array) $variants as $variant ) {
				if ( ! in_array( $variant, self::$fonts[ $name ]['variants'] ) ) {
					self::$fonts[ $name ]['variants'] = $variant;
				}
			}
		} else {
			self::$fonts[ $name ] = array(
				'variants' => (array) $variants,
			);
		}
	}

	/**
	 * Set Fonts
	 *
	 * @param  array $fonts Fonts.
	 * @return void
	 */
	public static function set_fonts( $fonts ) {
		foreach ( $fonts as $name => $font ) {
			self::add_font( $name, $font['fontvariants'] );
		}
	}

	/**
	 * Get Fonts
	 */
	public static function get_fonts() {

		do_action( 'pbg_get_fonts' );
		return apply_filters( 'pbg_add_fonts', self::$fonts );
	}

	/**
	 * Renders the <link> tag for all fonts in the $fonts array.
	 *
	 * @return void
	 */
	public static function render_fonts() {
		self::$performance = apply_filters( 'pb_performance_options', get_option( 'pbg_performance_options', array() ) );
		$font_list         = apply_filters( 'pbg_render_fonts', self::get_fonts() );

		$google_fonts = array();
		$font_subset  = array();

		foreach ( $font_list as $name => $font ) {
			if ( ! empty( $name ) ) {

				// Add font variants.
				$google_fonts[ $name ] = $font['variants'];

				// Add Subset.
				$subset = apply_filters( 'pbg_font_subset', '', $name );
				if ( ! empty( $subset ) ) {
					$font_subset[] = $subset;
				}
			}
		}

		$google_font_url = self::google_fonts_url( $google_fonts, $font_subset );

		$load_fonts = self::$performance['premium-load-fonts-locally'] ?? false;
		if ( $google_font_url ) {
			if ( $load_fonts && ! is_customize_preview() && ! is_admin() ) {
				$preload_fonts = self::$performance['premium-preload-local-fonts'] ?? false;
				if ( $preload_fonts ) {
					self::load_preload_local_fonts( $google_font_url );
				}
				wp_enqueue_style( 'pbg-google-fonts', pbg_get_webfont_url( $google_font_url ), array(), null );
			} else {
				wp_enqueue_style( 'pbg-google-fonts', $google_font_url, array(), null );
			}
		}
	}

	/**
	 * Get the file preloads.
	 *
	 * @param string $url    The URL of the remote webfont.
	 * @param string $format The font-format. If you need to support IE, change this to "woff".
	 */
	public static function load_preload_local_fonts( $url, $format = 'woff2' ) {

		$local_font_files = get_site_option( 'pbg_local_font_files', false );

		if ( is_array( $local_font_files ) && ! empty( $local_font_files ) ) {
			foreach ( $local_font_files as $key => $local_font ) {
				if ( $local_font ) {
					echo '<link rel="preload" href="' . esc_url( $local_font ) . '" as="font" type="font/' . esc_attr( $format ) . '" crossorigin>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
			}
			return;
		}

		// Now preload font data after processing it, as we didn't get stored data.
		$font = new PBG_WebFont_Loader( $url );
		$font->set_font_format( $format );
		$font->preload_local_fonts();
	}

	/**
	 * Google Font URL
	 * Combine multiple google font in one URL
	 *
	 * @link https://shellcreeper.com/?p=1476
	 * @param array $fonts      Google Fonts array.
	 * @param array $subsets    Font's Subsets array.
	 *
	 * @return string
	 */
	public static function google_fonts_url( $fonts, $subsets = array() ) {

		/* URL */
		$base_url  = 'https://fonts.googleapis.com/css2?';
		$font_args = array();
		$families  = array();
		$weights   = array(
			'italic' => array(),
			'normal' => array(),
		);
		$fonts     = apply_filters( 'pbg_google_fonts', $fonts );

		/* Format Each Font Family in Array */
		foreach ( $fonts as $font_name => $font_weight ) {
			$family      = '';
			$font_name   = str_replace( ' ', '+', $font_name );
			$family      = 'family=' . $font_name;
			$weight_text = 'wght@';
			$wghts       = array();
			if ( ! empty( $font_weight ) && ( count( $font_weight ) > 1 || ( count( $font_weight ) === 1 && $font_weight[0] != 400 ) ) ) {
				foreach ( $font_weight as  $weight ) {
					$weight_val = (int) $weight[1] * 100;
					if ( 'i' === $weight[0] ) {
						$weights['italic'][] = $weight_val;
					} else {
						$weights['normal'][] = $weight_val;
					}
				}
				sort( $weights['italic'] );
				sort( $weights['normal'] );

				if ( ! empty( $weights['normal'] ) ) {
					$weights['normal'] = array_unique( $weights['normal'] );
					foreach ( $weights['normal'] as $wght ) {
						$wghts[] = ! empty( $weights['italic'] ) ? '0,' . $wght : $wght;
					}
				}

				if ( ! empty( $weights['italic'] ) ) {
					$family           .= ':ital,';
					$weights['italic'] = array_unique( $weights['italic'] );
					foreach ( $weights['italic'] as $wght ) {
						$wghts[] = '1,' . $wght;
					}
				} else {
					$weight_text = ':wght@';
				}

				$weight_text .= implode( ';', $wghts );
				$families[]   = $family . $weight_text;
			} else {
				$families[] = $family;
			}
		}

		if ( ! empty( $families ) ) {
			$base_url .= implode( '&', $families );
			$base_url .= '&display=swap';

			return $base_url;
		}

		return false;
	}
}
