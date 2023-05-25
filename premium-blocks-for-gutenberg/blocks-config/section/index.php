<?php

/**
 * Server-side rendering of the `pbg/section` block.
 *
 * @package WordPress
 */

/**
 * Get Section Block CSS
 *
 * Return Frontend CSS for Section.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_section_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Desktop'], $padding['unit'] ) );
	}

	if ( isset( $attr['margin'] ) ) {
		$padding = $attr['margin'];
		$css->set_selector( $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $padding['Desktop'], $padding['unit'] ) );
	}

	if ( isset( $attr['border'] ) ) {
		$border        = $attr['border'];
		$border_width  = $border['borderWidth'];
		$border_radius = $border['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Desktop'], 'px' ) );
	}
	if ( isset( $attr['horAlign'] ) ) {
		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['horAlign'], 'Desktop' ) );
	}
	if(isset($attr["background"])){
		$css->set_selector( $unique_id );
		$css->render_background( $attr["background"], 'Desktop' );

	}

	$css->start_media_query( 'tablet' );

	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Tablet'], $padding['unit'] ) );
	}

	if ( isset( $attr['margin'] ) ) {
		$padding = $attr['margin'];
		$css->set_selector( $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $padding['Tablet'], $padding['unit'] ) );
	}

	if ( isset( $attr['border'] ) ) {
		$border        = $attr['border'];
		$border_width  = $border['borderWidth'];
		$border_radius = $border['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Tablet'], 'px' ) );
	}

	if ( isset( $attr['horAlign'] ) ) {
		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['horAlign'], 'Tablet' ) );
	}
	if(isset($attr["background"])){
		$css->set_selector( $unique_id );
		$css->render_background( $attr["background"], 'Tablet' );

	}
	$css->stop_media_query();

	$css->start_media_query( 'mobile' );

	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Mobile'], $padding['unit'] ) );
	}

	if ( isset( $attr['margin'] ) ) {
		$padding = $attr['margin'];
		$css->set_selector( $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $padding['Mobile'], $padding['unit'] ) );
	}

	if ( isset( $attr['border'] ) ) {
		$border        = $attr['border'];
		$border_width  = $border['borderWidth'];
		$border_radius = $border['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Mobile'], 'px' ) );
	}

	if ( isset( $attr['horAlign'] ) ) {
		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['horAlign'], 'Mobile' ) );
	}
	if(isset($attr["background"])){
		$css->set_selector( $unique_id );
		$css->render_background( $attr["background"], 'Mobile' );

	}
	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/section` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_section( $attributes, $content, $block ) {
	$is_rtl        = is_rtl() ? true : false;
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['block_id'] ) && ! empty( $attributes['block_id'] ) ) {
		$unique_id = '.premium-container';
	}

	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = ".{$attributes['blockId']}";
	}

	// Enqueue frontend scripts.
	wp_enqueue_script(
		'pbg-section',
		PREMIUM_BLOCKS_URL . 'assets/js/section.js',
		array( 'jquery' ),
		PREMIUM_BLOCKS_VERSION,
		true
	);

	wp_localize_script(
		'pbg-section',
		'siteDirection',
		array(
			'isRTL' => $is_rtl,
		)
	);

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'section', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_section_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'section', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'section' );
			}
		}
	};

	return $content;
}




/**
 * Register the section block.
 *
 * @uses render_block_pbg_section()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_section() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/section',
		array(
			'render_callback' => 'render_block_pbg_section',
		)
	);
}

register_block_pbg_section();
