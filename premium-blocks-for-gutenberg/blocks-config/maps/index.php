<?php

/**
 * Server-side rendering of the `pbg/maps` block.
 *
 * @package WordPress
 */

/**
 * Get Maps Block CSS
 *
 * Return Frontend CSS for Maps.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_maps_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// Map.
	if ( isset( $attr['mapPadding'] ) ) {
		$map_padding = $attr['mapPadding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $map_padding['Desktop'], $map_padding['unit'] ) );
	}

	if ( isset( $attr['mapMargin'] ) ) {
		$map_margin = $attr['mapMargin'];
		$css->set_selector( $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $map_margin['Desktop'], $map_margin['unit'] ) );
	}

	if ( isset( $attr['mapBorder'] ) ) {
		$map_border        = $attr['mapBorder'];
		$map_border_width  = $map_border['borderWidth'];
		$map_border_radius = $map_border['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $map_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $map_border_radius['Desktop'], 'px' ) );
	}
	if ( isset( $attr['mapBorder'] ) ) {
		$map_border        = $attr['mapBorder'];
		$map_border_radius = $map_border['borderRadius'];

		$css->set_selector( $unique_id . ' > .map-container' );
		$css->add_property( 'border-radius', $css->render_spacing( $map_border_radius['Desktop'], 'px' ) );
	}
	// Title.
	if ( isset( $attr['titleTypography'] ) ) {
		$title_typography = $attr['titleTypography'];

		$css->set_selector( $unique_id . ' .premium-maps__wrap__title' );
		$css->render_typography( $title_typography, 'Desktop' );
	}

	if ( isset( $attr['titlePadding'] ) ) {
		$title_padding = $attr['titlePadding'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__title' );
		$css->add_property( 'padding', $css->render_spacing( $title_padding['Desktop'], $title_padding['unit'] ) );
	}

	if ( isset( $attr['titleMargin'] ) ) {
		$title_margin = $attr['titleMargin'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__title' );
		$css->add_property( 'margin', $css->render_spacing( $title_margin['Desktop'], $title_margin['unit'] ) );
	}
	// Description.
	if ( isset( $attr['descriptionTypography'] ) ) {
		$description_typography = $attr['descriptionTypography'];

		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->render_typography( $description_typography, 'Desktop' );
	}

	if ( isset( $attr['descriptionPadding'] ) ) {
		$description_padding = $attr['descriptionPadding'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->add_property( 'padding', $css->render_spacing( $description_padding['Desktop'], $description_padding['unit'] ) );
	}

	if ( isset( $attr['descriptionMargin'] ) ) {
		$description_margin = $attr['descriptionMargin'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->add_property( 'margin', $css->render_spacing( $description_margin['Desktop'], $description_margin['unit'] ) );
	}

	if ( isset( $attr['boxAlign'] ) ) {

		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['boxAlign'], 'Desktop' ) );
	}
	$css->start_media_query( 'tablet' );

	// Map.
	if ( isset( $attr['mapPadding'] ) ) {
		$map_padding = $attr['mapPadding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $map_padding['Tablet'], $map_padding['unit'] ) );
	}

	if ( isset( $attr['mapMargin'] ) ) {
		$map_margin = $attr['mapMargin'];
		$css->set_selector( $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $map_margin['Tablet'], $map_margin['unit'] ) );
	}

	if ( isset( $attr['mapBorder'] ) ) {
		$map_border        = $attr['mapBorder'];
		$map_border_width  = $attr['mapBorder']['borderWidth'];
		$map_border_radius = $attr['mapBorder']['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $map_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $map_border_radius['Tablet'], 'px' ) );
	}
	if ( isset( $attr['mapBorder'] ) ) {
		$map_border        = $attr['mapBorder'];
		$map_border_radius = $attr['mapBorder']['borderRadius'];

		$css->set_selector( $unique_id . ' > .map-container' );
		$css->add_property( 'border-radius', $css->render_spacing( $map_border_radius['Tablet'], 'px' ) );
	}
	// Title.
	if ( isset( $attr['titleTypography'] ) ) {
		$title_typography = $attr['titleTypography'];

		$css->set_selector( $unique_id . ' .premium-maps__wrap__title' );
		$css->render_typography( $title_typography, 'Tablet' );
	}

	if ( isset( $attr['titlePadding'] ) ) {
		$title_padding = $attr['titlePadding'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__title' );
		$css->add_property( 'padding', $css->render_spacing( $title_padding['Tablet'], $title_padding['unit'] ) );
	}

	if ( isset( $attr['titleMargin'] ) ) {
		$title_margin = $attr['titleMargin'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__title' );
		$css->add_property( 'margin', $css->render_spacing( $title_margin['Tablet'], $title_margin['unit'] ) );
	}
	// Description.
	if ( isset( $attr['descriptionTypography'] ) ) {
		$description_typography = $attr['descriptionTypography'];

		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->render_typography( $description_typography, 'Tablet' );
	}

	if ( isset( $attr['descriptionPadding'] ) ) {
		$description_padding = $attr['descriptionPadding'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->add_property( 'padding', $css->render_spacing( $description_padding['Tablet'], $description_padding['unit'] ) );
	}

	if ( isset( $attr['descriptionMargin'] ) ) {
		$description_margin = $attr['descriptionMargin'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->add_property( 'margin', $css->render_spacing( $description_margin['Tablet'], $description_margin['unit'] ) );
	}

	if ( isset( $attr['boxAlign'] ) ) {

		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['boxAlign'], 'Tablet' ) );
	}
	$css->stop_media_query();
	$css->start_media_query( 'mobile' );

	// Map.
	if ( isset( $attr['mapPadding'] ) ) {
		$map_padding = $attr['mapPadding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $map_padding['Mobile'], $map_padding['unit'] ) );
	}

	if ( isset( $attr['mapMargin'] ) ) {
		$map_margin = $attr['mapMargin'];
		$css->set_selector( $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $map_margin['Mobile'], $map_margin['unit'] ) );
	}

	if ( isset( $attr['mapBorder'] ) ) {
		$map_border        = $attr['mapBorder'];
		$map_border_width  = $attr['mapBorder']['borderWidth'];
		$map_border_radius = $attr['mapBorder']['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $map_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $map_border_radius['Mobile'], 'px' ) );
	}
	if ( isset( $attr['mapBorder'] ) ) {
		$map_border        = $attr['mapBorder'];
		$map_border_radius = $attr['mapBorder']['borderRadius'];

		$css->set_selector( $unique_id . ' > .map-container' );
		$css->add_property( 'border-radius', $css->render_spacing( $map_border_radius['Mobile'], 'px' ) );
	}
	// Title.
	if ( isset( $attr['titleTypography'] ) ) {
		$title_typography = $attr['titleTypography'];

		$css->set_selector( $unique_id . ' .premium-maps__wrap__title' );
		$css->render_typography( $title_typography, 'Mobile' );
	}

	if ( isset( $attr['titlePadding'] ) ) {
		$title_padding = $attr['titlePadding'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__title' );
		$css->add_property( 'padding', $css->render_spacing( $title_padding['Mobile'], $title_padding['unit'] ) );
	}

	if ( isset( $attr['titleMargin'] ) ) {
		$title_margin = $attr['titleMargin'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__title' );
		$css->add_property( 'margin', $css->render_spacing( $title_margin['Mobile'], $title_margin['unit'] ) );
	}
	// Description.
	if ( isset( $attr['descriptionTypography'] ) ) {
		$description_typography = $attr['descriptionTypography'];

		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->render_typography( $description_typography, 'Mobile' );
	}

	if ( isset( $attr['descriptionPadding'] ) ) {
		$description_padding = $attr['descriptionPadding'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->add_property( 'padding', $css->render_spacing( $description_padding['Mobile'], $description_padding['unit'] ) );
	}

	if ( isset( $attr['descriptionMargin'] ) ) {
		$description_margin = $attr['descriptionMargin'];
		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->add_property( 'margin', $css->render_spacing( $description_margin['Mobile'], $description_margin['unit'] ) );
	}

	if ( isset( $attr['boxAlign'] ) ) {

		$css->set_selector( $unique_id . ' .premium-maps__wrap__desc' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['boxAlign'], 'Mobile' ) );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/maps` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_maps( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = ".{$attributes['blockId']}";
	} else {
		$unique_id = rand( 100, 10000 );
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'maps', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_maps_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'maps', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'maps' );
			}
		}
	};

	return $content;
}




/**
 * Register the maps block.
 *
 * @uses render_block_pbg_maps()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_maps() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/maps',
		array(
			'render_callback' => 'render_block_pbg_maps',
		)
	);
}

register_block_pbg_maps();
