<?php
/**
 * Server-side rendering of the `pbg/accordion` block.
 *
 * @package WordPress
 */

/**
 * Get Accordion Block CSS
 *
 * Return Frontend CSS for Accordion.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_accordion_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// Style.
	if ( isset( $attr['titleTypography'] ) ) {
		$title_typography = $attr['titleTypography'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__title_wrap' . ' > .premium-accordion__title' . ' > .premium-accordion__title_text' );
		$css->render_typography( $title_typography, 'Desktop' );
	}

	if ( isset( $attr['titlePadding'] ) ) {
		$title_padding = $attr['titlePadding'];
		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__title_wrap' );
		$css->add_property( 'padding', $css->render_spacing( $title_padding['Desktop'], $title_padding['unit'] ) );
	}

	if ( isset( $attr['titleMargin'] ) ) {
		$title_margin = $attr['titleMargin'];
		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' );
		$css->add_property( 'margin-bottom', $css->render_range( $title_margin, 'Desktop' ) . '!important' );
	}

	if ( isset( $attr['titleBorder'] ) ) {
		$title_border        = $attr['titleBorder'];
		$title_border_width  = $title_border['borderWidth'];
		$title_border_radius = $title_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__title_wrap' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Desktop'], 'px' ) );
	}

	// Desc Style
	if ( isset( $attr['descTypography'] ) ) {
		$desc_typography = $attr['descTypography'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' . ' > .premium-accordion__desc' );
		$css->render_typography( $desc_typography, 'Desktop' );
	}

	if ( isset( $attr['descPadding'] ) ) {
		$desc_padding = $attr['descPadding'];
		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' );
		$css->add_property( 'padding', $css->render_spacing( $desc_padding['Desktop'], $desc_padding['unit'] ) );
	}

	if ( isset( $attr['descBorder'] ) ) {
		$desc_border        = $attr['descBorder'];
		$desc_border_width  = $desc_border['borderWidth'];
		$desc_border_radius = $desc_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' );
		$css->add_property( 'border-width', $css->render_spacing( $desc_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $desc_border_radius['Desktop'], 'px' ) );
	}
	// content.
	if ( isset( $attr['descAlign'] ) ) {
		$align = $attr['descAlign'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' );
		$css->add_property( 'text-align', $css->get_responsive_css( $align, 'Desktop' ) );
	}

	$css->start_media_query( 'tablet' );

	if ( isset( $attr['titleTypography'] ) ) {
		$title_typography = $attr['titleTypography'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__title_wrap' . ' > .premium-accordion__title' . ' > .premium-accordion__title_text' );
		$css->render_typography( $title_typography, 'Tablet' );
	}

	if ( isset( $attr['titlePadding'] ) ) {
		$title_padding = $attr['titlePadding'];
		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__title_wrap' );
		$css->add_property( 'padding', $css->render_spacing( $title_padding['Tablet'], $title_padding['unit'] ) );
	}

	if ( isset( $attr['titleMargin'] ) ) {
		$title_margin = $attr['titleMargin'];
		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' );
		$css->add_property( 'margin-bottom', $css->render_range( $title_margin, 'Tablet' ) . '!important' );
	}

	if ( isset( $attr['titleBorder'] ) ) {
		$title_border        = $attr['titleBorder'];
		$title_border_width  = $title_border['borderWidth'];
		$title_border_radius = $title_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__title_wrap' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Tablet'], 'px' ) );
	}

	// Desc Style
	if ( isset( $attr['descTypography'] ) ) {
		$desc_typography = $attr['descTypography'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' . ' > .premium-accordion__desc' );
		$css->render_typography( $desc_typography, 'Tablet' );
	}

	if ( isset( $attr['descPadding'] ) ) {
		$desc_padding = $attr['descPadding'];
		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' );
		$css->add_property( 'padding', $css->render_spacing( $desc_padding['Tablet'], $desc_padding['unit'] ) );
	}

	if ( isset( $attr['descBorder'] ) ) {
		$desc_border        = $attr['descBorder'];
		$desc_border_width  = $desc_border['borderWidth'];
		$desc_border_radius = $desc_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' );
		$css->add_property( 'border-width', $css->render_spacing( $desc_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $desc_border_radius['Tablet'], 'px' ) );
	}
	// content.
	if ( isset( $attr['descAlign'] ) ) {
		$align = $attr['descAlign'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' );
		$css->add_property( 'text-align', $css->get_responsive_css( $align, 'Tablet' ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );
	if ( isset( $attr['titleTypography'] ) ) {
		$title_typography = $attr['titleTypography'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__title_wrap' . ' > .premium-accordion__title' . ' > .premium-accordion__title_text' );
		$css->render_typography( $title_typography, 'Mobile' );
	}

	if ( isset( $attr['titlePadding'] ) ) {
		$title_padding = $attr['titlePadding'];
		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__title_wrap' );
		$css->add_property( 'padding', $css->render_spacing( $title_padding['Mobile'], $title_padding['unit'] ) );
	}

	if ( isset( $attr['titleMargin'] ) ) {
		$title_margin = $attr['titleMargin'];
		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' );
		$css->add_property( 'margin-bottom', $css->render_range( $title_margin, 'Mobile' ) . '!important' );
	}

	if ( isset( $attr['titleBorder'] ) ) {
		$title_border        = $attr['titleBorder'];
		$title_border_width  = $title_border['borderWidth'];
		$title_border_radius = $title_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__title_wrap' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Mobile'], 'px' ) );
	}

	// Desc Style
	if ( isset( $attr['descTypography'] ) ) {
		$desc_typography = $attr['descTypography'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' . ' > .premium-accordion__desc' );
		$css->render_typography( $desc_typography, 'Mobile' );
	}

	if ( isset( $attr['descPadding'] ) ) {
		$desc_padding = $attr['descPadding'];
		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' );
		$css->add_property( 'padding', $css->render_spacing( $desc_padding['Mobile'], $desc_padding['unit'] ) );
	}

	if ( isset( $attr['descBorder'] ) ) {
		$desc_border        = $attr['descBorder'];
		$desc_border_width  = $desc_border['borderWidth'];
		$desc_border_radius = $desc_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' );
		$css->add_property( 'border-width', $css->render_spacing( $desc_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $desc_border_radius['Mobile'], 'px' ) );
	}
	// content.
	if ( isset( $attr['descAlign'] ) ) {
		$align = $attr['descAlign'];

		$css->set_selector( '.' . $unique_id . '> .premium-accordion__content_wrap' . ' > .premium-accordion__desc_wrap' );
		$css->add_property( 'text-align', $css->get_responsive_css( $align, 'Mobile' ) );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/accordion` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_accordion( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = $attributes['blockId'];
	} else {
		$unique_id = rand( 100, 10000 );
	}

	if ( $block_helpers->it_is_not_amp() ) {
		wp_enqueue_script(
			'pbg-accordion',
			PREMIUM_BLOCKS_URL . 'assets/js/accordion.js',
			array( 'jquery' ),
			PREMIUM_BLOCKS_VERSION,
			true
		);
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'accordion', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_accordion_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'accordion', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'accordion' );
			}
		}
	};

	return $content;
}




/**
 * Register the accordion block.
 *
 * @uses render_block_pbg_accordion()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_accordion() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/accordion',
		array(
			'render_callback' => 'render_block_pbg_accordion',
		)
	);
}

register_block_pbg_accordion();
