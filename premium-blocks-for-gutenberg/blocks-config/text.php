<?php
// Move this file to "blocks-config" folder with name "text.php".

/**
 * Server-side rendering of the `premium/text` block.
 *
 * @package WordPress
 */

function get_premium_text_css( $attributes, $unique_id ) {
	$css = new Premium_Blocks_css();

	// Desktop Styles.
	if ( isset( $attributes['align'] ) ) {
		$align = $css->get_responsive_css( $attributes['align'], 'Desktop' );

		$css->set_selector( ".{$unique_id} .premium-text-wrap" );
		$css->add_property( 'text-align', $align );
	}

	if ( isset( $attributes['typography'] ) ) {
		$typography = $attributes['typography'];

		$css->set_selector( ".{$unique_id} .premium-text-wrap" );
		$css->render_typography( $typography, 'Desktop' );
	}

	if ( isset( $attributes['border'] ) ) {
		$border        = $attributes['border'];
		$border_width  = $attributes['border']['borderWidth'];
		$border_radius = $attributes['border']['borderRadius'];

		$css->set_selector( ".{$unique_id}" );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Desktop'], 'px' ) );
	}

	if ( isset( $attributes['padding'] ) ) {
		$padding = $attributes['padding'];

		$css->set_selector( ".{$unique_id}" );
		$css->add_property( 'padding', $css->render_spacing( $padding['Desktop'], $padding['unit'] ) );
	}
	if(isset($attributes["background"])){
		$css->set_selector( ".{$unique_id}" );
		$css->render_background( $attributes["background"], 'Desktop' );

	}
	if ( isset( $attributes['margin'] ) ) {
		$margin = $attributes['margin'];

		$css->set_selector( ".{$unique_id}" );
		$css->add_property( 'margin', $css->render_spacing( $margin['Desktop'], $margin['unit'] ) );
	}

	$css->start_media_query( 'tablet' );
	// Tablet Styles.
	if ( isset( $attributes['align'] ) ) {
		$align = $css->get_responsive_css( $attributes['align'], 'Tablet' );
		error_log( wp_json_encode( $align ) );
		$css->set_selector( ".{$unique_id} .premium-text-wrap" );
		$css->add_property( 'text-align', $align );
	}

	if ( isset( $attributes['typography'] ) ) {
		$typography = $attributes['typography'];

		$css->set_selector( ".{$unique_id}" );
		$css->render_typography( $typography, 'Tablet' );
	}

	if ( isset( $attributes['border'] ) ) {
		$border        = $attributes['border'];
		$border_width  = $attributes['border']['borderWidth'];
		$border_radius = $attributes['border']['borderRadius'];

		$css->set_selector( ".{$unique_id} .premium-text-wrap" );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Tablet'], 'px' ) );
	}

	if ( isset( $attributes['padding'] ) ) {
		$padding = $attributes['padding'];

		$css->set_selector( ".{$unique_id}" );
		$css->add_property( 'padding', $css->render_spacing( $padding['Tablet'], $padding['unit'] ) );
	}

	if ( isset( $attributes['margin'] ) ) {
		$margin = $attributes['margin'];

		$css->set_selector( ".{$unique_id}" );
		$css->add_property( 'margin', $css->render_spacing( $margin['Tablet'], $margin['unit'] ) );
	}

	if(isset($attributes["background"])){
		$css->set_selector( ".{$unique_id}" );
		$css->render_background( $attributes["background"], 'Tablet' );

	}


	$css->stop_media_query();
	$css->start_media_query( 'mobile' );
	// Mobile Styles.
	if ( isset( $attributes['align'] ) ) {
		$align = $css->get_responsive_css( $attributes['align'], 'Mobile' );

		$css->set_selector( ".{$unique_id} .premium-text-wrap" );
		$css->add_property( 'text-align', $align );
	}

	if ( isset( $attributes['typography'] ) ) {
		$typography = $attributes['typography'];

		$css->set_selector( ".{$unique_id} .premium-text-wrap" );
		$css->render_typography( $typography, 'Mobile' );
	}

	if ( isset( $attributes['border'] ) ) {
		$border        = $attributes['border'];
		$border_width  = $attributes['border']['borderWidth'];
		$border_radius = $attributes['border']['borderRadius'];

		$css->set_selector( ".{$unique_id}" );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Mobile'], 'px' ) );
	}

	if ( isset( $attributes['padding'] ) ) {
		$padding = $attributes['padding'];

		$css->set_selector( ".{$unique_id}" );
		$css->add_property( 'padding', $css->render_spacing( $padding['Mobile'], $padding['unit'] ) );
	}

	if(isset($attributes["background"])){
		$css->set_selector( ".{$unique_id}" );
		$css->render_background( $attributes["background"], 'Mobile' );

	}


	if ( isset( $attributes['margin'] ) ) {
		$margin = $attributes['margin'];

		$css->set_selector( ".{$unique_id}" );
		$css->add_property( 'margin', $css->render_spacing( $margin['Mobile'], $margin['unit'] ) );
	}

	$css->stop_media_query();

	return $css->css_output();
}

/**
 * Renders the `premium/text` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_text( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	$unique_id     = rand( 100, 10000 );
	$id            = 'premium-text-' . esc_attr( $unique_id );
	$block_id      = ( ! empty( $attributes['blockId'] ) ) ? $attributes['blockId'] : $id;

	if ( ! wp_style_is( $unique_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'text', $unique_id ) ) {
		$css = get_premium_text_css( $attributes, $block_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'text', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'text' );
			}
		}
	};

	return $content;
}


/**
 * Register the Text block.
 *
 * @uses render_block_pbg_text()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_text() {
	register_block_type(
		'premium/text',
		array(
			'render_callback' => 'render_block_pbg_text',
			'editor_style'    => 'premium-blocks-editor-css',
			'editor_script'   => 'pbg-blocks-js',
		)
	);
}

register_block_pbg_text();
