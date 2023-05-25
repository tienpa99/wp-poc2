<?php
// Move this file to "blocks-config" folder with name "button.php".

/**
 * Server-side rendering of the `premium/button` block.
 *
 * @package WordPress
 */

function get_premium_button_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// Button Style

	if ( isset( $attr['typography'] ) ) {
		$typography = $attr['typography'];
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> a' );
		$css->render_typography( $typography, 'Desktop' );
	}

	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( '.' . $unique_id . '> .premium-button.wp-block-button__link' );
		$css->add_property( 'padding', $css->render_spacing( $padding['Desktop'], $padding['unit'] ) );
	}

	if ( isset( $attr['margin'] ) ) {
		$margin = $attr['margin'];
		$css->set_selector( '.' . $unique_id . '> .premium-button.wp-block-button__link' );
		$css->add_property( 'margin', $css->render_spacing( $margin['Desktop'], $margin['unit'] ) );
	}

	if ( isset( $attr['border'] ) ) {
		$border        = $attr['border'];
		$border_width  = $border['borderWidth'];
		$border_radius = $border['borderRadius'];

		$css->set_selector( '.' . $unique_id . '> .premium-button' );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Desktop'], 'px' ) );
	}
	if ( isset( $attr['backgroundOptions'] ) ) {
		
		$css->set_selector( '[class*="wp-block-premium"] .' . $unique_id . '> .premium-button' );
		$css->render_background( $attr['backgroundOptions'], 'Desktop' );

	}
	// icon styles
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' );
		$css->add_property( 'font-size', $css->render_range( $attr['iconSize'], 'Desktop' ) );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $attr['iconSize'], 'Desktop' ) );
	}
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' . '> svg' );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Desktop' ) );
	}
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' . '> svg' );
		$css->add_property( 'height', $css->render_range( $attr['iconSize'], 'Desktop' ) );
	}
	if ( isset( $attr['iconSpacing'] ) ) {
		$icon_spacing = $attr['iconSpacing'];
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' );
		$css->add_property( 'margin', $css->render_spacing( $icon_spacing['Desktop'], $icon_spacing['unit'] ) );
	}

	$css->start_media_query( 'tablet' );

	// Button Style
	if ( isset( $attr['typography'] ) ) {
		$typography = $attr['typography'];
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> a' );
		$css->render_typography( $typography, 'Tablet' );
	}

	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( '.' . $unique_id . '> .premium-button.wp-block-button__link' );
		$css->add_property( 'padding', $css->render_spacing( $padding['Tablet'], $padding['unit'] ) );
	}

	if ( isset( $attr['margin'] ) ) {
		$margin = $attr['margin'];
		$css->set_selector( '.' . $unique_id . '> .premium-button.wp-block-button__link' );
		$css->add_property( 'margin', $css->render_spacing( $margin['Tablet'], $margin['unit'] ) );
	}

	if ( isset( $attr['border'] ) ) {
		$border        = $attr['border'];
		$border_width  = $border['borderWidth'];
		$border_radius = $border['borderRadius'];

		$css->set_selector( '.' . $unique_id . '> .premium-button' );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Tablet'], 'px' ) );
	}
	if ( isset( $attr['backgroundOptions'] ) ) {
		$css->set_selector( '[class*="wp-block-premium"] .' . $unique_id . '> .premium-button' );
		$css->render_background( $attr['backgroundOptions'], 'Tablet' );

	}

	// icon styles
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' );
		$css->add_property( 'font-size', $css->render_range( $attr['iconSize'], 'Tablet' ) );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $attr['iconSize'], 'Tablet' ) );
	}
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' . '> svg' );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Tablet' ) );
	}
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' . '> svg' );
		$css->add_property( 'height', $css->render_range( $attr['iconSize'], 'Tablet' ) );
	}
	if ( isset( $attr['iconSpacing'] ) ) {
		$icon_spacing = $attr['iconSpacing'];
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' );
		$css->add_property( 'margin', $css->render_spacing( $icon_spacing['Tablet'], $icon_spacing['unit'] ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );

	// Button Style
	if ( isset( $attr['typography'] ) ) {
		$typography = $attr['typography'];
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> a' );
		$css->render_typography( $typography, 'Mobile' );
	}

	if ( isset( $attr['padding'] ) ) {
		$padding = $attr['padding'];
		$css->set_selector( '.' . $unique_id . '> .premium-button.wp-block-button__link' );
		$css->add_property( 'padding', $css->render_spacing( $padding['Mobile'], $padding['unit'] ) );
	}

	if ( isset( $attr['margin'] ) ) {
		$margin = $attr['margin'];
		$css->set_selector( '.' . $unique_id . '> .premium-button.wp-block-button__link' );
		$css->add_property( 'margin', $css->render_spacing( $margin['Mobile'], $margin['unit'] ) );
	}

	if ( isset( $attr['border'] ) ) {
		$border        = $attr['border'];
		$border_width  = $border['borderWidth'];
		$border_radius = $border['borderRadius'];

		$css->set_selector( '.' . $unique_id . '> .premium-button' );
		$css->add_property( 'border-width', $css->render_spacing( $border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $border_radius['Mobile'], 'px' ) );
	}
	if ( isset( $attr['backgroundOptions'] ) ) {
		$css->set_selector( '[class*="wp-block-premium"] .' . $unique_id . '> .premium-button' );
		$css->render_background( $attr['backgroundOptions'], 'Mobile' );

	}
	// icon styles
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' );
		$css->add_property( 'font-size', $css->render_range( $attr['iconSize'], 'Mobile' ) );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $attr['iconSize'], 'Mobile' ) );
	}
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' . '> svg' );
		$css->add_property( 'width', $css->render_range( $attr['iconSize'], 'Mobile' ) );
	}
	if ( isset( $attr['iconSize'] ) ) {
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' . '> svg' );
		$css->add_property( 'height', $css->render_range( $attr['iconSize'], 'Mobile' ) );
	}
	if ( isset( $attr['iconSpacing'] ) ) {
		$icon_spacing = $attr['iconSpacing'];
		$css->set_selector( '.' . $unique_id . '> .premium-button' . '> .premium-button-icon' );
		$css->add_property( 'margin', $css->render_spacing( $icon_spacing['Mobile'], $icon_spacing['unit'] ) );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/button` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_button( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	$unique_id     = rand( 100, 10000 );
	$id            = 'premium-button-' . esc_attr( $unique_id );
	$block_id      = ( ! empty( $attributes['blockId'] ) ) ? $attributes['blockId'] : $id;

	if ( ! wp_style_is( $unique_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'button', $unique_id ) ) {
		$css = get_premium_button_css_style( $attributes, $block_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'button', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'button' );
			}
		}
	};

	return $content;
}


/**
 * Register the button block.
 *
 * @uses render_block_pbg_button()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_button() {
	register_block_type(
		'premium/button',
		array(
			'render_callback' => 'render_block_pbg_button',
			'editor_style'    => 'premium-blocks-editor-css',
			'editor_script'   => 'pbg-blocks-js',
		)
	);
}

register_block_pbg_button();
