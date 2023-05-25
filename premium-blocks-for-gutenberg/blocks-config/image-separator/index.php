<?php
/**
 * Server-side rendering of the `pbg/image-separator` block.
 *
 * @package WordPress
 */

/**
 * Get Image Separator Block CSS
 *
 * Return Frontend CSS for Image Separator.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_image_separator_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// container style
	if ( isset( $attr['iconAlign'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['iconAlign'], 'Desktop' ) );
	}

	if ( isset( $attr['iconAlign'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-image-separator-container' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['iconAlign'], 'Desktop' ) );
	}

	// Icon Style.
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon' );
		$css->add_property( 'font-size', $css->render_range( $attr['iconSize'], 'Desktop' ) );
	}

	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon svg' );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $attr['iconSize'], 'Desktop' ) );
	}

	if ( isset( $attr['iconPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon' );
		$css->add_property( 'padding', $css->render_spacing( $attr['iconPadding']['Desktop'], ( isset( $attr['iconPadding']['unit'] ) ? $attr['iconPadding']['unit'] : 'px' ) ) );
	}

	if ( isset( $attr['iconBorder'] ) && ( isset( $attr['iconStyles'] ) && ( $attr['iconStyles'][0]['advancedBorder'] ) == false ) ) {
		$title_border_width  = $attr['iconBorder']['borderWidth'];
		$title_border_radius = $attr['iconBorder']['borderRadius'];
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Desktop'], 'px' ) );
	}
	// Image style
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . ' > img' );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Desktop' ) );
	}

	if ( isset( $attr['imgHeight'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . ' > img' );
		$css->add_property( 'height', $css->render_range( $attr['imgHeight'], 'Desktop' ) );
	}

	if ( isset( $attr['iconBorder'] ) ) {
		$title_border_width  = $attr['iconBorder']['borderWidth'];
		$title_border_radius = $attr['iconBorder']['borderRadius'];
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> img' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Desktop'], 'px' ) );
	}
	if ( isset( $attr['iconStyles'][0]['advancedBorder'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> img' );
		$css->add_property( 'border-radius', $attr['iconStyles'][0]['advancedBorder'] ? $attr['iconStyles'][0]['advancedBorderValue'] . '!important' : '' );
	}

	if ( isset( $attr['iconStyles'][0]['advancedBorder'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon' );
		$css->add_property( 'border-radius', $attr['iconStyles'][0]['advancedBorder'] ? $attr['iconStyles'][0]['advancedBorderValue'] . '!important' : '' );
	}

	$css->start_media_query( 'tablet' );

	// container style
	if ( isset( $attr['iconAlign'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['iconAlign'], 'Tablet' ) );
	}

	if ( isset( $attr['iconAlign'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-image-separator-container' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['iconAlign'], 'Tablet' ) );
	}

	// Icon Style.
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '>.premium-image-separator-icon' );
		$css->add_property( 'font-size', $css->render_range( $attr['iconSize'], 'Tablet' ) );
	}

	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon svg' );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $attr['iconSize'], 'Tablet' ) );
	}

	if ( isset( $attr['iconPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon' );
		$css->add_property( 'padding', $css->render_spacing( $attr['iconPadding']['Tablet'], ( isset( $attr['iconPadding']['unit'] ) ? $attr['iconPadding']['unit'] : 'px' ) ) );
	}

	if ( isset( $attr['iconBorder'] ) ) {
		$title_border_width  = $attr['iconBorder']['borderWidth'];
		$title_border_radius = $attr['iconBorder']['borderRadius'];
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Tablet'], 'px' ) );
	}

	// Image style
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . ' > img' );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Tablet' ) );
	}

	if ( isset( $attr['imgHeight'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . ' > img' );
		$css->add_property( 'height', $css->render_range( $attr['imgHeight'], 'Tablet' ) );
	}

	if ( isset( $attr['iconBorder'] ) ) {
		$title_border_width  = $attr['iconBorder']['borderWidth'];
		$title_border_radius = $attr['iconBorder']['borderRadius'];
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> img' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Tablet'], 'px' ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );

	// container style
	if ( isset( $attr['iconAlign'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['iconAlign'], 'Mobile' ) );
	}

	if ( isset( $attr['iconAlign'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-image-separator-container' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['iconAlign'], 'Mobile' ) );
	}

	// Icon Style.
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon' );
		$css->add_property( 'font-size', $css->render_range( $attr['iconSize'], 'Mobile' ) );
	}

	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon svg' );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $attr['iconSize'], 'Mobile' ) );
	}

	if ( isset( $attr['iconPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon' );
		$css->add_property( 'padding', $css->render_spacing( $attr['iconPadding']['Mobile'], ( isset( $attr['iconPadding']['unit'] ) ? $attr['iconPadding']['unit'] : 'px' ) ) );
	}

	if ( isset( $attr['iconBorder'] ) ) {
		$title_border_width  = $attr['iconBorder']['borderWidth'];
		$title_border_radius = $attr['iconBorder']['borderRadius'];
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> .premium-image-separator-icon' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Mobile'], 'px' ) );
	}

	// Image style
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . ' > img' );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Mobile' ) );
	}

	if ( isset( $attr['imgHeight'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . ' > img' );
		$css->add_property( 'height', $css->render_range( $attr['imgHeight'], 'Mobile' ) );
	}

	if ( isset( $attr['iconBorder'] ) ) {
		$title_border_width  = $attr['iconBorder']['borderWidth'];
		$title_border_radius = $attr['iconBorder']['borderRadius'];
		$css->set_selector( '.' . $unique_id . '> .premium-image-separator-container' . ' > .premium-image-separator-link' . '> img' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Mobile'], 'px' ) );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/image-separator` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_image_separator( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = $attributes['blockId'];
	} else {
		$unique_id = rand( 100, 10000 );
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'image-separator', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_image_separator_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'image-separator', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'image-separator' );
			}
		}
	};

	return $content;
}




/**
 * Register the image_separator block.
 *
 * @uses render_block_pbg_image_separator()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_image_separator() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/image-separator',
		array(
			'render_callback' => 'render_block_pbg_image_separator',
		)
	);
}

register_block_pbg_image_separator();
