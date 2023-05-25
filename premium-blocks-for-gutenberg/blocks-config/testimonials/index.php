<?php
/**
 * Server-side rendering of the `pbg/testimonials` block.
 *
 * @package WordPress
 */

/**
 * Get Testimonials Block CSS
 *
 * Return Frontend CSS for Testimonials.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_testimonials_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// Container Style
	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Desktop'], $padding['unit'] ) );
	}
	if(isset($attr["background"])){
		$css->set_selector( $unique_id );
		$css->render_background( $attr["background"], 'Desktop' );

	}


	$css->start_media_query( 'tablet' );

	// Container Style
	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Tablet'], $padding['unit'] ) );
	}
	if(isset($attr["background"])){
		$css->set_selector( $unique_id );
		$css->render_background( $attr["background"], 'Tablet' );

	}
	$css->stop_media_query();
	$css->start_media_query( 'mobile' );

	// Container Style
	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Mobile'], $padding['unit'] ) );
	}
	if(isset($attr["background"])){
		$css->set_selector( $unique_id );
		$css->render_background( $attr["background"], 'Mobile' );

	}
	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/testimonials` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_testimonials( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['block_id'] ) && ! empty( $attributes['block_id'] ) ) {
		$unique_id = "#premium-testimonial-{$attributes['block_id']}";
	}

	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = ".{$attributes['blockId']}";
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'testimonials', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_testimonials_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'testimonials', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'testimonials' );
			}
		}
	};

	return $content;
}




/**
 * Register the testimonials block.
 *
 * @uses render_block_pbg_testimonials()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_testimonials() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/testimonials',
		array(
			'render_callback' => 'render_block_pbg_testimonials',
		)
	);
}

register_block_pbg_testimonials();
