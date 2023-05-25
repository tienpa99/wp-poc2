<?php

/**
 * Server-side rendering of the `pbg/content-switcher` block.
 *
 * @package WordPress
 */

function get_content_switcher_css_style( $attributes, $unique_id ) {
	$block_helpers = pbg_blocks_helper();
	$css           = new Premium_Blocks_css();
	$unique_id     = $attributes['blockId'];

	// Container styles
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Desktop' ) );
	}
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Desktop' ) );
	}
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Desktop' ) . '!important' );
		$css->add_property( 'justify-content', ( $attributes['align']['Desktop'] == 'right' ? 'flex-end' : ( $attributes['align']['Desktop'] == 'left' ? 'flex-start' : $attributes['align']['Desktop'] ) . '!important' ) );
		$css->add_property( 'align-items', 'center !important' );
	}
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Desktop' ) . '!important' );
		$css->add_property( 'justify-content', ( $attributes['align']['Desktop'] == 'right' ? 'flex-end' : ( $attributes['align']['Desktop'] == 'left' ? 'flex-start' : $attributes['align']['Desktop'] ) . '!important' ) );
		$css->add_property( 'align-items', ( $attributes['align']['Desktop'] == 'right' ? 'flex-end' : ( $attributes['align']['Desktop'] == 'left' ? 'flex-start' : $attributes['align']['Desktop'] ) . '!important' ) );
	}
	if ( isset( $attributes['containerPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['containerPadding']['Desktop'], $attributes['containerPadding']['unit'] ) );
	}

	if ( isset( $attributes['containerMargin'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'margin', $css->render_spacing( $attributes['containerMargin']['Desktop'], $attributes['containerMargin']['unit'] ) );
	}
	if ( isset( $attributes['containerBackground'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->render_background( $attributes["containerBackground"], 'Desktop' );
	}

	if ( isset( $attributes['switcherBackground'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-content-switcher-toggle-switch-slider' );
		$css->render_background( $attributes["switcherBackground"], 'Desktop' );
	}
	if ( isset( $attributes['controllerOneBackground'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-content-switcher-toggle-switch-slider:before' );
		$css->render_background( $attributes["controllerOneBackground"], 'Desktop' );
	}
	if ( isset( $attributes['containerborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['containerborder']['borderWidth']['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['containerborder']['borderRadius']['Desktop'], 'px' ) );
	}

	// First Label styles
	if ( isset( $attributes['firstLabelTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->render_typography( $attributes['firstLabelTypography'], 'Desktop' );
	}
	if ( isset( $attributes['firstLabelTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->render_typography( $attributes['firstLabelTypography'], 'Desktop' );
	}

	if ( isset( $attributes['firstLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['firstLabelPadding']['Desktop'], $attributes['firstLabelPadding']['unit'] ) );
	}
	if ( isset( $attributes['firstLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['firstLabelPadding']['Desktop'], $attributes['firstLabelPadding']['unit'] ) );
	}

	if ( isset( $attributes['labelSpacing'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' );
		$css->add_property( 'margin-right', $css->render_range( $attributes['labelSpacing'], 'Desktop' ) );
	}
	if ( isset( $attributes['labelSpacing'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' );
		$css->add_property( 'margin-bottom', $css->render_range( $attributes['labelSpacing'], 'Desktop' ) );
	}
	if ( isset( $attributes['firstLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['firstLabelborder']['borderWidth']['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['firstLabelborder']['borderRadius']['Desktop'], 'px' ) );
	}
	if ( isset( $attributes['firstLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['firstLabelborder']['borderWidth']['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['firstLabelborder']['borderRadius']['Desktop'], 'px' ) );
	}

	// Second Label styles
	if ( isset( $attributes['secondLabelTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->render_typography( $attributes['secondLabelTypography'], 'Desktop' );
	}
	if ( isset( $attributes['secondLabelTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->render_typography( $attributes['secondLabelTypography'], 'Desktop' );
	}

	if ( isset( $attributes['secondLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['secondLabelPadding']['Desktop'], $attributes['secondLabelPadding']['unit'] ) );
	}
	if ( isset( $attributes['secondLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['secondLabelPadding']['Desktop'], $attributes['secondLabelPadding']['unit'] ) );
	}

	if ( isset( $attributes['labelSpacing'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'margin-left', $css->render_range( $attributes['labelSpacing'], 'Desktop' ) );
	}
	if ( isset( $attributes['labelSpacing'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'margin-top', $css->render_range( $attributes['labelSpacing'], 'Desktop' ) );
	}
	if ( isset( $attributes['secondLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['secondLabelborder']['borderWidth']['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['secondLabelborder']['borderRadius']['Desktop'], 'px' ) );
	}
	if ( isset( $attributes['secondLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['secondLabelborder']['borderWidth']['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['secondLabelborder']['borderRadius']['Desktop'], 'px' ) );
	}

	// Switch styles
	if ( isset( $attributes['switchSize'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-toggle-switch' );
		$css->add_property( 'font-size', $css->render_range( $attributes['switchSize'], 'Desktop' ) );
	}
	if ( isset( $attributes['switchSize'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-toggle-switch' );
		$css->add_property( 'font-size', $css->render_range( $attributes['switchSize'], 'Desktop' ) );
	}

	$css->start_media_query( 'tablet' );

	// Container styles
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Tablet' ) );
	}
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Tablet' ) );
	}
	if ( isset( $attributes['align']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Tablet' ) . '!important' );
		$css->add_property( 'justify-content', ( $attributes['align']['Tablet'] == 'right' ? 'flex-end' : ( $attributes['align']['Tablet'] == 'left' ? 'flex-start' : $attributes['align']['Tablet'] ) . '!important' ) );
		$css->add_property( 'align-items', 'center !important' );
	}
	if ( isset( $attributes['align']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Tablet' ) . '!important' );
		$css->add_property( 'justify-content', ( $attributes['align']['Tablet'] == 'right' ? 'flex-end' : ( $attributes['align']['Tablet'] == 'left' ? 'flex-start' : $attributes['align']['Tablet'] ) . '!important' ) );
		$css->add_property( 'align-items', ( $attributes['align']['Tablet'] == 'right' ? 'flex-end' : ( $attributes['align']['Tablet'] == 'left' ? 'flex-start' : $attributes['align']['Tablet'] ) . '!important' ) );
	}
	if ( isset( $attributes['containerPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['containerPadding']['Tablet'], ( isset( $attributes['containerPadding']['unit'] ) ? $attributes['containerPadding']['unit'] : 'px' ) . '!important' ) );
	}

	if ( isset( $attributes['containerMargin'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'margin', $css->render_spacing( $attributes['containerMargin']['Tablet'], $attributes['containerMargin']['unit'] ) );
	}
	if ( isset( $attributes['containerBackground'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->render_background( $attributes["containerBackground"], 'Tablet' );
	}

	if ( isset( $attributes['switcherBackground'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-content-switcher-toggle-switch-slider' );
		$css->render_background( $attributes["switcherBackground"], 'Tablet' );
	}
	if ( isset( $attributes['controllerOneBackground'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-content-switcher-toggle-switch-slider:before' );
		$css->render_background( $attributes["controllerOneBackground"], 'Tablet' );
	}
	if ( isset( $attributes['containerborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['containerborder']['borderWidth']['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['containerborder']['borderRadius']['Tablet'], 'px' ) );
	}

	// First Label styles
	if ( isset( $attributes['firstLabelTypography']['fontSize']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->render_typography( $attributes['firstLabelTypography'], 'Tablet' );
	}
	if ( isset( $attributes['firstLabelTypography']['fontSize']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->render_typography( $attributes['firstLabelTypography'], 'Tablet' );
	}

	if ( isset( $attributes['firstLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['firstLabelPadding']['Tablet'], $attributes['firstLabelPadding']['unit'] ) );
	}
	if ( isset( $attributes['firstLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['firstLabelPadding']['Tablet'], $attributes['firstLabelPadding']['unit'] ) );
	}

	if ( isset( $attributes['labelSpacing']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' );
		$css->add_property( 'margin-right', $css->render_range( $attributes['labelSpacing'], 'Tablet' ) );
	}
	if ( isset( $attributes['labelSpacing']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' );
		$css->add_property( 'margin-bottom', $css->render_range( $attributes['labelSpacing'], 'Tablet' ) );
	}
	if ( isset( $attributes['firstLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['firstLabelborder']['borderWidth']['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['firstLabelborder']['borderRadius']['Tablet'], 'px' ) );
	}
	if ( isset( $attributes['firstLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['firstLabelborder']['borderWidth']['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['firstLabelborder']['borderRadius']['Tablet'], 'px' ) );
	}

	// Second Label styles
	if ( isset( $attributes['secondLabelTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->render_typography( $attributes['secondLabelTypography'], 'Tablet' );
	}
	if ( isset( $attributes['secondLabelTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->render_typography( $attributes['secondLabelTypography'], 'Tablet' );
	}

	if ( isset( $attributes['secondLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['secondLabelPadding']['Tablet'], $attributes['secondLabelPadding']['unit'] ) );
	}
	if ( isset( $attributes['secondLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['secondLabelPadding']['Tablet'], $attributes['secondLabelPadding']['unit'] ) );
	}

	if ( isset( $attributes['labelSpacing']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'margin-left', $css->render_range( $attributes['labelSpacing'], 'Tablet' ) );
	}
	if ( isset( $attributes['labelSpacing']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'margin-top', $css->render_range( $attributes['labelSpacing'], 'Tablet' ) );
	}
	if ( isset( $attributes['secondLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['secondLabelborder']['borderWidth']['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['secondLabelborder']['borderRadius']['Tablet'], 'px' ) );
	}
	if ( isset( $attributes['secondLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['secondLabelborder']['borderWidth']['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['secondLabelborder']['borderRadius']['Tablet'], 'px' ) );
	}

	// Switch styles
	if ( isset( $attributes['switchSize']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-toggle-switch' );
		$css->add_property( 'font-size', $css->render_range( $attributes['switchSize'], 'Tablet' ) );
	}
	if ( isset( $attributes['switchSize']['Tablet'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-toggle-switch' );
		$css->add_property( 'font-size', $css->render_range( $attributes['switchSize'], 'Tablet' ) );
	}

	$css->stop_media_query();

	$css->start_media_query( 'mobile' );

	// Container styles
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Mobile' ) );
	}
	if ( isset( $attributes['align'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Mobile' ) );
	}
	if ( isset( $attributes['align']['Mobile'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Mobile' ) . '!important' );
		$css->add_property( 'justify-content', ( $attributes['align']['Mobile'] == 'right' ? 'flex-end' : ( $attributes['align']['Mobile'] == 'left' ? 'flex-start' : $attributes['align']['Mobile'] ) . '!important' ) );
		$css->add_property( 'align-items', 'center !important' );
	}
	if ( isset( $attributes['align']['Mobile'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' );
		$css->add_property( 'text-align', $css->get_responsive_css( $attributes['align'], 'Mobile' ) . '!important' );
		$css->add_property( 'justify-content', ( $attributes['align']['Mobile'] == 'right' ? 'flex-end' : ( $attributes['align']['Mobile'] == 'left' ? 'flex-start' : $attributes['align']['Mobile'] ) . '!important' ) );
		$css->add_property( 'align-items', ( $attributes['align']['Mobile'] == 'right' ? 'flex-end' : ( $attributes['align']['Mobile'] == 'left' ? 'flex-start' : $attributes['align']['Mobile'] ) . '!important' ) );
	}
	if ( isset( $attributes['containerPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['containerPadding']['Mobile'], ( isset( $attributes['containerPadding']['unit'] ) ? $attributes['containerPadding']['unit'] : 'px' ) . '!important' ) );
	}

	if ( isset( $attributes['containerMargin'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'margin', $css->render_spacing( $attributes['containerMargin']['Mobile'], $attributes['containerMargin']['unit'] ) );
	}
	if ( isset( $attributes['containerBackground'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->render_background( $attributes["containerBackground"], 'Mobile' );
	}

	if ( isset( $attributes['switcherBackground'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-content-switcher-toggle-switch-slider' );
		$css->render_background( $attributes["switcherBackground"], 'Mobile' );
	}
	if ( isset( $attributes['controllerOneBackground'] ) ) {
		$css->set_selector( '.' . $unique_id . ' .premium-content-switcher-toggle-switch-slider:before' );
		$css->render_background( $attributes["controllerOneBackground"], 'Mobile' );
	}
	if ( isset( $attributes['containerborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['containerborder']['borderWidth']['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['containerborder']['borderRadius']['Mobile'], 'px' ) );
	}

	// First Label styles
	if ( isset( $attributes['firstLabelTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->render_typography( $attributes['firstLabelTypography'], 'Mobile' );
	}
	if ( isset( $attributes['firstLabelTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->render_typography( $attributes['firstLabelTypography'], 'Mobile' );
	}

	if ( isset( $attributes['firstLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['firstLabelPadding']['Mobile'], $attributes['firstLabelPadding']['unit'] ) );
	}
	if ( isset( $attributes['firstLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['firstLabelPadding']['Mobile'], $attributes['firstLabelPadding']['unit'] ) );
	}

	if ( isset( $attributes['labelSpacing']['Mobile'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' );
		$css->add_property( 'margin-right', $css->render_range( $attributes['labelSpacing'], 'Mobile' ) );
	}
	if ( isset( $attributes['labelSpacing']['Mobile'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' );
		$css->add_property( 'margin-bottom', $css->render_range( $attributes['labelSpacing'], 'Mobile' ) );
	}
	if ( isset( $attributes['firstLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['firstLabelborder']['borderWidth']['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['firstLabelborder']['borderRadius']['Mobile'], 'px' ) );
	}
	if ( isset( $attributes['firstLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-first-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['firstLabelborder']['borderWidth']['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['firstLabelborder']['borderRadius']['Mobile'], 'px' ) );
	}

	// Second Label styles
	if ( isset( $attributes['secondLabelTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->render_typography( $attributes['secondLabelTypography'], 'Mobile' );
	}
	if ( isset( $attributes['secondLabelTypography'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->render_typography( $attributes['secondLabelTypography'], 'Mobile' );
	}

	if ( isset( $attributes['secondLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-inline' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['secondLabelPadding']['Mobile'], $attributes['secondLabelPadding']['unit'] ) );
	}
	if ( isset( $attributes['secondLabelPadding'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' . ' > .premium-content-switcher-block' . '-editing' );
		$css->add_property( 'padding', $css->render_spacing( $attributes['secondLabelPadding']['Mobile'], $attributes['secondLabelPadding']['unit'] ) );
	}

	if ( isset( $attributes['labelSpacing'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'margin-left', $css->render_range( $attributes['labelSpacing'], 'Mobile' ) );
	}
	if ( isset( $attributes['labelSpacing'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'margin-top', $css->render_range( $attributes['labelSpacing'], 'Mobile' ) );
	}
	if ( isset( $attributes['secondLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['secondLabelborder']['borderWidth']['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['secondLabelborder']['borderRadius']['Mobile'], 'px' ) );
	}
	if ( isset( $attributes['secondLabelborder'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-second-label' );
		$css->add_property( 'border-width', $css->render_spacing( $attributes['secondLabelborder']['borderWidth']['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $attributes['secondLabelborder']['borderRadius']['Mobile'], 'px' ) );
	}

	// Switch styles
	if ( isset( $attributes['switchSize']['Mobile'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-inline' . ' > .premium-content-switcher-toggle-switch' );
		$css->add_property( 'font-size', $css->render_range( $attributes['switchSize'], 'Mobile' ) );
	}
	if ( isset( $attributes['switchSize']['Mobile'] ) ) {
		$css->set_selector( '.' . $unique_id . ' > .premium-content-switcher' . ' > .premium-content-switcher-toggle-block' . ' > .premium-content-switcher-toggle-switch' );
		$css->add_property( 'font-size', $css->render_range( $attributes['switchSize'], 'Mobile' ) );
	}

	$css->stop_media_query();

	return $css->css_output();
}

function should_render_inline( $name, $unique_id ) {
	if ( doing_filter( 'the_content' ) || apply_filters( 'premium_blocks_force_render_inline_css_in_content', false, $name, $unique_id ) || is_customize_preview() ) {
		return true;
	}
	return false;
}

function render_block_pbg_content_switcher( $attributes, $content ) {
	$wrapper_attributes = get_block_wrapper_attributes();
	$unique_id          = $attributes['blockId'];
	$align_class_name   = empty( $attributes['textAlign'] ) ? '' : "has-text-align-{$attributes['textAlign']}";
	$wrapper_attributes = get_block_wrapper_attributes( array( 'class' => $align_class_name ) );
	$block_helpers      = pbg_blocks_helper();

	// Enqueue frontend JavaScript and CSS.
	wp_enqueue_script(
		'content-switcher',
		PREMIUM_BLOCKS_URL . 'assets/js/content-switcher.js',
		array( 'jquery' ),
		PREMIUM_BLOCKS_VERSION,
		true
	);

	$style_id  = 'pbg-blocks-style' . esc_attr( $unique_id );
	$id        = 'premium-content-switcher-' . esc_attr( $unique_id );
	$unique_id = $attributes['blockId'];
	if ( ! wp_style_is( $style_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'content-switcher', $unique_id ) ) {
		// If filter didn't run in header (which would have enqueued the specific css id ) then filter attributes for easier dynamic css.
		// $attributes = apply_filters( 'Premium_BLocks_blocks_column_render_block_attributes', $attributes );
		$css = get_content_switcher_css_style( $attributes, $unique_id );
		if ( ! empty( $css ) ) {
			if ( should_render_inline( 'content-switcher', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
			} else {
				$block_helpers->render_inline_css( $css, 'content-switcher' );
			}
		}
	};

	return $content;
}




/**
 * Registers the `pbg/content-switcher` block on the server.
 */
function register_block_pbg_content_switcher() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . '/blocks-config/content-switcher',
		array(
			'render_callback' => 'render_block_pbg_content_switcher',
		)
	);
}

register_block_pbg_content_switcher();
