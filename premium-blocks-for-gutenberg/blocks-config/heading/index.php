<?php
/**
 * Server-side rendering of the `pbg/haeding` block.
 *
 * @package WordPress
 */

/**
 * Get Heading Block CSS
 *
 * Return Frontend CSS for Heading.
 *
 * @access public
 *
 * @param string $attr option attribute.
 * @param string $unique_id option For block ID.
 */
function get_premium_heading_css_style( $attr, $unique_id ) {
	$css = new Premium_Blocks_css();

	// Align.
	if ( isset( $attr['align'] ) ) {
		$align = $css->get_responsive_css( $attr['align'], 'Desktop' );
		$css->set_selector( $unique_id . ', ' . $unique_id );
		$css->add_property( 'text-align', $align );
	}

	if ( isset( $attr['titlePadding'] ) ) {
		$title_padding = $attr['titlePadding'];
		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->add_property( 'padding', $css->render_spacing( $title_padding['Desktop'], $title_padding['unit'] ) );
	}

	if ( isset( $attr['titleMargin'] ) ) {
		$title_margin = $attr['titleMargin'];
		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->add_property( 'margin', $css->render_spacing( $title_margin['Desktop'], $title_margin['unit'] ) );
	}

	if ( isset( $attr['titleTypography'] ) ) {
		$typography_title = $attr['titleTypography'];
		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->render_typography( $typography_title, 'Desktop' );
	}

	if ( isset( $attr['titleBorder'] ) ) {
		$title_border        = $attr['titleBorder'];
		$title_border_width  = $title_border['borderWidth'];
		$title_border_radius = $title_border['borderRadius'];
		$border_left_width   = $css->get_responsive_value( $title_border_width, 'left', 'Desktop', 'px' );
		$border_bottom_width = $css->get_responsive_value( $title_border_width, 'bottom', 'Desktop', 'px' );

		$css->set_selector( $unique_id . ' .style1 .premium-title-header' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Desktop'], 'px' ) );
		if ( $border_left_width && 1 <= $border_left_width ) {
			$css->add_property( 'border-left', "{$border_left_width} {$title_border['borderType']} {$title_border['borderColor']}!important" );
		}
		$css->set_selector( $unique_id . ' .style2, ' . $unique_id . ' .style4, ' . $unique_id . ' .style5, ' . $unique_id . ' .style6' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Desktop'], 'px' ) );
		if ( $border_bottom_width && 0 <= $border_bottom_width ) {
			$css->add_property( 'border-bottom', "{$border_bottom_width} {$title_border['borderType']} {$title_border['borderColor']}!important" );
		}
		$css->set_selector( $unique_id . ' > .default .premium-title-header' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Desktop'], 'px' ) );
	}

	// Style for icon.
	if ( isset( $attr['iconAlign'] ) ) {
		$align      = $css->get_responsive_css( $attr['iconAlign'], 'Desktop' );
		$flex_align = 'left' === $align ? 'flex-start' : 'center';
		$flex_align = 'right' === $align ? 'flex-end' : $flex_align;

		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->add_property( 'align-items', $flex_align );
	}
	if ( isset( $attr['align'] ) ) {
		$align      = $css->get_responsive_css( $attr['align'], 'Desktop' );
		$flex_align = 'left' === $align ? 'flex-start' : 'center';
		$flex_align = 'right' === $align ? 'flex-end' : $flex_align;

		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->add_property( 'justify-content', $flex_align );
	}
	if ( isset( $attr['iconPadding'] ) ) {
		$icon_padding = $attr['iconPadding'];
		$css->set_selector( $unique_id . ' .premium-title-header' . ' .premium-title-icon' );
		$css->add_property( 'padding', $css->render_spacing( $icon_padding['Desktop'], $icon_padding['unit'] ) );
	}

	if ( isset( $attr['iconMargin'] ) ) {
		$icon_margin = $attr['iconMargin'];
		$css->set_selector( $unique_id . ' .premium-title-icon' );
		$css->add_property( 'margin', $css->render_spacing( $icon_margin['Desktop'], $icon_margin['unit'] ) );
	}

	if ( isset( $attr['iconBorder'] ) ) {
		$icon_border        = $attr['iconBorder'];
		$icon_border_width  = $icon_border['borderWidth'];
		$icon_border_radius = $icon_border['borderRadius'];

		$css->set_selector( $unique_id . ' .premium-title-icon' );
		$css->add_property( 'border-width', $css->render_spacing( $icon_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $icon_border_radius['Desktop'], 'px' ) );
	}

	if ( isset( $attr['iconSize'] ) ) {
		$icon_size = $attr['iconSize'];

		$css->set_selector( $unique_id . ' .premium-title-icon' . ' > svg' );
		$css->add_property( 'width', $css->render_range( $icon_size, 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $icon_size, 'Desktop' ) );
		$css->set_selector( $unique_id . ' > .premium-title-header' . ' > .premium-lottie-animation' . ' > svg' );
		$css->add_property( 'width', $css->render_range( $icon_size, 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $icon_size, 'Desktop' ) );
		$css->set_selector( $unique_id . ' > .premium-title-header' . ' > img' );
		$css->add_property( 'width', $css->render_range( $icon_size, 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $icon_size, 'Desktop' ) );
	}

	// stripeStyles
	if ( isset( $attr['stripeTopSpacing'] ) ) {
		$stripe_top_spacing = $attr['stripeTopSpacing'];
		$css->set_selector( $unique_id . ' .premium-title-style7-stripe__wrap' );
		$css->add_property( 'margin-top', $css->render_range( $stripe_top_spacing, 'Desktop' ) );
	}

	if ( isset( $attr['stripeBottomSpacing'] ) ) {
		$stripe_bottom_spacing = $attr['stripeBottomSpacing'];
		$css->set_selector( $unique_id . ' .premium-title-style7-stripe__wrap' );
		$css->add_property( 'margin-bottom', $css->render_range( $stripe_bottom_spacing, 'Desktop' ) );
	}

	if ( isset( $attr['stripeWidth'] ) ) {
		$stripe_width = $attr['stripeWidth'];
		$css->set_selector( $unique_id . ' .premium-title-style7-stripe__wrap' . ' .premium-title-style7-stripe-span' );
		$css->add_property( 'width', $css->render_range( $stripe_width, 'Desktop' ) );
	}

	if ( isset( $attr['stripeHeight'] ) ) {
		$stripe_height = $attr['stripeHeight'];
		$css->set_selector( $unique_id . ' .premium-title-style7-stripe__wrap' . ' .premium-title-style7-stripe-span' );
		$css->add_property( 'height', $css->render_range( $stripe_height, 'Desktop' ) );
	}

	// background text
	if ( isset( $attr['verticalText'] ) ) {
		$vertical_text = $attr['verticalText'];
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->add_property( 'top', $css->render_range( $vertical_text, 'Desktop' ) );
	}

	if ( isset( $attr['horizontalText'] ) ) {
		$horizontal_text = $attr['horizontalText'];
		$css->set_selector( $unique_id . ' > .premium-title-bg-text:before' );
		$css->add_property( 'left', $css->render_range( $horizontal_text, 'Desktop' ) );
	}

	if ( isset( $attr['rotateText'] ) && $attr['backgroundText'] ) {
		$rotate_text = $attr['rotateText'];
		$value       = $css->render_range( $rotate_text, 'Desktop' );
		$css->set_selector( $unique_id . ' > .premium-title-bg-text:before' );
		$css->add_property( 'transform', "rotate({$value})!important" );
	}

	if ( isset( $attr['strokeFull'] ) ) {
		$stroke_full = $attr['strokeFull'];
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->add_property( '-webkit-text-stroke-width', $css->render_range( $stroke_full, 'Desktop' ) );
	}

	if ( isset( $attr['textTypography'] ) ) {
		$text_typography = $attr['textTypography'];
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->render_typography( $text_typography, 'Desktop' );
	}

	$css->start_media_query( 'tablet' );

	// Align.
	if ( isset( $attr['align'] ) ) {
		$align = $css->get_responsive_css( $attr['align'], 'Tablet' );
		$css->set_selector( $unique_id . ', ' . $unique_id );
		$css->add_property( 'text-align', $align );
	}

	if ( isset( $attr['titlePadding'] ) ) {
		$title_padding = $attr['titlePadding'];
		$css->set_selector( $unique_id . ' .premium-title-text-title, ' . $unique_id . ' .premium-title-style9__wrap .premium-letters-container' );
		$css->add_property( 'padding', $css->render_spacing( $title_padding['Tablet'], $title_padding['unit'] ) );
	}

	if ( isset( $attr['titleMargin'] ) ) {
		$title_margin = $attr['titleMargin'];
		$css->set_selector( $unique_id . ' .premium-title-text-title, ' . $unique_id . ' .premium-title-style9__wrap .premium-letters-container' );
		$css->add_property( 'margin', $css->render_spacing( $title_margin['Tablet'], $title_margin['unit'] ) );
	}

	if ( isset( $attr['titleTypography'] ) ) {
		$typography_title = $attr['titleTypography'];
		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->render_typography( $typography_title, 'Tablet' );
	}

	if ( isset( $attr['titleBorder'] ) ) {
		$title_border        = $attr['titleBorder'];
		$title_border_width  = $attr['titleBorder']['borderWidth'];
		$title_border_radius = $attr['titleBorder']['borderRadius'];
		$border_left_width   = $css->get_responsive_value( $title_border_width, 'left', 'Tablet', 'px' );
		$border_bottom_width = $css->get_responsive_value( $title_border_width, 'bottom', 'Tablet', 'px' );

		$css->set_selector( $unique_id . ' .style1 .premium-title-header' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Tablet'], 'px' ) );
		if ( $border_left_width && 1 <= $border_left_width ) {
			$css->add_property( 'border-left', "{$border_left_width} {$title_border['borderType']} {$title_border['borderColor']}!important" );
		}
		$css->set_selector( $unique_id . ' .style2, ' . $unique_id . ' .style4, ' . $unique_id . ' .style5, ' . $unique_id . ' .style6' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Tablet'], 'px' ) );
		if ( $border_bottom_width && 0 <= $border_bottom_width ) {
			$css->add_property( 'border-bottom', "{$border_bottom_width} {$title_border['borderType']} {$title_border['borderColor']}!important" );
		}
		$css->set_selector( $unique_id . ' .default .premium-title-header' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Tablet'], 'px' ) );
	}

	// Style for icon.
	if ( isset( $attr['iconAlign'] ) ) {
		$align      = $css->get_responsive_css( $attr['iconAlign'], 'Tablet' );
		$flex_align = 'left' === $align ? 'flex-start' : 'center';
		$flex_align = 'right' === $align ? 'flex-end' : $flex_align;

		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->add_property( 'align-items', $flex_align );
	}
	if ( isset( $attr['align'] ) ) {
		$align      = $css->get_responsive_css( $attr['align'], 'Tablet' );
		$flex_align = 'left' === $align ? 'flex-start' : 'center';
		$flex_align = 'right' === $align ? 'flex-end' : $flex_align;

		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->add_property( 'justify-content', $flex_align );
	}
	if ( isset( $attr['iconPadding'] ) ) {
		$icon_padding = $attr['iconPadding'];
		$css->set_selector( $unique_id . ' .premium-title-header' . ' .premium-title-icon' );
		$css->add_property( 'padding', $css->render_spacing( $icon_padding['Tablet'], $icon_padding['unit'] ) );
	}

	if ( isset( $attr['iconMargin'] ) ) {
		$icon_margin = $attr['iconMargin'];
		$css->set_selector( $unique_id . ' .premium-title-header' . ' .premium-title-icon' );
		$css->add_property( 'margin', $css->render_spacing( $icon_margin['Tablet'], $icon_margin['unit'] ) );
	}

	if ( isset( $attr['iconBorder'] ) ) {
		$icon_border        = $attr['iconBorder'];
		$icon_border_width  = $icon_border['borderWidth'];
		$icon_border_radius = $icon_border['borderRadius'];

		$css->set_selector( $unique_id . ' .premium-title-icon' );
		$css->add_property( 'border-width', $css->render_spacing( $icon_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $icon_border_radius['Tablet'], 'px' ) );
	}

	if ( isset( $attr['iconSize'] ) ) {
		$icon_size = $attr['iconSize'];

		$css->set_selector( $unique_id . ' .premium-title-icon' . ' > svg' );
		$css->add_property( 'width', $css->render_range( $icon_size, 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $icon_size, 'Desktop' ) );
		$css->set_selector( $unique_id . ' .premium-title-header' . ' > .premium-lottie-animation' . ' > svg' );
		$css->add_property( 'width', $css->render_range( $icon_size, 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $icon_size, 'Tablet' ) );
		$css->set_selector( $unique_id . ' .premium-title-header' . ' > img' );
		$css->add_property( 'width', $css->render_range( $icon_size, 'Tablet' ) );
		$css->add_property( 'height', $css->render_range( $icon_size, 'Tablet' ) );
	}

	// stripeStyles
	if ( isset( $attr['stripeTopSpacing'] ) ) {
		$stripe_top_spacing = $attr['stripeTopSpacing'];
		$css->set_selector( $unique_id . ' .premium-title-style7-stripe__wrap' );
		$css->add_property( 'margin-top', $css->render_range( $stripe_top_spacing, 'Tablet' ) );
	}

	if ( isset( $attr['stripeBottomSpacing'] ) ) {
		$stripe_bottom_spacing = $attr['stripeBottomSpacing'];
		$css->set_selector( $unique_id . ' .premium-title-style7-stripe__wrap' );
		$css->add_property( 'margin-bottom', $css->render_range( $stripe_bottom_spacing, 'Tablet' ) );
	}

	if ( isset( $attr['stripeWidth'] ) ) {
		$stripe_width = $attr['stripeWidth'];
		$css->set_selector( $unique_id . ' .premium-title-style7-stripe__wrap' . ' > .premium-title-style7-stripe-span' );
		$css->add_property( 'width', $css->render_range( $stripe_width, 'Tablet' ) );
	}

	if ( isset( $attr['stripeHeight'] ) ) {
		$stripe_height = $attr['stripeHeight'];
		$css->set_selector( $unique_id . ' .premium-title-style7-stripe__wrap' . ' > .premium-title-style7-stripe-span' );
		$css->add_property( 'height', $css->render_range( $stripe_height, 'Tablet' ) );
	}

	// background text
	if ( isset( $attr['verticalText'] ) ) {
		$vertical_text = $attr['verticalText'];
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->add_property( 'top', $css->render_range( $vertical_text, 'Tablet' ) );
	}

	if ( isset( $attr['horizontalText'] ) ) {
		$horizontal_text = $attr['horizontalText'];
		$css->set_selector( $unique_id . ' > .premium-title-bg-text:before' );
		$css->add_property( 'left', $css->render_range( $horizontal_text, 'Tablet' ) );
	}

	if ( isset( $attr['rotateText'] ) && $attr['backgroundText'] ) {
		$rotate_text = $attr['rotateText'];
		$value       = $css->render_range( $rotate_text, 'Tablet' );
		$css->set_selector( $unique_id . ' > .premium-title-bg-text:before' );
		$css->add_property( 'transform', "rotate({$value})!important" );
	}

	if ( isset( $attr['strokeFull'] ) ) {
		$stroke_full = $attr['strokeFull'];
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->add_property( '-webkit-text-stroke-width', $css->render_range( $stroke_full, 'Tablet' ) );
	}

	if ( isset( $attr['textTypography'] ) ) {
		$text_typography = $attr['textTypography'];
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->render_typography( $text_typography, 'Tablet' );
	}

	$css->stop_media_query();

	$css->start_media_query( 'mobile' );

	// Align.
	if ( isset( $attr['align'] ) ) {
		$align = $css->get_responsive_css( $attr['align'], 'Mobile' );
		$css->set_selector( $unique_id . ', ' . $unique_id );
		$css->add_property( 'text-align', $align );
	}

	if ( isset( $attr['titlePadding'] ) ) {
		$title_padding = $attr['titlePadding'];
		$css->set_selector( $unique_id . ' .premium-title-header' . ' .premium-title-text-title, ' . $unique_id . ' .premium-title-style9__wrap .premium-letters-container' );
		$css->add_property( 'padding', $css->render_spacing( $title_padding['Mobile'], $title_padding['unit'] ) );
	}

	if ( isset( $attr['titleMargin'] ) ) {
		$title_margin = $attr['titleMargin'];
		$css->set_selector( $unique_id . ' .premium-title-header' . ' .premium-title-text-title, ' . $unique_id . ' .premium-title-style9__wrap .premium-letters-container' );
		$css->add_property( 'margin', $css->render_spacing( $title_margin['Mobile'], $title_margin['unit'] ) );
	}

	if ( isset( $attr['titleTypography'] ) ) {
		$typography_title = $attr['titleTypography'];
		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->render_typography( $typography_title, 'Mobile' );
	}

	if ( isset( $attr['titleBorder'] ) ) {
		$title_border        = $attr['titleBorder'];
		$title_border_width  = $title_border['borderWidth'];
		$title_border_radius = $title_border['borderRadius'];
		$border_left_width   = $css->get_responsive_value( $title_border_width, 'left', 'Mobile', 'px' );
		$border_bottom_width = $css->get_responsive_value( $title_border_width, 'bottom', 'Mobile', 'px' );

		$css->set_selector( $unique_id . ' .style1 .premium-title-header' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Mobile'], 'px' ) );
		if ( $border_left_width && 1 <= $border_left_width ) {
			$css->add_property( 'border-left', "{$border_left_width} {$title_border['borderType']} {$title_border['borderColor']}!important" );
		}
		$css->set_selector( $unique_id . ' .style2, ' . $unique_id . ' .style4, ' . $unique_id . ' .style5, ' . $unique_id . ' .style6' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Mobile'], 'px' ) );
		if ( $border_bottom_width && 0 <= $border_bottom_width ) {
			$css->add_property( 'border-bottom', "{$border_bottom_width} {$title_border['borderType']} {$title_border['borderColor']}!important" );
		}
		$css->set_selector( $unique_id . ' .default .premium-title-header' );
		$css->add_property( 'border-width', $css->render_spacing( $title_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $title_border_radius['Mobile'], 'px' ) );
	}

	// Style for icon.
	if ( isset( $attr['iconAlign'] ) ) {
		$align      = $css->get_responsive_css( $attr['iconAlign'], 'Mobile' );
		$flex_align = 'left' === $align ? 'flex-start' : 'center';
		$flex_align = 'right' === $align ? 'flex-end' : $flex_align;

		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->add_property( 'align-items', $flex_align );
	}
	if ( isset( $attr['align'] ) ) {
		$align      = $css->get_responsive_css( $attr['align'], 'Mobile' );
		$flex_align = 'left' === $align ? 'flex-start' : 'center';
		$flex_align = 'right' === $align ? 'flex-end' : $flex_align;

		$css->set_selector( $unique_id . ' .premium-title-header' );
		$css->add_property( 'justify-content', $flex_align );
	}
	if ( isset( $attr['iconPadding'] ) ) {
		$icon_padding = $attr['iconPadding'];
		$css->set_selector( $unique_id . ' .premium-title-icon' );
		$css->add_property( 'padding', $css->render_spacing( $icon_padding['Mobile'], $icon_padding['unit'] ) );
	}

	if ( isset( $attr['iconMargin'] ) ) {
		$icon_margin = $attr['iconMargin'];
		$css->set_selector( $unique_id . ' .premium-title-icon' );
		$css->add_property( 'margin', $css->render_spacing( $icon_margin['Mobile'], $icon_margin['unit'] ) );
	}

	if ( isset( $attr['iconBorder'] ) ) {
		$icon_border        = $attr['iconBorder'];
		$icon_border_width  = $icon_border['borderWidth'];
		$icon_border_radius = $icon_border['borderRadius'];
		$css->set_selector( $unique_id . ' .premium-title-icon' );
		$css->add_property( 'border-width', $css->render_spacing( $icon_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $icon_border_radius['Mobile'], 'px' ) );
	}

	if ( isset( $attr['iconSize'] ) ) {
		$icon_size = $attr['iconSize'];

		$css->set_selector( $unique_id . ' .premium-title-icon' . ' > svg' );
		$css->add_property( 'width', $css->render_range( $icon_size, 'Desktop' ) );
		$css->add_property( 'height', $css->render_range( $icon_size, 'Desktop' ) );
		$css->set_selector( $unique_id . ' .premium-title-header' . ' > .premium-lottie-animation' . ' > svg' );
		$css->add_property( 'width', $css->render_range( $icon_size, 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $icon_size, 'Mobile' ) );
		$css->set_selector( $unique_id . ' .premium-title-header' . ' > img' );
		$css->add_property( 'width', $css->render_range( $icon_size, 'Mobile' ) );
		$css->add_property( 'height', $css->render_range( $icon_size, 'Mobile' ) );
	}

	// stripeStyles
	if ( isset( $attr['stripeTopSpacing'] ) ) {
		$stripe_top_spacing = $attr['stripeTopSpacing'];
		$css->set_selector( $unique_id . ' .premium-title-header' . ' > .premium-title-style7-stripe__wrap' );
		$css->add_property( 'margin-top', $css->render_range( $stripe_top_spacing, 'Mobile' ) );
	}

	if ( isset( $attr['stripeBottomSpacing'] ) ) {
		$stripe_bottom_spacing = $attr['stripeBottomSpacing'];
		$css->set_selector( $unique_id . ' .premium-title-header' . ' .premium-title-style7-stripe__wrap' );
		$css->add_property( 'margin-bottom', $css->render_range( $stripe_bottom_spacing, 'Mobile' ) );
	}

	if ( isset( $attr['stripeWidth'] ) ) {
		$stripe_width = $attr['stripeWidth'];
		$css->set_selector( $unique_id . ' .premium-title-header' . ' .premium-title-style7-stripe__wrap' . ' .premium-title-style7-stripe-span' );
		$css->add_property( 'width', $css->render_range( $stripe_width, 'Mobile' ) );
	}

	if ( isset( $attr['stripeHeight'] ) ) {
		$stripe_height = $attr['stripeHeight'];
		$css->set_selector( $unique_id . ' .premium-title-header' . ' .premium-title-style7-stripe__wrap' . ' .premium-title-style7-stripe-span' );
		$css->add_property( 'height', $css->render_range( $stripe_height, 'Mobile' ) );
	}

	// background text
	if ( isset( $attr['verticalText'] ) ) {
		$vertical_text = $attr['verticalText'];
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->add_property( 'top', $css->render_range( $vertical_text, 'Mobile' ) );
	}

	if ( isset( $attr['horizontalText'] ) ) {
		$horizontal_text = $attr['horizontalText'];
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->add_property( 'left', $css->render_range( $horizontal_text, 'Mobile' ) );
	}

	if ( isset( $attr['rotateText'] ) && $attr['backgroundText'] ) {
		$rotate_text = $attr['rotateText'];
		$value       = $css->render_range( $rotate_text, 'Mobile' );
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->add_property( 'transform', "rotate({$value})!important" );
	}

	if ( isset( $attr['strokeFull'] ) ) {
		$stroke_full = $attr['strokeFull'];
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->add_property( '-webkit-text-stroke-width', $css->render_range( $stroke_full, 'Mobile' ) );
	}

	if ( isset( $attr['textTypography'] ) ) {
		$text_typography = $attr['textTypography'];
		$css->set_selector( $unique_id . ' .premium-title-bg-text:before' );
		$css->render_typography( $text_typography, 'Mobile' );
	}

	$css->stop_media_query();
	return $css->css_output();
}

/**
 * Renders the `premium/heading` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_heading( $attributes, $content ) {
	$block_helpers = pbg_blocks_helper();
	if ( isset( $attributes['block_id'] ) && ! empty( $attributes['block_id'] ) ) {
		$unique_id = "#premium-title-{$attributes['block_id']}";
	}

	if ( isset( $attributes['blockId'] ) && ! empty( $attributes['blockId'] ) ) {
		$unique_id = ".{$attributes['blockId']}";
	}

	if ( $block_helpers->it_is_not_amp() ) {
		if ( isset( $attributes['iconType'] ) && $attributes['iconType'] == 'lottie' ) {
			wp_enqueue_script(
				'pbg-lottie',
				PREMIUM_BLOCKS_URL . 'assets/js/lottie.js',
				array( 'jquery' ),
				PREMIUM_BLOCKS_VERSION,
				true
			);
		}

		if ( isset( $attributes['style'] ) && ( $attributes['style'] == 'style8' || $attributes['style'] == 'style9' ) ) {
			wp_enqueue_script(
				'pbg-heading',
				PREMIUM_BLOCKS_URL . 'assets/js/heading.js',
				array( 'jquery' ),
				PREMIUM_BLOCKS_VERSION,
				true
			);
		}
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'heading', $unique_id ) ) {
		$css = get_premium_heading_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( $block_helpers->should_render_inline( 'heading', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'heading' );
			}
		}
	};

	return $content;
}




/**
 * Register the heading block.
 *
 * @uses render_block_pbg_heading()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_heading() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/heading',
		array(
			'render_callback' => 'render_block_pbg_heading',
		)
	);
}

register_block_pbg_heading();
