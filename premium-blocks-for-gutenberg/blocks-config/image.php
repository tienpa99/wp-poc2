<?php
// Move this file to "blocks-config" folder with name "image.php".

/**
 * Server-side rendering of the `premium/image` block.
 *
 * @package WordPress
 */

function get_premium_image_css( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// Desktop Styles.
	if ( isset( $attr['imageBorder'] ) ) {
		$image_border        = $attr['imageBorder'];
		$image_border_width  = $image_border['borderWidth'];
		$image_border_radius = $image_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' > .premium-image-container' . ' > .premium-image-wrap' . ' > img' );
		$css->add_property( 'border-radius', $css->render_spacing( $image_border_radius['Desktop'], 'px' ) );
		$css->add_property( 'border-width', $css->render_spacing( $image_border_width['Desktop'], 'px' ) );
	}

	if ( isset( $attr['imgHeight'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-image-container' . ' > .premium-image-wrap' . ' > img' );
		$css->add_property( 'height', $css->render_range( $attr['imgHeight'], 'Desktop' ) );
	}

	if ( isset( $attr['imgWidth'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-image-container' . ' > .premium-image-wrap' . ' > img' );
		$css->add_property( 'width', $css->render_range( $attr['imgWidth'], 'Desktop' ) );
	}

	$css->start_media_query( 'tablet' );
	// // Tablet Styles.
	if ( isset( $attr['imageBorder'] ) ) {
		$image_border        = $attr['imageBorder'];
		$image_border_width  = $image_border['borderWidth'];
		$image_border_radius = $image_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' > .premium-image-container' . ' > .premium-image-wrap' . ' > img' );
		$css->add_property( 'border-radius', $css->render_spacing( $image_border_radius['Tablet'], 'px' ) );
		$css->add_property( 'border-width', $css->render_spacing( $image_border_width['Tablet'], 'px' ) );
	}
	if ( isset( $attr['imgHeight'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-image-container' . ' > .premium-image-wrap' . ' > img' );
		$css->add_property( 'height', $css->render_range( $attr['imgHeight'], 'Tablet' ) );
	}
	if ( isset( $attr['imgWidth'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-image-container' . ' > .premium-image-wrap' . ' > img' );
		$css->add_property( 'width', $css->render_range( $attr['imgWidth'], 'Tablet' ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );
	// // Mobile Styles.
	if ( isset( $attr['imageBorder'] ) ) {
		$image_border        = $attr['imageBorder'];
		$image_border_width  = $image_border['borderWidth'];
		$image_border_radius = $image_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' > .premium-image-container' . ' > .premium-image-wrap' . ' > img' );
		$css->add_property( 'border-radius', $css->render_spacing( $image_border_radius['Mobile'], 'px' ) );
		$css->add_property( 'border-width', $css->render_spacing( $image_border_width['Mobile'], 'px' ) );
	}
	if ( isset( $attr['imgHeight'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-image-container' . ' > .premium-image-wrap' . ' > img' );
		$css->add_property( 'height', $css->render_range( $attr['imgHeight'], 'Mobile' ) );
	}
	if ( isset( $attr['imgWidth'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-image-container' . ' > .premium-image-wrap' . ' > img' );
		$css->add_property( 'width', $css->render_range( $attr['imgWidth'], 'Mobile' ) );
	}

	$css->stop_media_query();

	return $css->css_output();
}

/**
 * Renders the `premium/image` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_image( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	$unique_id     = rand( 100, 10000 );
	$id            = 'premium-image-' . esc_attr( $unique_id );
	$block_id      = ( ! empty( $attributes['blockId'] ) ) ? $attributes['blockId'] : $id;

	if ( ! wp_style_is( $unique_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'image', $unique_id ) ) {
		$css = get_premium_image_css( $attributes, $block_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'image', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'image' );
			}
		}
	};

	return $content;
}


/**
 * Register the Price block.
 *
 * @uses render_block_pbg_image()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_image() {
	register_block_type(
		'premium/image',
		array(
			'render_callback' => 'render_block_pbg_image',
			'editor_style'    => 'premium-blocks-editor-css',
			'editor_script'   => 'pbg-blocks-js',
		)
	);
}

register_block_pbg_image();
