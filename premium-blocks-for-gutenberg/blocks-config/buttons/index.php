<?php
/**
 * Server-side rendering of the `pbg/button-group` block.
 *
 * @package WordPress
 */

/**
 * Get Button Group Block CSS
 *
 * Return Frontend CSS for Button Group.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_button_group_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	if ( isset( $attr['align'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['align'], 'Desktop' );
		$content_flex_align = 'left' === $content_align ? 'flex-start' : 'center';
		$content_flex_align = 'right' === $content_align ? 'flex-end' : $content_flex_align;

		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $content_align );

		$css->set_selector( $unique_id . ' > .premium-button-group_wrap.premium-button-group-class-css' );
		$css->add_property( 'justify-content', $content_align );
		$css->add_property( 'align-items', $content_flex_align );
	}
	if ( isset( $attr['groupAlign'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['groupAlign'], 'Desktop' );
		$content_flex_align = 'horizontal' === $content_align ? 'row' : 'column';

		$css->set_selector( $unique_id . ' > .premium-button-group_wrap' );
		$css->add_property( 'flex-direction', $content_flex_align );
	}
	if ( isset( $attr['buttonGap'] ) ) {
		$css->set_selector( $unique_id . ' > .premium-button-group_wrap' );
		$css->add_property( 'column-gap', $css->get_responsive_css( $attr['buttonGap'], 'Desktop' ) . 'px !important' );
		$css->add_property( 'row-gap', $css->get_responsive_css( $attr['buttonGap'], 'Desktop' ) . 'px !important' );
	}

	if ( isset( $attr['typography'] ) ) {
		$typography = $attr['typography'];
		$css->set_selector( $unique_id . ' .premium-button-text-edit' );
		$css->render_typography( $typography, 'Desktop' );
	}

	if ( isset( $attr['groupPadding'] ) ) {
		$groupPadding = $attr['groupPadding'];
		$css->set_selector( $unique_id . ' .premium-button' );
		$css->add_property( 'padding', $css->render_spacing( $groupPadding['Desktop'], $groupPadding['unit'] ) );
	}

	if ( isset( $attr['groupMargin'] ) ) {
		$groupMargin = $attr['groupMargin'];
		$css->set_selector( $unique_id . ' .premium-button' );
		$css->add_property( 'margin', $css->render_spacing( $groupMargin['Desktop'], $groupMargin['unit'] ) );
	}

	$css->start_media_query( 'tablet' );

	if ( isset( $attr['align'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['align'], 'Tablet' );
		$content_flex_align = 'left' === $content_align ? 'flex-start' : 'center';
		$content_flex_align = 'right' === $content_align ? 'flex-end' : $content_flex_align;

		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $content_align );

		$css->set_selector( $unique_id . ' > .premium-button-group_wrap.premium-button-group-class-css' );
		$css->add_property( 'justify-content', $content_align );
		$css->add_property( 'align-items', $content_flex_align );
	}
	if ( isset( $attr['groupAlign'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['groupAlign'], 'Tablet' );
		$content_flex_align = 'horizontal' === $content_align ? 'row' : 'column';

		$css->set_selector( $unique_id . ' > .premium-button-group_wrap' );
		$css->add_property( 'flex-direction', $content_flex_align );
	}
	if ( isset( $attr['buttonGap'] ) ) {
		$css->set_selector( $unique_id . ' > .premium-button-group_wrap' );
		$css->add_property( 'column-gap', $css->get_responsive_css( $attr['buttonGap'], 'Tablet' ) . 'px !important' );
		$css->add_property( 'row-gap', $css->get_responsive_css( $attr['buttonGap'], 'Tablet' ) . 'px !important' );
	}

	if ( isset( $attr['typography'] ) ) {
		$typography = $attr['typography'];
		$css->set_selector( $unique_id . ' .premium-button-text-edit' );
		$css->render_typography( $typography, 'Tablet' );
	}

	if ( isset( $attr['groupPadding'] ) ) {
		$groupPadding = $attr['groupPadding'];
		$css->set_selector( $unique_id . ' .premium-button' );
		$css->add_property( 'padding', $css->render_spacing( $groupPadding['Tablet'], $groupPadding['unit'] ) );
	}

	if ( isset( $attr['groupMargin'] ) ) {
		$groupMargin = $attr['groupMargin'];
		$css->set_selector( $unique_id . ' .premium-button' );
		$css->add_property( 'margin', $css->render_spacing( $groupMargin['Tablet'], $groupMargin['unit'] ) );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );

	if ( isset( $attr['align'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['align'], 'Mobile' );
		$content_flex_align = 'left' === $content_align ? 'flex-start' : 'center';
		$content_flex_align = 'right' === $content_align ? 'flex-end' : $content_flex_align;

		$css->set_selector( $unique_id );
		$css->add_property( 'text-align', $content_align );

		$css->set_selector( $unique_id . ' > .premium-button-group_wrap.premium-button-group-class-css' );
		$css->add_property( 'justify-content', $content_align );
		$css->add_property( 'align-items', $content_flex_align );
	}
	if ( isset( $attr['groupAlign'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['groupAlign'], 'Mobile' );
		$content_flex_align = 'horizontal' === $content_align ? 'row' : 'column';

		$css->set_selector( $unique_id . ' > .premium-button-group_wrap' );
		$css->add_property( 'flex-direction', $content_flex_align );
	}
	if ( isset( $attr['buttonGap'] ) ) {
		$css->set_selector( $unique_id . ' > .premium-button-group_wrap' );
		$css->add_property( 'column-gap', $css->get_responsive_css( $attr['buttonGap'], 'Mobile' ) . 'px !important' );
		$css->add_property( 'row-gap', $css->get_responsive_css( $attr['buttonGap'], 'Mobile' ) . 'px !important' );
	}

	if ( isset( $attr['typography'] ) ) {
		$typography = $attr['typography'];
		$css->set_selector( $unique_id . ' .premium-button-text-edit' );
		$css->render_typography( $typography, 'Mobile' );
	}

	if ( isset( $attr['groupPadding'] ) ) {
		$groupPadding = $attr['groupPadding'];
		$css->set_selector( $unique_id . ' .premium-button' );
		$css->add_property( 'padding', $css->render_spacing( $groupPadding['Mobile'], $groupPadding['unit'] ) );
	}

	if ( isset( $attr['groupMargin'] ) ) {
		$groupMargin = $attr['groupMargin'];
		$css->set_selector( $unique_id . ' .premium-button' );
		$css->add_property( 'margin', $css->render_spacing( $groupMargin['Mobile'], $groupMargin['unit'] ) );
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
function render_block_pbg_button_group( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();

	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = ".{$attributes['blockId']}";
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'buttons', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_button_group_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'buttons', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'buttons' );
			}
		}
	};

	return $content;
}




/**
 * Register the button block.
 *
 * @uses render_block_pbg_button_group()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_button_group() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/buttons',
		array(
			'render_callback' => 'render_block_pbg_button_group',
		)
	);
}

register_block_pbg_button_group();
