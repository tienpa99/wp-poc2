<?php
// Move this file to "blocks-config" folder with name "counter.php".

/**
 * Server-side rendering of the `premium/counter` block.
 *
 * @package WordPress
 */

function get_premium_counter_css( $attributes, $unique_id ) {
	$block_helpers = pbg_blocks_helper();
	$css           = new Premium_Blocks_css();

	// Desktop Styles.
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'justify-content', $css->get_responsive_css( $attributes['align'], 'Desktop' ) . ' !important' );
	}
	// Number Style
	if ( isset( $attributes['numberTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__increment' );
		$css->render_typography( $attributes['numberTypography'], 'Desktop' );
	}
	if ( isset( $attributes['numberMargin'] ) ) {
		$number_margin = $attributes['numberMargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__increment' );
		$css->add_property( 'margin', $css->render_spacing( $number_margin['Desktop'], $number_margin['unit'] ) );
	}
	if ( isset( $attributes['numberPadding'] ) ) {
		$number_padding = $attributes['numberPadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__increment' );
		$css->add_property( 'padding', $css->render_spacing( $number_padding['Desktop'], $number_padding['unit'] ) );
	}

	// Prefix Style
	if ( isset( $attributes['prefixTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__prefix' );
		$css->render_typography( $attributes['prefixTypography'], 'Desktop' );
	}
	if ( isset( $attributes['prefixMargin'] ) ) {
		$prefix_margin = $attributes['prefixMargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__prefix' );
		$css->add_property( 'margin', $css->render_spacing( $prefix_margin['Desktop'], $prefix_margin['unit'] ) );
	}
	if ( isset( $attributes['prefixPadding'] ) ) {
		$prefix_padding = $attributes['prefixPadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__prefix' );
		$css->add_property( 'padding', $css->render_spacing( $prefix_padding['Desktop'], $prefix_padding['unit'] ) );
	}

	// Suffix Style
	if ( isset( $attributes['suffixTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__suffix' );
		$css->render_typography( $attributes['suffixTypography'], 'Desktop' );
	}
	if ( isset( $attributes['suffixMargin'] ) ) {
		$suffix_margin = $attributes['suffixMargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__suffix' );
		$css->add_property( 'margin', $css->render_spacing( $suffix_margin['Desktop'], $suffix_margin['unit'] ) );
	}
	if ( isset( $attributes['suffixPadding'] ) ) {
		$suffix_padding = $attributes['suffixPadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__suffix' );
		$css->add_property( 'padding', $css->render_spacing( $suffix_padding['Desktop'], $suffix_padding['unit'] ) );
	}

	$css->start_media_query( 'tablet' );
	// Tablet Styles.
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'justify-content', $css->get_responsive_css( $attributes['align'], 'Tablet' ) . ' !important' );
	}
	// Number Style
	if ( isset( $attributes['numberTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__increment' );
		$css->render_typography( $attributes['numberTypography'], 'Tablet' );
	}
	if ( isset( $attributes['numberMargin'] ) ) {
		$number_margin = $attributes['numberMargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__increment' );
		$css->add_property( 'margin', $css->render_spacing( $number_margin['Tablet'], $number_margin['unit'] ) );
	}
	if ( isset( $attributes['numberPadding'] ) ) {
		$number_padding = $attributes['numberPadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__increment' );
		$css->add_property( 'padding', $css->render_spacing( $number_padding['Tablet'], $number_padding['unit'] ) );
	}

	// Prefix Style
	if ( isset( $attributes['prefixTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__prefix' );
		$css->render_typography( $attributes['prefixTypography'], 'Tablet' );
	}
	if ( isset( $attributes['prefixMargin'] ) ) {
		$prefix_margin = $attributes['prefixMargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__prefix' );
		$css->add_property( 'margin', $css->render_spacing( $prefix_margin['Tablet'], $prefix_margin['unit'] ) );
	}
	if ( isset( $attributes['prefixPadding'] ) ) {
		$prefix_padding = $attributes['prefixPadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__prefix' );
		$css->add_property( 'padding', $css->render_spacing( $prefix_padding['Tablet'], $prefix_padding['unit'] ) );
	}

	// Suffix Style
	if ( isset( $attributes['suffixTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__suffix' );
		$css->render_typography( $attributes['suffixTypography'], 'Tablet' );
	}
	if ( isset( $attributes['suffixMargin'] ) ) {
		$suffix_margin = $attributes['suffixMargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__suffix' );
		$css->add_property( 'margin', $css->render_spacing( $suffix_margin['Tablet'], $suffix_margin['unit'] ) );
	}
	if ( isset( $attributes['suffixPadding'] ) ) {
		$suffix_padding = $attributes['suffixPadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__suffix' );
		$css->add_property( 'padding', $css->render_spacing( $suffix_padding['Tablet'], $suffix_padding['unit'] ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );
	// Mobile Styles.
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'justify-content', $css->get_responsive_css( $attributes['align'], 'Mobile' ) . ' !important' );
	}
	// Number Style
	if ( isset( $attributes['numberTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__increment' );
		$css->render_typography( $attributes['numberTypography'], 'Mobile' );
	}
	if ( isset( $attributes['numberMargin'] ) ) {
		$number_margin = $attributes['numberMargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__increment' );
		$css->add_property( 'margin', $css->render_spacing( $number_margin['Mobile'], $number_margin['unit'] ) );
	}
	if ( isset( $attributes['numberPadding'] ) ) {
		$number_padding = $attributes['numberPadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__increment' );
		$css->add_property( 'padding', $css->render_spacing( $number_padding['Mobile'], $number_padding['unit'] ) );
	}

	// Prefix Style
	if ( isset( $attributes['prefixTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__prefix' );
		$css->render_typography( $attributes['prefixTypography'], 'Mobile' );
	}
	if ( isset( $attributes['prefixMargin'] ) ) {
		$prefix_margin = $attributes['prefixMargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__prefix' );
		$css->add_property( 'margin', $css->render_spacing( $prefix_margin['Mobile'], $prefix_margin['unit'] ) );
	}
	if ( isset( $attributes['prefixPadding'] ) ) {
		$prefix_padding = $attributes['prefixPadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__prefix' );
		$css->add_property( 'padding', $css->render_spacing( $prefix_padding['Mobile'], $prefix_padding['unit'] ) );
	}

	// Suffix Style
	if ( isset( $attributes['suffixTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__suffix' );
		$css->render_typography( $attributes['suffixTypography'], 'Mobile' );
	}
	if ( isset( $attributes['suffixMargin'] ) ) {
		$suffix_margin = $attributes['suffixMargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__suffix' );
		$css->add_property( 'margin', $css->render_spacing( $suffix_margin['Mobile'], $suffix_margin['unit'] ) );
	}
	if ( isset( $attributes['suffixPadding'] ) ) {
		$suffix_padding = $attributes['suffixPadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-countup__desc' . ' > .premium-countup__suffix' );
		$css->add_property( 'padding', $css->render_spacing( $suffix_padding['Mobile'], $suffix_padding['unit'] ) );
	}

	$css->stop_media_query();

	return $css->css_output();
}

/**
 * Renders the `premium/counter` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_counter( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	$unique_id     = rand( 100, 10000 );
	$id            = 'premium-counter-' . esc_attr( $unique_id );
	$block_id      = ( ! empty( $attributes['blockId'] ) ) ? $attributes['blockId'] : $id;

	if ( ! wp_style_is( $unique_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'counter', $unique_id ) ) {
		$css = get_premium_counter_css( $attributes, $block_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'counter', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'counter' );
			}
		}
	};

		// wp_enqueue_script(
		// 'pbg-waypoints',
		// PREMIUM_BLOCKS_URL . 'assets/js/lib/jquery.waypoints.js',
		// array( 'jquery' ),
		// PREMIUM_BLOCKS_VERSION,
		// true
		// );
		// wp_enqueue_script(
		// 'pbg-count',
		// PREMIUM_BLOCKS_URL . 'assets/js/lib/countUpmin.js',
		// array( 'jquery' ),
		// PREMIUM_BLOCKS_VERSION,
		// true
		// );
		// wp_enqueue_script(
		// 'pbg-countup',
		// PREMIUM_BLOCKS_URL . 'assets/js/countup.js',
		// array( 'jquery' ),
		// PREMIUM_BLOCKS_VERSION,
		// true
		// );

	return $content;
}


/**
 * Register the Price block.
 *
 * @uses render_block_pbg_counter()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_counter() {
	register_block_type(
		'premium/counter',
		array(
			'render_callback' => 'render_block_pbg_counter',
			'editor_style'    => 'premium-blocks-editor-css',
			'editor_script'   => 'pbg-blocks-js',
		)
	);
}

register_block_pbg_counter();
