<?php
// Move this file to "blocks-config" folder with name "icon-group.php".

/**
 * Server-side rendering of the `premium/icon group` block.
 *
 * @package WordPress
 */

function get_premium_icon_group_css( $attr, $unique_id ) {
	$block_helpers          = pbg_blocks_helper();
	$css                    = new Premium_Blocks_css();
	$media_query            = array();
	$media_query['mobile']  = apply_filters( 'Premium_BLocks_mobile_media_query', '(max-width: 767px)' );
	$media_query['tablet']  = apply_filters( 'Premium_BLocks_tablet_media_query', '(max-width: 1024px)' );
	$media_query['desktop'] = apply_filters( 'Premium_BLocks_tablet_media_query', '(min-width: 1025px)' );

	// icon Size
	if ( isset( $attr['iconsSize']['Desktop'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-icon__container .premium-icon' );
		$css->add_property( 'font-size', $css->render_range( $attr['iconsSize'], 'Desktop' ) );
	}
	if ( isset( $attr['iconsSize']['Desktop'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-icon__container .premium-icon svg' );
		$css->add_property( 'width', $css->render_range( $attr['iconsSize'], 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $attr['iconsSize'], 'Desktop' ) );
	}
	// Desktop Styles.
	if ( isset( $attr['groupIconBorder'] ) ) {
		$icon_border        = $attr['groupIconBorder'];
		$icon_border_width  = $icon_border['borderWidth'];
		$icon_border_radius = $icon_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' .premium-icon-content .premium-icon' );
		$css->add_property( 'border-radius', $css->render_spacing( $icon_border_radius['Desktop'], 'px' ) );
		$css->add_property( 'border-width', $css->render_spacing( $icon_border_width['Desktop'], 'px' ) );
	}

	if ( isset( $attr['groupIconMargin'] ) ) {
		$margin = $attr['groupIconMargin'];
		$css->set_selector( '.' . $unique_id . ' .premium-icon-content .premium-icon' );
		$css->add_property( 'margin', $css->render_spacing( $margin['Desktop'], $margin['unit'] ) );
	}

	if ( isset( $attr['groupIconPadding'] ) ) {
		$padding = $attr['groupIconPadding'];
		$css->set_selector( '.' . $unique_id . ' .premium-icon-content .premium-icon' );
		$css->add_property( 'padding', $css->render_spacing( $padding['Desktop'], $padding['unit'] ) );
	}

	if ( isset( $attr['align'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-icon-group-horizontal' );
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['align'], 'Desktop' ) );
	}

	if ( isset( $attr['align'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['align'], 'Desktop' );
		$content_flex_align = 'left' === $content_align ? 'flex-start' : 'center';
		$content_flex_align = 'right' === $content_align ? 'flex-end' : $content_flex_align;

		$css->set_selector( '.' . $unique_id . ' > .premium-icon-group-vertical' );
		$css->add_property( 'align-items', $content_flex_align );
		$css->add_property( 'justify-content', $content_flex_align );
	}

	$css->start_media_query( 'tablet' );
	// // Tablet Styles.
	// icon Size
	if ( isset( $attr['iconsSize']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-icon-group-container .premium-icon__container .premium-icon' );
		$css->add_property( 'font-size', $css->render_range( $attr['iconsSize'], 'Tablet' ) );
	}
	if ( isset( $attr['iconsSize']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-icon-group-container .premium-icon__container .premium-icon svg' );
		$css->add_property( 'width', $css->render_range( $attr['iconsSize'], 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $attr['iconsSize'], 'Tablet' ) );
	}
	if ( isset( $attr['groupIconBorder'] ) ) {
		$icon_border        = $attr['groupIconBorder'];
		$icon_border_width  = $icon_border['borderWidth'];
		$icon_border_radius = $icon_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' .premium-icon-content .premium-icon' );
		$css->add_property( 'border-radius', $css->render_spacing( $icon_border_radius['Tablet'], 'px' ) );
		$css->add_property( 'border-width', $css->render_spacing( $icon_border_width['Tablet'], 'px' ) );
	}

	if ( isset( $attr['groupIconMargin'] ) ) {
		$margin = $attr['groupIconMargin'];
		$css->set_selector( '.' . $unique_id . ' .premium-icon-content .premium-icon' );
		$css->add_property( 'margin', $css->render_spacing( $margin['Tablet'], $margin['unit'] ) );
	}

	if ( isset( $attr['groupIconPadding'] ) ) {
		$padding = $attr['groupIconPadding'];
		$css->set_selector( '.' . $unique_id . ' .premium-icon-content .premium-icon' );
		$css->add_property( 'padding', $css->render_spacing( $padding['Tablet'], $padding['unit'] ) );
	}

	if ( isset( $attr['align'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-icon-group-horizontal' );
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['align'], 'Tablet' ) );
	}

	if ( isset( $attr['align'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['align'], 'Tablet' );
		$content_flex_align = 'left' === $content_align ? 'flex-start' : 'center';
		$content_flex_align = 'right' === $content_align ? 'flex-end' : $content_flex_align;

		$css->set_selector( '.' . $unique_id . ' > .premium-icon-group-vertical' );
		$css->add_property( 'align-items', $content_flex_align );
		$css->add_property( 'justify-content', $content_flex_align );
	}

	$css->stop_media_query();
	$css->start_media_query( 'mobile' );
	// // Mobile Styles.
	// icon Size
	if ( isset( $attr['iconsSize']['Mobile'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-icon-group-container .premium-icon__container .premium-icon' );
		$css->add_property( 'font-size', $css->render_range( $attr['iconsSize'], 'Mobile' ) );
	}

	if ( isset( $attr['iconsSize']['Mobile'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-icon-group-container .premium-icon__container .premium-icon svg' );
		$css->add_property( 'width', $css->render_range( $attr['iconsSize'], 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $attr['iconsSize'], 'Mobile' ) );
	}
	if ( isset( $attr['groupIconBorder'] ) ) {
		$icon_border        = $attr['groupIconBorder'];
		$icon_border_width  = $icon_border['borderWidth'];
		$icon_border_radius = $icon_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' .premium-icon-content .premium-icon' );
		$css->add_property( 'border-radius', $css->render_spacing( $icon_border_radius['Mobile'], 'px' ) );
		$css->add_property( 'border-width', $css->render_spacing( $icon_border_width['Mobile'], 'px' ) );
	}

	if ( isset( $attr['groupIconMargin'] ) ) {
		$margin = $attr['groupIconMargin'];
		$css->set_selector( '.' . $unique_id . ' .premium-icon-content .premium-icon' );
		$css->add_property( 'margin', $css->render_spacing( $margin['Mobile'], $margin['unit'] ) );
	}

	if ( isset( $attr['groupIconPadding'] ) ) {
		$padding = $attr['groupIconPadding'];
		$css->set_selector( '.' . $unique_id . ' .premium-icon-content .premium-icon' );
		$css->add_property( 'padding', $css->render_spacing( $padding['Mobile'], $padding['unit'] ) );
	}

	if ( isset( $attr['align'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-icon-group-horizontal' );
		$css->add_property( 'justify-content', $css->get_responsive_css( $attr['align'], 'Mobile' ) );
	}

	if ( isset( $attr['align'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['align'], 'Mobile' );
		$content_flex_align = 'left' === $content_align ? 'flex-start' : 'center';
		$content_flex_align = 'right' === $content_align ? 'flex-end' : $content_flex_align;

		$css->set_selector( '.' . $unique_id . ' > .premium-icon-group-vertical' );
		$css->add_property( 'align-items', $content_flex_align );
		$css->add_property( 'justify-content', $content_flex_align );
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
function render_block_pbg_icon_group( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	$unique_id     = rand( 100, 10000 );
	$id            = 'premium-icon-group-' . esc_attr( $unique_id );
	$block_id      = ( ! empty( $attributes['blockId'] ) ) ? $attributes['blockId'] : $id;

	if ( ! wp_style_is( $unique_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'icon-group', $unique_id ) ) {
		$css = get_premium_icon_group_css( $attributes, $block_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'icon-group', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'icon-group' );
			}
		}
	};

	return $content;
}


/**
 * Register the icon group block.
 *
 * @uses render_block_pbg_icon_group()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_icon_group() {
	register_block_type(
		'premium/icon-group',
		array(
			'render_callback' => 'render_block_pbg_icon_group',
			'editor_style'    => 'premium-blocks-editor-css',
			'editor_script'   => 'pbg-blocks-js',
		)
	);
}

register_block_pbg_icon_group();
