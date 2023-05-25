<?php
/**
 * Server-side rendering of the `pbg/icon-box` block.
 *
 * @package WordPress
 */

/**
 * Get Icon Box Block CSS
 *
 * Return Frontend CSS for Icon Box.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_icon_box_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// container style
	if ( isset( $attr['containerMargin'] ) ) {
		$container_margin = $attr['containerMargin'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $container_margin['Desktop'], $container_margin['unit'] ) );
	}

	if ( isset( $attr['containerPadding'] ) ) {
		$container_padding = $attr['containerPadding'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $container_padding['Desktop'], $container_padding['unit'] ) );
	}
	if(isset($attr["containerBackground"])){
		$css->set_selector( '.' . $unique_id  );
		$css->render_background( $attr["containerBackground"], 'Desktop' );

	}

	if ( isset( $attr['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Desktop' ) );

		$css->set_selector( '.' . $unique_id . ' .premium-button-group' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Desktop' ) );

		$css->set_selector( '.' . $unique_id . ' .premium-button-group_wrap' );
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['align'], 'Desktop' ) );

		$css->set_selector( '.' . $unique_id . ' .premium-icon-container' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Desktop' ) );
	}

	if ( isset( $attr['containerBorder'] ) ) {
		$container_border        = $attr['containerBorder'];
		$container_border_width  = $container_border['borderWidth'];
		$container_border_radius = $container_border['borderRadius'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Desktop'], 'px' ) );
	}

	$css->start_media_query( 'tablet' );
	// container style
	if ( isset( $attr['containerMargin'] ) ) {
		$container_margin = $attr['containerMargin'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $container_margin['Tablet'], $container_margin['unit'] ) );
	}

	if ( isset( $attr['containerPadding'] ) ) {
		$container_padding = $attr['containerPadding'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $container_padding['Tablet'], $container_padding['unit'] ) );
	}
	if(isset($attr["containerBackground"])){
		$css->set_selector( '.' . $unique_id  );
		$css->render_background( $attr["containerBackground"], 'Tablet' );

	}

	if ( isset( $attr['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Tablet' ) );

		$css->set_selector( '.' . $unique_id . ' .premium-button-group' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Tablet' ) );

		$css->set_selector( '.' . $unique_id . ' .premium-button-group_wrap' );
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['align'], 'Tablet' ) );

		$css->set_selector( '.' . $unique_id . ' .premium-icon-container' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Tablet' ) );
	}

	if ( isset( $attr['containerBorder'] ) ) {
		$container_border        = $attr['containerBorder'];
		$container_border_width  = $container_border['borderWidth'];
		$container_border_radius = $container_border['borderRadius'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Tablet'], 'px' ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );
	// container style
	if ( isset( $attr['containerMargin'] ) ) {
		$container_margin = $attr['containerMargin'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $container_margin['Mobile'], $container_margin['unit'] ) );
	}

	if ( isset( $attr['containerPadding'] ) ) {
		$container_padding = $attr['containerPadding'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $container_padding['Mobile'], $container_padding['unit'] ) );
	}
	if(isset($attr["containerBackground"])){
		$css->set_selector( '.' . $unique_id  );
		$css->render_background( $attr["containerBackground"], 'Mobile' );

	}

	if ( isset( $attr['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Mobile' ) );

		$css->set_selector( '.' . $unique_id . ' .premium-button-group' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Mobile' ) );

		$css->set_selector( '.' . $unique_id . ' .premium-button-group_wrap' );
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['align'], 'Mobile' ) );

		$css->set_selector( '.' . $unique_id . ' .premium-icon-container' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Mobile' ) );
	}

	if ( isset( $attr['containerBorder'] ) ) {
		$container_border        = $attr['containerBorder'];
		$container_border_width  = $container_border['borderWidth'];
		$container_border_radius = $container_border['borderRadius'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Mobile'], 'px' ) );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/icon-box` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_icon_box( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = $attributes['blockId'];
	} else {
		$unique_id = rand( 100, 10000 );
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'icon-box', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_icon_box_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'icon-box', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'icon-box' );
			}
		}
	};

	return $content;
}




/**
 * Register the icon_box block.
 *
 * @uses render_block_pbg_icon_box()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_icon_box() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/icon-box',
		array(
			'render_callback' => 'render_block_pbg_icon_box',
		)
	);
}

register_block_pbg_icon_box();
