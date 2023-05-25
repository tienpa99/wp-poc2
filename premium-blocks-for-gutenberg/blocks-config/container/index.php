<?php

/**
 * Server-side rendering of the `pbg/container` block.
 *
 * @package WordPress
 */

/**
 * Get Container Block CSS
 *
 * Return Frontend CSS for Container.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_container_css_style( $attr, $unique_id ) {
	 $css = new Premium_Blocks_css();

	$css->set_selector( '.wp-block-premium-container.premium-is-root-container.premium-block-' . $unique_id . '>  .premium-container-inner-blocks-wrap' );
	if ( isset( $attr['minHeight'] ) ) {
		$css->add_property( 'min-height', $css->render_range( $attr['minHeight'], 'Desktop' ) );
	}
	if ( isset( $attr['direction'] ) ) {
		$css->add_property( 'flex-direction', $css->get_responsive_css( $attr['direction'], 'Desktop' ) );
	}
	if ( isset( $attr['alignItems'] ) ) {
		$css->add_property( 'align-items', $css->get_responsive_css( $attr['alignItems'], 'Desktop' ) );
	}
	if ( isset( $attr['justifyItems'] ) ) {
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['justifyItems'], 'Desktop' ) );
	}
	if ( isset( $attr['wrapItems'] ) ) {
		$css->add_property( 'flex-wrap', $css->get_responsive_css( $attr['wrapItems'], 'Desktop' ) );
	}
	if ( isset( $attr['alignContent'] ) ) {
		$css->add_property( 'align-content', $css->get_responsive_css( $attr['alignContent'], 'Desktop' ) );
	}

	// $css->add_property( 'row-gap', isset( $attr['rowGutter']['Desktop'] ) ? $attr['rowGutter']['Desktop'] . $attr['rowGutter']['unit'] : '20px' );

	// $css->add_property( 'column-gap', isset( $attr['columnGutter']['Desktop'] ) ? $attr['columnGutter']['Desktop'] . $attr['columnGutter']['unit'] : '20px' );
	$css->add_property( 'row-gap', isset( $attr['rowGutter']['Desktop'] ) ? $css->render_range( $attr['rowGutter'], 'Desktop' ) : '20px' );
	$css->add_property( 'column-gap', isset( $attr['columnGutter']['Desktop'] ) ? $css->render_range( $attr['columnGutter'], 'Desktop' ) : '20px' );

	$css->set_selector( '.wp-block-premium-container.premium-is-root-container  .premium-container-inner-blocks-wrap .premium-block-' . $unique_id . '  > .premium-container-inner-blocks-wrap' );

	if ( isset( $attr['minHeight'] ) ) {
		$css->add_property( 'min-height', $css->render_range( $attr['minHeight'], 'Desktop' ) );
	}
	if ( isset( $attr['direction'] ) ) {
		$css->add_property( 'flex-direction', $css->get_responsive_css( $attr['direction'], 'Desktop' ) );
	}
	if ( isset( $attr['alignItems'] ) ) {
		$css->add_property( 'align-items', $css->get_responsive_css( $attr['alignItems'], 'Desktop' ) );
	}
	if ( isset( $attr['justifyItems'] ) ) {
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['justifyItems'], 'Desktop' ) );
	}
	if ( isset( $attr['wrapItems'] ) ) {
		$css->add_property( 'flex-wrap', $css->get_responsive_css( $attr['wrapItems'], 'Desktop' ) );
	}
	if ( isset( $attr['alignContent'] ) ) {
		$css->add_property( 'align-content', $css->get_responsive_css( $attr['alignContent'], 'Desktop' ) );
	}
	// $css->add_property( 'row-gap', isset( $attr['rowGutter']['Desktop'] ) ? $attr['rowGutter']['Desktop'] . $attr['rowGutter']['unit'] : '20px' );
	$css->add_property( 'row-gap', isset( $attr['rowGutter']['Desktop'] ) ? $css->render_range( $attr['rowGutter'], 'Desktop' ) : '20px' );
	$css->add_property( 'column-gap', isset( $attr['columnGutter']['Desktop'] ) ? $css->render_range( $attr['columnGutter'], 'Desktop' ) : '20px' );

	// $css->add_property( 'column-gap', isset( $attr['columnGutter']['Desktop'] ) ? $attr['columnGutter']['Desktop'] . $attr['columnGutter']['unit'] : '20px' );
	if ( isset( $attr['colWidth'] ) ) {
		$css->set_selector( '.wp-block-premium-container.premium-is-root-container .premium-block-' . $unique_id );
		$css->add_property( 'max-width', $css->render_range( $attr['colWidth'], 'Desktop' ) );
		$css->add_property( 'width', $css->render_range( $attr['colWidth'], 'Desktop' ) );
	}
	if ( isset( $attr['shapeTop'] ) ) {
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id . ' .premium-top-shape svg' );
		$css->add_property( 'width', $css->render_range( $attr['shapeTop']['width'], 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $attr['shapeTop']['height'], 'Desktop' ) );
	}
	if ( isset( $attr['shapeBottom'] ) ) {
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id . ' .premium-top-bottom svg' );
		$css->add_property( 'width', $css->render_range( $attr['shapeBottom']['width'], 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $attr['shapeBottom']['height'], 'Desktop' ) );
	}
	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Desktop'], $padding['unit'] ) );
	}
	if(isset($attr["backgroundOptions"])){
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id );
		$css->render_background( $attr["backgroundOptions"], 'Desktop' );

	}
	if ( isset( $attr['margin'] ) ) {
		$margin = $attr['margin'];
		$css->set_selector( 'body .entry-content > .wp-block-premium-container.premium-block-' . $unique_id . ', .wp-block-premium-container.premium-block-' . $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $margin['Desktop'], $margin['unit'] ) );
	}
	if ( isset( $attr['border'] ) ) {
		$border        = $attr['border'];
		$border_width  = $border['borderWidth'];
		$border_radius = $border['borderRadius'];

		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Desktop'], 'px' ) );
	}

	$css->start_media_query( 'tablet' );

	$css->set_selector( '.wp-block-premium-container.premium-is-root-container.premium-block-' . $unique_id . ' .premium-container-inner-blocks-wrap' );
	if ( isset( $attr['minHeight'] ) ) {
		$css->add_property( 'min-height', $css->render_range( $attr['minHeight'], 'Tablet' ) );
	}
	if ( isset( $attr['direction'] ) ) {
		$css->add_property( 'flex-direction', $css->get_responsive_css( $attr['direction'], 'Tablet' ) );
	}
	if ( isset( $attr['alignItems'] ) ) {
		$css->add_property( 'align-items', $css->get_responsive_css( $attr['alignItems'], 'Tablet' ) );
	}
	if ( isset( $attr['justifyItems'] ) ) {
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['justifyItems'], 'Tablet' ) );
	}
	if ( isset( $attr['wrapItems'] ) ) {
		$css->add_property( 'flex-wrap', $css->get_responsive_css( $attr['wrapItems'], 'Tablet' ) );
	}
	if ( isset( $attr['alignContent'] ) ) {
		$css->add_property( 'align-content', $css->get_responsive_css( $attr['alignContent'], 'Tablet' ) );
	}
	// $css->add_property( 'row-gap', isset( $attr['rowGutter']['Tablet'] ) ? $attr['rowGutter']['Tablet'] . $attr['rowGutter']['unit'] : '20px' );
	// $css->add_property( 'column-gap', isset( $attr['rowGutter']['Tablet'] ) ? $attr['columnGutter']['Tablet'] . $attr['columnGutter']['unit'] : '20px' );
	$css->add_property( 'row-gap', isset( $attr['rowGutter']['Tablet'] ) ? $css->render_range( $attr['rowGutter'], 'Tablet' ) : '20px' );
	$css->add_property( 'column-gap', isset( $attr['columnGutter']['Tablet'] ) ? $css->render_range( $attr['columnGutter'], 'Tablet' ) : '20px' );

	$css->set_selector( '.wp-block-premium-container.premium-is-root-container  .premium-container-inner-blocks-wrap .premium-block-' . $unique_id . ' .premium-container-inner-blocks-wraps' );
	if ( isset( $attr['minHeight'] ) ) {
		$css->add_property( 'min-height', $css->render_range( $attr['minHeight'], 'Tablet' ) );
	}
	if ( isset( $attr['direction'] ) ) {
		$css->add_property( 'flex-direction', $css->get_responsive_css( $attr['direction'], 'Tablet' ) );
	}
	if ( isset( $attr['alignItems'] ) ) {
		$css->add_property( 'align-items', $css->get_responsive_css( $attr['alignItems'], 'Tablet' ) );
	}
	if ( isset( $attr['justifyItems'] ) ) {
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['justifyItems'], 'Tablet' ) );
	}
	if ( isset( $attr['wrapItems'] ) ) {
		$css->add_property( 'flex-wrap', $css->get_responsive_css( $attr['wrapItems'], 'Tablet' ) );
	}
	if ( isset( $attr['alignContent'] ) ) {
		$css->add_property( 'align-content', $css->get_responsive_css( $attr['alignContent'], 'Tablet' ) );
	}
	// $css->add_property( 'row-gap', isset( $attr['rowGutter']['Tablet'] ) ? $attr['rowGutter']['Tablet'] . $attr['rowGutter']['unit'] : '20px' );
	// $css->add_property( 'column-gap', isset( $attr['rowGutter']['Tablet'] ) ? $attr['columnGutter']['Tablet'] . $attr['columnGutter']['unit'] : '20px' );
	$css->add_property( 'row-gap', isset( $attr['rowGutter']['Tablet'] ) ? $css->render_range( $attr['rowGutter'], 'Tablet' ) : '20px' );
	$css->add_property( 'column-gap', isset( $attr['columnGutter']['Tablet'] ) ? $css->render_range( $attr['columnGutter'], 'Tablet' ) : '20px' );

	if ( isset( $attr['colWidth'] ) ) {
		$css->set_selector( '.wp-block-premium-container.premium-is-root-container .premium-block-' . $unique_id );
		$css->add_property( 'max-width', $css->render_range( $attr['colWidth'], 'Tablet' ) );
		$css->add_property( 'width', $css->render_range( $attr['colWidth'], 'Tablet' ) );
	}
	if ( isset( $attr['shapeTop'] ) ) {
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id . ' .premium-top-shape svg' );
		$css->add_property( 'width', $css->render_range( $attr['shapeTop']['width'], 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $attr['shapeTop']['height'], 'Tablet' ) );
	}
	if ( isset( $attr['shapeBottom'] ) ) {
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id . ' .premium-top-bottom svg' );
		$css->add_property( 'width', $css->render_range( $attr['shapeBottom']['width'], 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $attr['shapeBottom']['height'], 'Tablet' ) );
	}
	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Tablet'], $padding['unit'] ) );
	}
	if(isset($attr["backgroundOptions"])){
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id );
		$css->render_background( $attr["backgroundOptions"], 'Tablet' );

	}
	if ( isset( $attr['margin'] ) ) {
		$margin = $attr['margin'];
		$css->set_selector( 'body .entry-content > .wp-block-premium-container.premium-block-' . $unique_id . ', .wp-block-premium-container.premium-block-' . $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $margin['Tablet'], $margin['unit'] ) );
	}
	if ( isset( $attr['border'] ) ) {
		$border        = $attr['border'];
		$border_width  = $border['borderWidth'];
		$border_radius = $border['borderRadius'];

		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Tablet'], 'px' ) );
	}

	$css->stop_media_query();

	$css->start_media_query( 'mobile' );

	$css->set_selector( '.wp-block-premium-container.premium-is-root-container.premium-block-' . $unique_id . ' .premium-container-inner-blocks-wrap' );
	if ( isset( $attr['minHeight'] ) ) {
		$css->add_property( 'min-height', $css->render_range( $attr['minHeight'], 'Mobile' ) );
	}
	if ( isset( $attr['direction'] ) ) {
		$css->add_property( 'flex-direction', $css->get_responsive_css( $attr['direction'], 'Mobile' ) );
	}
	if ( isset( $attr['alignItems'] ) ) {
		$css->add_property( 'align-items', $css->get_responsive_css( $attr['alignItems'], 'Mobile' ) );
	}
	if ( isset( $attr['justifyItems'] ) ) {
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['justifyItems'], 'Mobile' ) );
	}
	if ( isset( $attr['wrapItems'] ) ) {
		$css->add_property( 'flex-wrap', $css->get_responsive_css( $attr['wrapItems'], 'Mobile' ) );
	}
	if ( isset( $attr['alignContent'] ) ) {
		$css->add_property( 'align-content', $css->get_responsive_css( $attr['alignContent'], 'Mobile' ) );
	}
	// $css->add_property( 'row-gap', isset( $attr['rowGutter']['Mobile'] ) ? $attr['rowGutter']['Mobile'] . $attr['rowGutter']['unit'] : '20px' );
	// $css->add_property( 'column-gap', isset( $attr['rowGutter']['Mobile'] ) ? $attr['columnGutter']['Mobile'] . $attr['columnGutter']['unit'] : '20px' );
	$css->add_property( 'row-gap', isset( $attr['rowGutter']['Mobile'] ) ? $css->render_range( $attr['rowGutter'], 'Mobile' ) : '20px' );
	$css->add_property( 'column-gap', isset( $attr['columnGutter']['Mobile'] ) ? $css->render_range( $attr['columnGutter'], 'Mobile' ) : '20px' );

	$css->set_selector( '.wp-block-premium-container.premium-is-root-container  .premium-container-inner-blocks-wrap .premium-block-' . $unique_id . ' .premium-container-inner-blocks-wraps' );
	if ( isset( $attr['minHeight'] ) ) {
		$css->add_property( 'min-height', $css->render_range( $attr['minHeight'], 'Mobile' ) );
	}
	if ( isset( $attr['direction'] ) ) {
		$css->add_property( 'flex-direction', $css->get_responsive_css( $attr['direction'], 'Mobile' ) );
	}
	if ( isset( $attr['alignItems'] ) ) {
		$css->add_property( 'align-items', $css->get_responsive_css( $attr['alignItems'], 'Mobile' ) );
	}
	if ( isset( $attr['justifyItems'] ) ) {
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['justifyItems'], 'Mobile' ) );
	}
	if ( isset( $attr['wrapItems'] ) ) {
		$css->add_property( 'flex-wrap', $css->get_responsive_css( $attr['wrapItems'], 'Mobile' ) );
	}
	if ( isset( $attr['alignContent'] ) ) {
		$css->add_property( 'align-content', $css->get_responsive_css( $attr['alignContent'], 'Mobile' ) );
	}
	// $css->add_property( 'row-gap', isset( $attr['rowGutter']['Mobile'] ) ? $attr['rowGutter']['Mobile'] . $attr['rowGutter']['unit'] : '20px' );
	// $css->add_property( 'column-gap', isset( $attr['rowGutter']['Mobile'] ) ? $attr['columnGutter']['Mobile'] . $attr['columnGutter']['unit'] : '20px' );
	$css->add_property( 'row-gap', isset( $attr['rowGutter']['Mobile'] ) ? $css->render_range( $attr['rowGutter'], 'Mobile' ) : '20px' );
	$css->add_property( 'column-gap', isset( $attr['columnGutter']['Mobile'] ) ? $css->render_range( $attr['columnGutter'], 'Mobile' ) : '20px' );

	if ( isset( $attr['colWidth'] ) ) {
		$css->set_selector( '.wp-block-premium-container.premium-is-root-container .premium-block-' . $unique_id );
		$css->add_property( 'max-width', $css->render_range( $attr['colWidth'], 'Mobile' ) );
		$css->add_property( 'width', $css->render_range( $attr['colWidth'], 'Mobile' ) );
	}
	if ( isset( $attr['shapeTop'] ) ) {
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id . ' .premium-top-shape svg' );
		$css->add_property( 'width', $css->render_range( $attr['shapeTop']['width'], 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $attr['shapeTop']['height'], 'Mobile' ) );
	}
	if ( isset( $attr['shapeBottom'] ) ) {
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id . ' .premium-top-bottom svg' );
		$css->add_property( 'width', $css->render_range( $attr['shapeBottom']['width'], 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $attr['shapeBottom']['height'], 'Mobile' ) );
	}
	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Mobile'], $padding['unit'] ) );
	}
	if(isset($attr["backgroundOptions"])){
		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id );
		$css->render_background( $attr["backgroundOptions"], 'Mobile' );

	}
	if ( isset( $attr['margin'] ) ) {
		$margin = $attr['margin'];
		$css->set_selector( 'body .entry-content > .wp-block-premium-container.premium-block-' . $unique_id . ', .wp-block-premium-container.premium-block-' . $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $margin['Mobile'], $margin['unit'] ) );
	}
	if ( isset( $attr['border'] ) ) {
		$border        = $attr['border'];
		$border_width  = $border['borderWidth'];
		$border_radius = $border['borderRadius'];

		$css->set_selector( '.wp-block-premium-container.premium-block-' . $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Mobile'], 'px' ) );
	}
	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/container` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_container( $attributes, $content, $block ) {
	$block_helpers   = pbg_blocks_helper();
	$global_features = apply_filters( 'pb_global_features', get_option( 'pbg_global_features', array() ) );

	if ( $global_features['premium-equal-height'] && isset( $attributes['equalHeight'] ) && $attributes['equalHeight'] ) {
		add_filter(
			'premium_equal_height_localize_script',
			function ( $data ) use ( $attributes ) {
				$data[ $attributes['block_id'] ] = array(
					'attributes' => $attributes,
				);
				return $data;
			}
		);
	}

	if ( isset( $attributes['block_id'] ) && ! empty( $attributes['block_id'] ) ) {
		$unique_id = $attributes['block_id'];
	} else {
		$unique_id = rand( 100, 10000 );
	}

	// Enqueue frontend JavaScript and CSS.
	if ( $block_helpers->it_is_not_amp() ) {
		wp_enqueue_script(
			'pbg-animation',
			PREMIUM_BLOCKS_URL . 'assets/js/animation.js',
			array( 'jquery' ),
			PREMIUM_BLOCKS_VERSION,
			true
		);
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'container', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_container_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'container', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'container' );
			}
		}
	};

	return $content;
}




/**
 * Register the container block.
 *
 * @uses render_block_pbg_container()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_container() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/container',
		array(
			'render_callback' => 'render_block_pbg_container',
		)
	);
}

register_block_pbg_container();
