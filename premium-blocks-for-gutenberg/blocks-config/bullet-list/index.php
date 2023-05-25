<?php
/**
 * Server-side rendering of the `pbg/bullet-list` block.
 *
 * @package WordPress
 */

/**
 * Get Bullet List Block CSS
 *
 * Return Frontend CSS for Bullet List.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_bullet_list_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// Align.
	if ( isset( $attr['bulletAlign'] ) ) {
		$align      = $css->get_responsive_css( $attr['bulletAlign'], 'Desktop' );
		$flex_align = 'left' === $align ? 'flex-start' : 'center';
		$flex_align = 'right' === $align ? 'flex-end' : $flex_align;

		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list__icon-wrap' );
		$css->add_property( 'align-self', $flex_align );
		$css->add_property( 'text-align', $align );
		$css->add_property( 'justify-content', $flex_align );
		$css->add_property( 'align-items', $flex_align );
	}

	if ( isset( $attr['align'] ) ) {
		$icon_position      = $attr['iconPosition'];
		$content_align      = $css->get_responsive_css( $attr['align'], 'Desktop' );
		$content_flex_align = 'left' === $content_align ? 'flex-start' : 'center';
		$content_flex_align = 'right' === $content_align ? 'flex-end' : $content_flex_align;

		$content_flex_direction = 'right' === $content_align ? 'column' : 'column';
		$content_flex_position  = 'after' === $icon_position ? 'row-reverse' : '';
		$content_flex_direction = 'top' === $icon_position ? $content_flex_direction : $content_flex_position;

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $content_align );
		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list' );
		$css->add_property( 'justify-content', $content_flex_align );
		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list__wrapper' );
		$css->add_property( 'justify-content', $content_flex_align );
		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list__content-wrap' );
		$css->add_property( 'justify-content', $content_flex_align );
		$css->add_property( 'flex-direction', $content_flex_direction );
	}

	// Style for list.
	if ( isset( $attr['generalpadding'] ) ) {
		$general_padding = $attr['generalpadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' );
		$css->add_property( 'padding', $css->render_spacing( $general_padding['Desktop'], $general_padding['unit'] ) );
	}

	if ( isset( $attr['generalmargin'] ) ) {
		$general_margin = $attr['generalmargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' );
		$css->add_property( 'margin', $css->render_spacing( $general_margin['Desktop'], $general_margin['unit'] ) );
	}

	if ( isset( $attr['generalBorder'] ) ) {
		$general_border        = $attr['generalBorder'];
		$general_border_width  = $general_border['borderWidth'];
		$general_border_radius = $general_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' );
		$css->add_property( 'border-width', $css->render_spacing( $general_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $general_border_radius['Desktop'], 'px' ) );
	}

	// Style for icon.
	if ( isset( $attr['bulletIconFontSize'] ) ) {
		$bullet_icon_size = $attr['bulletIconFontSize'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' );
		$css->add_property( 'font-size', $css->render_range( $bullet_icon_size, 'Desktop' ) );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' );
		$css->add_property( 'font-size', $css->render_range( $bullet_icon_size, 'Desktop' ) );
	}

	if ( isset( $attr['bulletIconFontSize'] ) ) {
		$bullet_icon_size = $attr['bulletIconFontSize'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' . ' > svg' );
		$css->add_property( 'width', $css->render_range( $bullet_icon_size, 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $bullet_icon_size, 'Desktop' ) );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' . ' > svg' );
		$css->add_property( 'height', $css->render_range( $bullet_icon_size, 'Desktop' ) );
		$css->add_property( 'width', $css->render_range( $bullet_icon_size, 'Desktop' ) );
	}

	if ( isset( $attr['bulletIconFontSize'] ) ) {
		$bullet_image_size = $attr['bulletIconFontSize'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'width', $css->render_range( $bullet_image_size, 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $bullet_image_size, 'Desktop' ) );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'width', $css->render_range( $bullet_image_size, 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $bullet_image_size, 'Desktop' ) );
	}

	if ( isset( $attr['bulletIconpadding'] ) ) {
		$bullet_icon_padding = $attr['bulletIconpadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'padding', $css->render_spacing( $bullet_icon_padding['Desktop'], $bullet_icon_padding['unit'] ) );
	}

	if ( isset( $attr['bulletAlign'] ) ) {
		$align      = $css->get_responsive_css( $attr['bulletAlign'], 'Desktop' );
		$flex_align = 'flex-start' === $align ? 'top' : 'middle';
		$flex_align = $align === 'flex-end' ? 'bottom' : $flex_align;

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'vertical-align', $flex_align );
	}

	if ( isset( $attr['bulletIconBorder'] ) ) {
		$bullet_icon_border        = $attr['bulletIconBorder'];
		$bullet_icon_border_width  = $bullet_icon_border['borderWidth'];
		$bullet_icon_border_radius = $bullet_icon_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'border-width', $css->render_spacing( $bullet_icon_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $bullet_icon_border_radius['Desktop'], 'px' ) );
	}

	// Bullet Icon Style
	if ( isset( $attr['bulletIconmargin'] ) ) {
		$bullet_icon_margin = $attr['bulletIconmargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' );
		$css->add_property( 'margin', $css->render_spacing( $bullet_icon_margin['Desktop'], $bullet_icon_margin['unit'] ) );
	}

	// Style for title.
	if ( isset( $attr['titleTypography'] ) ) {
		$title_typography = $attr['titleTypography'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__label-wrap' );
		$css->render_typography( $title_typography, 'Desktop' );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__label-wrap' );
		$css->render_typography( $title_typography, 'Desktop' );
	}

	if ( isset( $attr['titlemargin'] ) ) {
		$title_margin = $attr['titlemargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' );
		$css->add_property( 'margin', $css->render_spacing( $title_margin['Desktop'], $title_margin['unit'] ) );
	}

	// style for divider
	if ( isset( $attr['dividerWidth'] ) ) {
		$divider_width = $attr['dividerWidth'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-block:not(:last-child):after' );
		$css->add_property( 'width', $css->render_range( $divider_width, 'Desktop' ) );
	}

	if ( isset( $attr['dividerHeight'] ) ) {
		$divider_height = $attr['dividerHeight'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-block:not(:last-child):after' );
		$css->add_property( 'border-top-width', $css->render_range( $divider_height, 'Desktop' ) );
	}

	if ( isset( $attr['dividerWidth'] ) ) {
		$divider_width = $attr['dividerWidth'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-inline:not(:last-child):after' );
		$css->add_property( 'border-left-width', $css->render_range( $divider_width, 'Desktop' ) );
	}

	if ( isset( $attr['dividerHeight'] ) ) {
		$divider_height = $attr['dividerHeight'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-inline:not(:last-child):after' );
		$css->add_property( 'height', $css->render_range( $divider_height, 'Desktop' ) );
	}

	$css->start_media_query( 'tablet' );

	// Align.
	if ( isset( $attr['bulletAlign'] ) ) {
		$align      = $css->get_responsive_css( $attr['bulletAlign'], 'Tablet' );
		$flex_align = 'left' === $align ? 'flex-start' : 'center';
		$flex_align = 'right' === $align ? 'flex-end' : $flex_align;

		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list__icon-wrap' );
		$css->add_property( 'align-self', $flex_align );
		$css->add_property( 'text-align', $align );
		$css->add_property( 'justify-content', $flex_align );
		$css->add_property( 'align-items', $flex_align );
	}

	if ( isset( $attr['align'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['align'], 'Tablet' );
		$content_flex_align = 'left' === $content_align ? 'flex-start' : 'center';
		$content_flex_align = 'right' === $content_align ? 'flex-end' : $content_flex_align;

		$content_flex_direction = 'right' === $content_align ? 'column' : 'column';
		$content_flex_position  = 'after' === $icon_position ? 'row-reverse' : '';
		$content_flex_direction = 'top' === $icon_position ? $content_flex_direction : $content_flex_position;

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $content_align );
		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list' );
		$css->add_property( 'justify-content', $content_flex_align );
		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list__wrapper' );
		$css->add_property( 'justify-content', $content_flex_align );
		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list__content-wrap' );
		$css->add_property( 'justify-content', $content_flex_align );
		$css->add_property( 'flex-direction', $content_flex_direction );
	}
	// Style for image.
	if ( isset( $attr['generalpadding'] ) ) {
		$general_padding = $attr['generalpadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' );
		$css->add_property( 'padding', $css->render_spacing( $general_padding['Tablet'], $general_padding['unit'] ) );
	}

	if ( isset( $attr['generalmargin'] ) ) {
		$general_margin = $attr['generalmargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' );
		$css->add_property( 'margin', $css->render_spacing( $general_margin['Tablet'], $general_margin['unit'] ) );
	}

	if ( isset( $attr['generalBorder'] ) ) {
		$general_border        = $attr['generalBorder'];
		$general_border_width  = $general_border['borderWidth'];
		$general_border_radius = $general_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' );
		$css->add_property( 'border-width', $css->render_spacing( $general_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $general_border_radius['Tablet'], 'px' ) );
	}
	// style for link

	if ( isset( $attr['bulletIconFontSize'] ) ) {
		$bullet_icon_size = $attr['bulletIconFontSize'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' );
		$css->add_property( 'font-size', $css->render_range( $bullet_icon_size, 'Tablet' ) );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' );
		$css->add_property( 'font-size', $css->render_range( $bullet_icon_size, 'Tablet' ) );
	}

	if ( isset( $attr['bulletIconFontSize'] ) ) {
		$bullet_icon_size = $attr['bulletIconFontSize'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' . ' > svg' );
		$css->add_property( 'width', $css->render_range( $bullet_icon_size, 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $bullet_icon_size, 'Tablet' ) );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' . ' > svg' );
		$css->add_property( 'height', $css->render_range( $bullet_icon_size, 'Tablet' ) );
		$css->add_property( 'width', $css->render_range( $bullet_icon_size, 'Tablet' ) );
	}

	if ( isset( $attr['bulletIconFontSize'] ) ) {
		$bullet_image_size = $attr['bulletIconFontSize'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'width', $css->render_range( $bullet_image_size, 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $bullet_image_size, 'Tablet' ) );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'width', $css->render_range( $bullet_image_size, 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $bullet_image_size, 'Tablet' ) );
	}

	// Style for image.
	if ( isset( $attr['bulletIconpadding'] ) ) {
		$bullet_icon_padding = $attr['bulletIconpadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'padding', $css->render_spacing( $bullet_icon_padding['Tablet'], $bullet_icon_padding['unit'] ) );
	}

	if ( isset( $attr['bulletAlign'] ) ) {
		$align      = $css->get_responsive_css( $attr['bulletAlign'], 'Tablet' );
		$flex_align = 'flex-start' === $align ? 'top' : 'middle';
		$flex_align = $align === 'flex-end' ? 'bottom' : $flex_align;

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'vertical-align', $flex_align );
	}

	if ( isset( $attr['bulletIconBorder'] ) ) {
		$bullet_icon_border        = $attr['bulletIconBorder'];
		$bullet_icon_border_width  = $bullet_icon_border['borderWidth'];
		$bullet_icon_border_radius = $bullet_icon_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'border-width', $css->render_spacing( $bullet_icon_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $bullet_icon_border_radius['Tablet'], 'px' ) );
	}

	// Bullet Icon Style
	if ( isset( $attr['bulletIconmargin'] ) ) {
		$bullet_icon_margin = $attr['bulletIconmargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' );
		$css->add_property( 'margin', $css->render_spacing( $bullet_icon_margin['Tablet'], $bullet_icon_margin['unit'] ) );
	}

	// Style for title.
	if ( isset( $attr['titleTypography'] ) ) {
		$title_typography = $attr['titleTypography'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__label-wrap' );
		$css->render_typography( $title_typography, 'Tablet' );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__label-wrap' );
		$css->render_typography( $title_typography, 'Tablet' );
	}

	if ( isset( $attr['titlemargin'] ) ) {
		$title_margin = $attr['titlemargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' );
		$css->add_property( 'margin', $css->render_spacing( $title_margin['Tablet'], $title_margin['unit'] ) );
	}

	// style for divider
	if ( isset( $attr['dividerWidth'] ) ) {
		$divider_width = $attr['dividerWidth'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-block:not(:last-child):after' );
		$css->add_property( 'width', $css->render_range( $divider_width, 'Tablet' ) );
	}

	if ( isset( $attr['dividerHeight'] ) ) {
		$divider_height = $attr['dividerHeight'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-block:not(:last-child):after' );
		$css->add_property( 'border-top-width', $css->render_range( $divider_height, 'Tablet' ) );
	}

	if ( isset( $attr['dividerWidth'] ) ) {
		$divider_width = $attr['dividerWidth'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-inline:not(:last-child):after' );
		$css->add_property( 'border-left-width', $css->render_range( $divider_width, 'Tablet' ) );
	}

	if ( isset( $attr['dividerHeight'] ) ) {
		$divider_height = $attr['dividerHeight'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-inline:not(:last-child):after' );
		$css->add_property( 'height', $css->render_range( $divider_height, 'Tablet' ) );
	}

	$css->stop_media_query();

	$css->start_media_query( 'mobile' );

	// Align.
	if ( isset( $attr['bulletAlign'] ) ) {
		$align      = $css->get_responsive_css( $attr['bulletAlign'], 'Mobile' );
		$flex_align = 'left' === $align ? 'flex-start' : 'center';
		$flex_align = 'right' === $align ? 'flex-end' : $flex_align;

		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list__icon-wrap' );
		$css->add_property( 'align-self', $flex_align );
		$css->add_property( 'text-align', $align );
		$css->add_property( 'justify-content', $flex_align );
		$css->add_property( 'align-items', $flex_align );
	}

	if ( isset( $attr['align'] ) ) {
		$content_align      = $css->get_responsive_css( $attr['align'], 'Mobile' );
		$content_flex_align = 'left' === $content_align ? 'flex-start' : 'center';
		$content_flex_align = 'right' === $content_align ? 'flex-end' : $content_flex_align;

		$content_flex_direction = 'right' === $content_align ? 'column' : 'column';
		$content_flex_position  = 'after' === $icon_position ? 'row-reverse' : '';
		$content_flex_direction = 'top' === $icon_position ? $content_flex_direction : $content_flex_position;

		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $content_align );
		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list' );
		$css->add_property( 'justify-content', $content_flex_align );
		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list__wrapper' );
		$css->add_property( 'justify-content', $content_flex_align );
		$css->set_selector( '.' . $unique_id . ' .premium-bullet-list__content-wrap' );
		$css->add_property( 'justify-content', $content_flex_align );
		$css->add_property( 'flex-direction', $content_flex_direction );
	}

	// Style for general setting.
	if ( isset( $attr['generalpadding'] ) ) {
		$general_padding = $attr['generalpadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' );
		$css->add_property( 'padding', $css->render_spacing( $general_padding['Mobile'], $general_padding['unit'] ) );
	}

	if ( isset( $attr['generalmargin'] ) ) {
		$general_margin = $attr['generalmargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' );
		$css->add_property( 'margin', $css->render_spacing( $general_margin['Mobile'], $general_margin['unit'] ) );
	}

	if ( isset( $attr['generalBorder'] ) ) {
		$general_border        = $attr['generalBorder'];
		$general_border_width  = $general_border['borderWidth'];
		$general_border_radius = $general_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' );
		$css->add_property( 'border-width', $css->render_spacing( $general_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $general_border_radius['Mobile'], 'px' ) );
	}

	// style for link
	// Style for icon.
	if ( isset( $attr['bulletIconFontSize'] ) ) {
		$bullet_icon_size = $attr['bulletIconFontSize'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' );
		$css->add_property( 'font-size', $css->render_range( $bullet_icon_size, 'Mobile' ) );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' );
		$css->add_property( 'font-size', $css->render_range( $bullet_icon_size, 'Mobile' ) );
	}

	if ( isset( $attr['bulletIconFontSize'] ) ) {
		$bullet_icon_size = $attr['bulletIconFontSize'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' . ' > svg' );
		$css->add_property( 'width', $css->render_range( $bullet_icon_size, 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $bullet_icon_size, 'Mobile' ) );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon' . ' > svg' );
		$css->add_property( 'height', $css->render_range( $bullet_icon_size, 'Mobile' ) );
		$css->add_property( 'width', $css->render_range( $bullet_icon_size, 'Mobile' ) );
	}

	if ( isset( $attr['bulletIconFontSize'] ) ) {
		$bullet_image_size = $attr['bulletIconFontSize'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'width', $css->render_range( $bullet_image_size, 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $bullet_image_size, 'Mobile' ) );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'width', $css->render_range( $bullet_image_size, 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $bullet_image_size, 'Mobile' ) );
	}

	// Style for image.
	if ( isset( $attr['bulletIconpadding'] ) ) {
		$bullet_icon_padding = $attr['bulletIconpadding'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'padding', $css->render_spacing( $bullet_icon_padding['Mobile'], $bullet_icon_padding['unit'] ) );
	}

	if ( isset( $attr['bulletAlign'] ) ) {
		$align      = $css->get_responsive_css( $attr['bulletAlign'], 'Mobile' );
		$flex_align = 'flex-start' === $align ? 'top' : 'middle';
		$flex_align = $align === 'flex-end' ? 'bottom' : $flex_align;

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'vertical-align', $flex_align );
	}

	if ( isset( $attr['bulletIconBorder'] ) ) {
		$bullet_icon_border        = $attr['bulletIconBorder'];
		$bullet_icon_border_width  = $bullet_icon_border['borderWidth'];
		$bullet_icon_border_radius = $bullet_icon_border['borderRadius'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > .premium-bullet-list__content-icon' . ' > .premium-bullet-list-icon, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' . ' > img' );
		$css->add_property( 'border-width', $css->render_spacing( $bullet_icon_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $bullet_icon_border_radius['Mobile'], 'px' ) );
	}

	// Bullet Icon Style
	if ( isset( $attr['bulletIconmargin'] ) ) {
		$bullet_icon_margin = $attr['bulletIconmargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__icon-wrap' );
		$css->add_property( 'margin', $css->render_spacing( $bullet_icon_margin['Mobile'], $bullet_icon_margin['unit'] ) );
	}
	// Style for title.
	if ( isset( $attr['titleTypography'] ) ) {
		$title_typography = $attr['titleTypography'];

		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__label-wrap' );
		$css->render_typography( $title_typography, 'Mobile' );
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' . ' > .premium-bullet-list__label-wrap' );
		$css->render_typography( $title_typography, 'Mobile' );
	}

	if ( isset( $attr['titlemargin'] ) ) {
		$title_margin = $attr['titlemargin'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > .premium-bullet-list__content-wrap, ' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list__wrapper' . ' > a' . ' > .premium-bullet-list__content-wrap' );
		$css->add_property( 'margin', $css->render_spacing( $title_margin['Mobile'], $title_margin['unit'] ) );
	}

	// style for divider
	if ( isset( $attr['dividerWidth'] ) ) {
		$divider_width = $attr['dividerWidth'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-block:not(:last-child):after' );
		$css->add_property( 'width', $css->render_range( $divider_width, 'Mobile' ) );
	}

	if ( isset( $attr['dividerHeight'] ) ) {
		$divider_height = $attr['dividerHeight'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-block:not(:last-child):after' );
		$css->add_property( 'border-top-width', $css->render_range( $divider_height, 'Mobile' ) );
	}

	if ( isset( $attr['dividerWidth'] ) ) {
		$divider_width = $attr['dividerWidth'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-inline:not(:last-child):after' );
		$css->add_property( 'border-left-width', $css->render_range( $divider_width, 'Mobile' ) );
	}

	if ( isset( $attr['dividerHeight'] ) ) {
		$divider_height = $attr['dividerHeight'];
		$css->set_selector( '.' . $unique_id . ' > .premium-bullet-list' . '> .premium-bullet-list-divider-inline:not(:last-child):after' );
		$css->add_property( 'height', $css->render_range( $divider_height, 'Mobile' ) );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/count-up` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_bullet_list( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = $attributes['blockId'];
	} else {
		$unique_id = rand( 100, 10000 );
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'bullet-list', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_premium_bullet_list_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'bullet-list', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'bullet-list' );
			}
		}
	};

	return $content;
}




/**
 * Register the bullet_list block.
 *
 * @uses render_block_pbg_bullet_list()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_bullet_list() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/bullet-list',
		array(
			'render_callback' => 'render_block_pbg_bullet_list',
		)
	);
}

register_block_pbg_bullet_list();
