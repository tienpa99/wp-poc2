<?php
/**
 * Server-side rendering of the `pbg/person` block.
 *
 * @package WordPress
 */

/**
 * Get Person Block CSS
 *
 * Return Frontend CSS for Person.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_person_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// style for container
	if ( isset( $attr['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Desktop' ) );
	}

	// style for Content
	if ( isset( $attr['contentPadding'] ) ) {
		$content_padding = $attr['contentPadding'];
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $content_padding['Desktop'], $content_padding['unit'] ) );
	}

	$css->start_media_query( 'tablet' );

	// style for container
	if ( isset( $attr['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Tablet' ) );
	}

	// style for Content
	if ( isset( $attr['contentPadding'] ) ) {
		$content_padding = $attr['contentPadding'];
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $content_padding['Tablet'], $content_padding['unit'] ) );
	}

	$css->stop_media_query();

	$css->start_media_query( 'mobile' );

	// style for container
	if ( isset( $attr['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attr['align'], 'Mobile' ) );
	}

	// style for Content
	if ( isset( $attr['contentPadding'] ) ) {
		$content_padding = $attr['contentPadding'];
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $content_padding['Mobile'], $content_padding['unit'] ) );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/person` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_person( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = $attributes['blockId'];
	} else {
		$unique_id = rand( 100, 10000 );
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'person', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_person_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'person', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'person' );
			}
		}
	};

	return $content;
}




/**
 * Register the person block.
 *
 * @uses render_block_pbg_person()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_person() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/person',
		array(
			'render_callback' => 'render_block_pbg_person',
		)
	);
}

register_block_pbg_person();
