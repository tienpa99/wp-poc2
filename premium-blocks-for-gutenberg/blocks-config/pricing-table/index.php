<?php
/**
 * Server-side rendering of the `pbg/pricing-table` block.
 *
 * @package WordPress
 */

/**
 * Get Pricing Table Block CSS
 *
 * Return Frontend CSS for Pricing Table.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_pricing_table_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// Table.
	if ( isset( $attr['tableBorder'] ) ) {
		$table_border        = $attr['tableBorder'];
		$table_border_width  = $table_border['borderWidth'];
		$table_border_radius = $table_border['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $table_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $table_border_radius['Desktop'], 'px' ) );
	}

	if ( isset( $attr['tablePadding'] ) ) {
		$table_padding = $attr['tablePadding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $table_padding['Desktop'], $table_padding['unit'] ) );
	}

	$css->start_media_query( 'tablet' );

	// Table.
	if ( isset( $attr['tableBorder'] ) ) {
		$table_border        = $attr['tableBorder'];
		$table_border_width  = $table_border['borderWidth'];
		$table_border_radius = $table_border['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $table_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $table_border_radius['Tablet'], 'px' ) );
	}

	if ( isset( $attr['tablePadding'] ) ) {
		$table_padding = $attr['tablePadding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $table_padding['Tablet'], $table_padding['unit'] ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );

	// Table.
	if ( isset( $attr['tableBorder'] ) ) {
		$table_border        = $attr['tableBorder'];
		$table_border_width  = $table_border['borderWidth'];
		$table_border_radius = $table_border['borderRadius'];

		$css->set_selector( $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $table_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $table_border_radius['Mobile'], 'px' ) );
	}

	if ( isset( $attr['tablePadding'] ) ) {
		$table_padding = $attr['tablePadding'];
		$css->set_selector( $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $table_padding['Mobile'], $table_padding['unit'] ) );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/pricing-table` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_pricing_table( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['block_id'] ) && ! empty( $attributes['block_id'] ) ) {
		$unique_id = "#premium-pricing-table-{$attributes['block_id']}";
	}

	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = ".{$attributes['blockId']}";
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'pricing-table', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_pricing_table_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'pricing-table', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'pricing-table' );
			}
		}
	};

	return $content;
}

/**
 * Register the pricing_table block.
 *
 * @uses render_block_pbg_pricing_table()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_pricing_table() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/pricing-table',
		array(
			'render_callback' => 'render_block_pbg_pricing_table',
		)
	);
}

register_block_pbg_pricing_table();
