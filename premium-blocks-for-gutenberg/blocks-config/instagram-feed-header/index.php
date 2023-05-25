<?php
// Move this file to "blocks-config" folder with name "instagram-feed-header.php".

/**
 * Server-side rendering of the `premium/instagram-feed-header` block.
 *
 * @package WordPress
 */

function get_premium_instagram_feed_header_css( $attributes, $unique_id ) {
	$css = new Premium_Blocks_css();

	// Desktop Styles.
	if ( isset( $attributes['border'] ) ) {
		$border        = $attributes['border'];
		$border_width  = $attributes['border']['borderWidth'];
		$border_radius = $attributes['border']['borderRadius'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Desktop'], 'px' ) );
	}

	if ( isset( $attributes['padding'] ) ) {
		$padding = $attributes['padding'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Desktop'], $padding['unit'] ) );
	}

	if ( isset( $attributes['margin'] ) ) {
		$margin = $attributes['margin'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $margin['Desktop'], $margin['unit'] ) );
	}

	$css->start_media_query( 'tablet' );
	// Tablet Styles.
	if ( isset( $attributes['border'] ) ) {
		$border        = $attributes['border'];
		$border_width  = $attributes['border']['borderWidth'];
		$border_radius = $attributes['border']['borderRadius'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Tablet'], 'px' ) );
	}

	if ( isset( $attributes['padding'] ) ) {
		$padding = $attributes['padding'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Tablet'], $padding['unit'] ) );
	}

	if ( isset( $attributes['margin'] ) ) {
		$margin = $attributes['margin'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $margin['Tablet'], $margin['unit'] ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );
	// Mobile Styles.
	if ( isset( $attributes['border'] ) ) {
		$border        = $attributes['border'];
		$border_width  = $attributes['border']['borderWidth'];
		$border_radius = $attributes['border']['borderRadius'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Mobile'], 'px' ) );
	}

	if ( isset( $attributes['padding'] ) ) {
		$padding = $attributes['padding'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'padding', $css->render_spacing( $padding['Mobile'], $padding['unit'] ) );
	}

	if ( isset( $attributes['margin'] ) ) {
		$margin = $attributes['margin'];

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'margin', $css->render_spacing( $margin['Mobile'], $margin['unit'] ) );
	}

	$css->stop_media_query();

	return $css->css_output();
}

/**
 * Renders the `premium/instagram-feed-header` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_instagram_feed_header( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	$unique_id     = rand( 100, 10000 );
	$id            = 'premium-instagram-feed-header-' . esc_attr( $unique_id );
	$block_id      = ( ! empty( $attributes['blockId'] ) ) ? $attributes['blockId'] : $id;

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $unique_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'column', $unique_id ) ) {
		$css = get_premium_instagram_feed_header_css( $attributes, $block_id );
		if ( $block_helpers->should_render_inline( 'instagram-feed-header', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
		} else {
			$block_helpers->render_inline_css( $css, 'instagram-feed-header' );
		}
	};

	return $content;
}


/**
 * Register the Instagram Feed Header block.
 *
 * @uses render_block_pbg_instagram_feed_header()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_instagram_feed_header() {
	register_block_type(
		PREMIUM_BLOCKS_PATH . 'blocks-config/instagram-feed-header',
		array(
			'render_callback' => 'render_block_pbg_instagram_feed_header',
		)
	);
}

register_block_pbg_instagram_feed_header();


