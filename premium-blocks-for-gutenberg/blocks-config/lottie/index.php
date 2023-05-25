<?php
/**
 * Server-side rendering of the `pbg/lottie` block.
 *
 * @package WordPress
 */

/**
 * Get Lottie Block CSS
 *
 * Return Frontend CSS for Lottie.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_lottie_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	if ( isset( $attr['lottieAlign'] ) ) {
		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['lottieAlign'], 'Desktop' ) );
	}

	if ( isset( $attr['size'] ) ) {
		$css->set_selector( $unique_id . '> .premium-lottie-svg svg' );
		$css->add_property( 'width', $css->render_range( $attr['size'], 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $attr['size'], 'Desktop' ) );
		$css->set_selector( '#premium-lottie-' . $unique_id . '> .premium-lottie-canvas' );
		$css->add_property( 'width', $css->render_range( $attr['size'], 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $attr['size'], 'Desktop' ) );
	}

	if ( isset( $attr['padding'] ) ) {
		$css->set_selector( $unique_id . ' .premium-lottie-animation' );
		$css->add_property( 'padding', $css->render_spacing( $attr['padding']['Desktop'], $attr['padding']['unit'] ) );
	}

	if ( isset( $attr['border'] ) ) {
		$lottie_border        = $attr['border'];
		$lottie_border_width  = $lottie_border['borderWidth'];
		$lottie_border_radius = $lottie_border['borderRadius'];
		$css->set_selector( $unique_id . ' .premium-lottie-animation' );
		$css->add_property( 'border-width', $css->render_spacing( $lottie_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $lottie_border_radius['Desktop'], 'px' ) );
	}

	$css->start_media_query( 'tablet' );
	if ( isset( $attr['lottieAlign'] ) ) {
		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['lottieAlign'], 'Tablet' ) );
	}

	if ( isset( $attr['size'] ) ) {
		$css->set_selector( $unique_id . '> .premium-lottie-svg svg' );
		$css->add_property( 'width', $css->render_range( $attr['size'], 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $attr['size'], 'Tablet' ) );
		$css->set_selector( $unique_id . '> .premium-lottie-canvas' );
		$css->add_property( 'width', $css->render_range( $attr['size'], 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $attr['size'], 'Tablet' ) );
	}

	if ( isset( $attr['padding'] ) ) {
		$css->set_selector( $unique_id . ' .premium-lottie-animation' );
		$css->add_property( 'padding', $css->render_spacing( $attr['padding']['Tablet'], $attr['padding']['unit'] ) );
	}

	if ( isset( $attr['border'] ) ) {
		$lottie_border        = $attr['border'];
		$lottie_border_width  = $lottie_border['borderWidth'];
		$lottie_border_radius = $lottie_border['borderRadius'];
		$css->set_selector( $unique_id . ' .premium-lottie-animation' );
		$css->add_property( 'border-width', $css->render_spacing( $lottie_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $lottie_border_radius['Tablet'], 'px' ) );
	}
	$css->stop_media_query();
	$css->start_media_query( 'mobile' );
	if ( isset( $attr['lottieAlign'] ) ) {
		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['lottieAlign'], 'Mobile' ) );
	}

	if ( isset( $attr['size'] ) ) {
		$css->set_selector( $unique_id . '> .premium-lottie-svg svg' );
		$css->add_property( 'width', $css->render_range( $attr['size'], 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $attr['size'], 'Mobile' ) );
		$css->set_selector( $unique_id . '> .premium-lottie-canvas' );
		$css->add_property( 'width', $css->render_range( $attr['size'], 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $attr['size'], 'Mobile' ) );
	}

	if ( isset( $attr['padding'] ) ) {
		$css->set_selector( $unique_id . ' .premium-lottie-animation' );
		$css->add_property( 'padding', $css->render_spacing( $attr['padding']['Mobile'], $attr['padding']['unit'] ) );
	}

	if ( isset( $attr['border'] ) ) {
		$lottie_border        = $attr['border'];
		$lottie_border_width  = $lottie_border['borderWidth'];
		$lottie_border_radius = $lottie_border['borderRadius'];
		$css->set_selector( $unique_id . ' .premium-lottie-animation' );
		$css->add_property( 'border-width', $css->render_spacing( $lottie_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $lottie_border_radius['Mobile'], 'px' ) );
	}
	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/lottie` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_lottie( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['block_id'] ) && ! empty( $attributes['block_id'] ) ) {
		$unique_id = "#premium-lottie-{$attributes['block_id']}";
	}

	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = ".{$attributes['blockId']}";
	}

	// Enqueue frontend JavaScript and CSS.
	if ( $block_helpers->it_is_not_amp() ) {
		wp_enqueue_script(
			'pbg-lottie',
			PREMIUM_BLOCKS_URL . 'assets/js/lottie.js',
			array( 'jquery' ),
			PREMIUM_BLOCKS_VERSION,
			true
		);
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'lottie', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_lottie_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'lottie', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'lottie' );
			}
		}
	};

	return $content;
}




/**
 * Register the lottie block.
 *
 * @uses render_block_pbg_lottie()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_lottie() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/lottie',
		array(
			'render_callback' => 'render_block_pbg_lottie',
		)
	);
}

register_block_pbg_lottie();
