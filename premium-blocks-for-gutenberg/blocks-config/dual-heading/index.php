<?php
/**
 * Server-side rendering of the `pbg/dual-heading` block.
 *
 * @package WordPress
 */

/**
 * Get Dual Heading Block CSS
 *
 * Return Frontend CSS for Dual Heading.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_dual_heading_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	if ( isset( $attr['align'] ) ) {
		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Desktop' ) );
	}
	if ( isset( $attr['containerBorder'] ) ) {
		$container_border        = $attr['containerBorder'];
		$container_border_width  = $container_border['borderWidth'];
		$container_border_radius = $container_border['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Desktop'], 'px' ) );
	}
	if(isset($attr["background"])){
		$css->set_selector( $unique_id );
		$css->render_background( $attr["background"], 'Desktop' );

	}

	// First Style FontSize.

	if ( isset( $attr['firstTypography'] ) ) {
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->render_typography( $attr['firstTypography'], 'Desktop' );
	}

	if ( isset( $attr['firstBorder'] ) ) {
		$first_border        = $attr['firstBorder'];
		$first_border_width  = $first_border['borderWidth'];
		$first_border_radius = $first_border['borderRadius'];

		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->add_property( 'border-width', $css->render_spacing( $first_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $first_border_radius['Desktop'], 'px' ) );
	}

	if ( isset( $attr['firstPadding'] ) ) {
		$first_padding = $attr['firstPadding'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->add_property( 'padding', $css->render_spacing( $first_padding['Desktop'], $first_padding['unit'] ) );
	}

	if ( isset( $attr['firstMargin'] ) ) {
		$first_margin = $attr['firstMargin'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->add_property( 'margin', $css->render_spacing( $first_margin['Desktop'], $first_margin['unit'] ) );
	}

	// Second Style FontSize.

	if ( isset( $attr['secondTypography'] ) ) {
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );
		$css->render_typography( $attr['secondTypography'], 'Desktop' );
	}

	if ( isset( $attr['secondBorder'] ) ) {
		$second_border        = $attr['secondBorder'];
		$second_border_width  = $second_border['borderWidth'];
		$second_border_radius = $second_border['borderRadius'];

		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );
		$css->add_property( 'border-width', $css->render_spacing( $second_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $second_border_radius['Desktop'], 'px' ) );
	}

	if ( isset( $attr['secondPadding'] ) ) {
		$second_padding = $attr['secondPadding'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );
		$css->add_property( 'padding', $css->render_spacing( $second_padding['Desktop'], $second_padding['unit'] ) );
	}

	if ( isset( $attr['secondMargin'] ) ) {
		$second_margin = $attr['secondMargin'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );
		$css->add_property( 'margin', $css->render_spacing( $second_margin['Desktop'], $second_margin['unit'] ) );
	}

	$css->start_media_query( 'tablet' );

	if ( isset( $attr['align'] ) ) {
		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Tablet' ) );
	}

	if ( isset( $attr['containerBorder'] ) ) {
		$container_border        = $attr['containerBorder'];
		$container_border_width  = $container_border['borderWidth'];
		$container_border_radius = $container_border['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Tablet'], 'px' ) );
	}

	if(isset($attr["background"])){
		$css->set_selector( $unique_id );
		$css->render_background( $attr["background"], 'Tablet' );

	}

	// First Style FontSize.

	if ( isset( $attr['firstTypography'] ) ) {
		$first_typography = $attr['firstTypography'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->render_typography( $first_typography, 'Tablet' );
	}

	if ( isset( $attr['firstBorder'] ) ) {
		$first_border        = $attr['firstBorder'];
		$first_border_width  = $first_border['borderWidth'];
		$first_border_radius = $first_border['borderRadius'];

		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->add_property( 'border-width', $css->render_spacing( $first_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $first_border_radius['Tablet'], 'px' ) );
	}

	if ( isset( $attr['firstPadding'] ) ) {
		$first_padding = $attr['firstPadding'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->add_property( 'padding', $css->render_spacing( $first_padding['Tablet'], $first_padding['unit'] ) );
	}

	if ( isset( $attr['firstMargin'] ) ) {
		$first_margin = $attr['firstMargin'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->add_property( 'margin', $css->render_spacing( $first_margin['Tablet'], $first_margin['unit'] ) );
	}

	// Second Style FontSizeTablet.
	if ( isset( $attr['secondTypography'] ) ) {
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );
		$css->render_typography( $attr['secondTypography'], 'Tablet' );
	}

	if ( isset( $attr['secondBorder'] ) ) {
		$second_border        = $attr['secondBorder'];
		$second_border_width  = $second_border['borderWidth'];
		$second_border_radius = $second_border['borderRadius'];

		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );

		$css->add_property( 'border-width', $css->render_spacing( $second_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $second_border_radius['Tablet'], 'px' ) );
	}

	if ( isset( $attr['secondPadding'] ) ) {
		$second_padding = $attr['secondPadding'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );
		$css->add_property( 'padding', $css->render_spacing( $second_padding['Tablet'], $second_padding['unit'] ) );
	}

	if ( isset( $attr['secondMargin'] ) ) {
		$second_margin = $attr['secondMargin'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );
		$css->add_property( 'margin', $css->render_spacing( $second_margin['Tablet'], $second_margin['unit'] ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );

	if ( isset( $attr['align'] ) ) {
		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Mobile' ) );
	}

	if ( isset( $attr['containerBorder'] ) ) {
		$container_border        = $attr['containerBorder'];
		$container_border_width  = $container_border['borderWidth'];
		$container_border_radius = $container_border['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Mobile'], 'px' ) );
	}

	if(isset($attr["background"])){
		$css->set_selector( $unique_id );
		$css->render_background( $attr["background"], 'Mobile' );

	}

	// First Style FontSize.

	if ( isset( $attr['firstTypography'] ) ) {
		$first_typography = $attr['firstTypography'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->render_typography( $first_typography, 'Mobile' );
	}

	if ( isset( $attr['firstBorder'] ) ) {
		$first_border        = $attr['firstBorder'];
		$first_border_width  = $first_border['borderWidth'];
		$first_border_radius = $first_border['borderRadius'];

		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->add_property( 'border-width', $css->render_spacing( $first_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $first_border_radius['Mobile'], 'px' ) );
	}

	if ( isset( $attr['firstPadding'] ) ) {
		$first_padding = $attr['firstPadding'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->add_property( 'padding', $css->render_spacing( $first_padding['Mobile'], $first_padding['unit'] ) );
	}

	if ( isset( $attr['firstMargin'] ) ) {
		$first_margin = $attr['firstMargin'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__first' );
		$css->add_property( 'margin', $css->render_spacing( $first_margin['Mobile'], $first_margin['unit'] ) );
	}

	// Second Style FontSizeMobil.
	if ( isset( $attr['secondTypography'] ) ) {
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );
		$css->render_typography( $attr['secondTypography'], 'Mobile' );
	}

	if ( isset( $attr['secondBorder'] ) ) {
		$second_border        = $attr['secondBorder'];
		$second_border_width  = $second_border['borderWidth'];
		$second_border_radius = $second_border['borderRadius'];

		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );

		$css->add_property( 'border-width', $css->render_spacing( $second_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $second_border_radius['Mobile'], 'px' ) );
	}

	if ( isset( $attr['secondPadding'] ) ) {
		$second_padding = $attr['secondPadding'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );
		$css->add_property( 'padding', $css->render_spacing( $second_padding['Mobile'], $second_padding['unit'] ) );
	}

	if ( isset( $attr['secondMargin'] ) ) {
		$second_margin = $attr['secondMargin'];
		$css->set_selector( $unique_id . '> .premium-dheading-block__wrap' . ' > .premium-dheading-block__title' . ' > .premium-dheading-block__second' );
		$css->add_property( 'margin', $css->render_spacing( $second_margin['Mobile'], $second_margin['unit'] ) );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/dual-heading` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_dual_heading( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['block_id'] ) && ! empty( $attributes['block_id'] ) ) {
		$unique_id = "#premium-dheading-block-{$attributes['block_id']}";
	}

	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = ".{$attributes['blockId']}";
	} else {
		$unique_id = rand( 100, 10000 );
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'dual-heading', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_dual_heading_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'dual-heading', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'dual-heading' );
			}
		}
	};

	return $content;
}




/**
 * Register the dual_heading block.
 *
 * @uses render_block_pbg_dual_heading()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_dual_heading() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/dual-heading',
		array(
			'render_callback' => 'render_block_pbg_dual_heading',
		)
	);
}

register_block_pbg_dual_heading();
