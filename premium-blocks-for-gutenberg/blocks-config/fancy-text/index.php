<?php
/**
 * Server-side rendering of the `pbg/fancy-text` block.
 *
 * @package WordPress
 */

/**
 * Get Fancy Text Block CSS
 *
 * Return Frontend CSS for Fancy Text.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_fancy_text_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// FancyText Style
	if ( isset( $attr['fancyTextTypography'] ) ) {
		$fancy_typography = $attr['fancyTextTypography'];
		$fancy_size       = $fancy_typography['fontSize'];

		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .premium-fancy-text-title' );
		$css->render_typography( $attr['fancyTextTypography'], 'Desktop' );
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .typed-cursor' );
		$css->add_property( 'font-size', $css->render_range( $fancy_size, 'Desktop' ) );
	}
	// Suffix, Prefix Style
	if ( isset( $attr['prefixTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .premium-fancy-text-suffix-prefix' );
		$css->render_typography( $attr['prefixTypography'], 'Desktop' );
	}

	if ( isset( $attr['fancyContentAlign'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['fancyContentAlign'], 'Desktop' ) );
	}

	if ( isset( $attr['fancyTextAlign'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .premium-fancy-text-title-slide' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['fancyTextAlign'], 'Desktop' ) );
	}
	if ( isset( $attr['fancyContentAlign'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['fancyContentAlign'], 'Desktop' ) );
	}

	$css->start_media_query( 'tablet' );

	if ( isset( $attr['fancyTextTypography'] ) ) {
		$fancy_typography = $attr['fancyTextTypography'];
		$fancy_size       = $fancy_typography['fontSize'];

		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .premium-fancy-text-title' );
		$css->render_typography( $attr['fancyTextTypography'], 'Tablet' );
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .typed-cursor' );
		$css->add_property( 'font-size', $css->render_range( $fancy_size, 'Tablet' ) );
	}

	// Suffix, Prefix Style
	if ( isset( $attr['prefixTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .premium-fancy-text-suffix-prefix' );
		$css->render_typography( $attr['prefixTypography'], 'Tablet' );
	}

	if ( isset( $attr['fancyContentAlign'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['fancyContentAlign'], 'Tablet' ) );
	}

	if ( isset( $attr['fancyTextAlign'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .premium-fancy-text-title-slide' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['fancyTextAlign'], 'Tablet' ) );
	}
	if ( isset( $attr['fancyContentAlign'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['fancyContentAlign'], 'Tablet' ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );

	if ( isset( $attr['fancyTextTypography'] ) ) {
		$fancy_typography = $attr['fancyTextTypography'];
		$fancy_size       = $fancy_typography['fontSize'];

		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .premium-fancy-text-title' );
		$css->render_typography( $attr['fancyTextTypography'], 'Mobile' );
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .typed-cursor' );
		$css->add_property( 'font-size', $css->render_range( $fancy_size, 'Mobile' ) );
	}

	// Suffix, Prefix Style
	if ( isset( $attr['prefixTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .premium-fancy-text-suffix-prefix' );
		$css->render_typography( $attr['prefixTypography'], 'Mobile' );
	}
	if ( isset( $attr['fancyContentAlign'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['fancyContentAlign'], 'Mobile' ) );
	}

	if ( isset( $attr['fancyTextAlign'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-fancy-text' . '> .premium-fancy-text-title-slide' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['fancyTextAlign'], 'Mobile' ) );
	}
	if ( isset( $attr['fancyContentAlign'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['fancyContentAlign'], 'Mobile' ) );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/fancy-text` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_fancy_text( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = $attributes['blockId'];
	} else {
		$unique_id = rand( 100, 10000 );
	}

	// Enqueue required styles and scripts.
	if ( $block_helpers->it_is_not_amp() ) {
		wp_enqueue_script(
			'pbg-sectionfancy-text',
			PREMIUM_BLOCKS_URL . 'assets/js/fancy-text.js',
			array( 'jquery', 'pbg-typed' ),
			PREMIUM_BLOCKS_VERSION,
			true
		);
		wp_enqueue_script(
			'pbg-vticker',
			PREMIUM_BLOCKS_URL . 'assets/js/lib/vticker.js',
			array( 'jquery' ),
			PREMIUM_BLOCKS_VERSION,
			true
		);
		wp_enqueue_script(
			'pbg-typed',
			PREMIUM_BLOCKS_URL . 'assets/js/lib/typed.js',
			array( 'jquery' ),
			PREMIUM_BLOCKS_VERSION,
			true
		);
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'fancy-text', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_fancy_text_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'fancy-text', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'fancy-text' );
			}
		}
	};

	return $content;
}




/**
 * Register the fancy_text block.
 *
 * @uses render_block_pbg_fancy_text()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_fancy_text() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/fancy-text',
		array(
			'render_callback' => 'render_block_pbg_fancy_text',
		)
	);
}

register_block_pbg_fancy_text();
