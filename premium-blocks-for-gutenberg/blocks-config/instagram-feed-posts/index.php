<?php
// Move this file to "blocks-config" folder with name "instagram-feed-posts.php".

/**
 * Get Dynamic CSS.
 *
 * @param array  $attr
 * @param string $unique_id
 * @return string
 */
function get_premium_instagram_feed_posts_css( $attr, $unique_id ) {
	$block_helpers          = pbg_blocks_helper();
	$css                    = new Premium_Blocks_css();
	$media_query            = array();
	$media_query['mobile']  = apply_filters( 'Premium_BLocks_mobile_media_query', '(max-width: 767px)' );
	$media_query['tablet']  = apply_filters( 'Premium_BLocks_tablet_media_query', '(max-width: 1024px)' );
	$media_query['desktop'] = apply_filters( 'Premium_BLocks_tablet_media_query', '(min-width: 1025px)' );

	// Desktop Styles.
	// Container.
	if ( isset( $attr['containerColor'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'color', $attr['containerColor'] );
	}

	if ( isset( $attr['containerShadow'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'box-shadow', $css->render_shadow( $attr['containerShadow'] ) );
	}

	if ( isset( $attr['containerBackground'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->render_background( $attr['containerBackground'], $css );
	}

	if ( isset( $attr['containerBorder'] ) ) {
		$container_border_width  = $attr['containerBorder']['borderWidth'];
		$container_border_radius = $attr['containerBorder']['borderRadius'];

		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'border-style', $attr['containerBorder']['borderType'] );
		$css->add_property( 'border-color', $attr['containerBorder']['borderColor'] );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Desktop'], 'px' ) );
	}

	if ( isset( $attr['containerMargin'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'margin', $css->render_spacing( $attr['containerMargin']['Desktop'], $attr['containerMargin']['unit'] ) );
	}

	if ( isset( $attr['containerPadding'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'padding', $css->render_spacing( $attr['containerPadding']['Desktop'], $attr['containerPadding']['unit'] ) );
	}

	// Image.
	if ( isset( $attr['photoShadow'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'box-shadow', $css->render_shadow( $attr['photoShadow'] ) );
	}

	if ( isset( $attr['photoFilter'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'filter', $css->render_filter( $attr['photoFilter'] ) );
	}

	if ( isset( $attr['photoBorder'] ) ) {
		$container_border_width  = $attr['photoBorder']['borderWidth'];
		$container_border_radius = $attr['photoBorder']['borderRadius'];

		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'border-style', $attr['photoBorder']['borderType'] );
		$css->add_property( 'border-color', $attr['photoBorder']['borderColor'] );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Desktop'], 'px' ) );
	}

	if ( isset( $attr['photoMargin'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'margin', $css->render_spacing( $attr['photoMargin']['Desktop'], $attr['photoMargin']['unit'] ) );
	}

	if ( isset( $attr['photoPadding'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'padding', $css->render_spacing( $attr['photoPadding']['Desktop'], $attr['photoPadding']['unit'] ) );
	}

	if ( isset( $attr['photoHoverShadow'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media:hover" );
		$css->add_property( 'box-shadow', $css->render_shadow( $attr['photoHoverShadow'] ) );
	}

	if ( isset( $attr['photoHoverFilter'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media:hover" );
		$css->add_property( 'filter', $css->render_filter( $attr['photoHoverFilter'] ) );
	}

	if ( isset( $attr['photoHoverBorder'] ) ) {
		$container_border_width  = $attr['photoHoverBorder']['borderWidth'];
		$container_border_radius = $attr['photoHoverBorder']['borderRadius'];

		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media:hover" );
		$css->add_property( 'border-style', $attr['photoHoverBorder']['borderType'] );
		$css->add_property( 'border-color', $attr['photoHoverBorder']['borderColor'] );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Desktop'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Desktop'], 'px' ) );
	}

	if ( isset( $attr['photoHoverMargin'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media:hover" );
		$css->add_property( 'margin', $css->render_spacing( $attr['photoHoverMargin']['Desktop'], $attr['photoHoverMargin']['unit'] ) );
	}

	// Caption.
	if ( isset( $attr['captionColor'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-caption" );
		$css->add_property( 'color', $attr['captionColor'] );
	}

	if ( isset( $attr['captionTypography'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-caption" );
		$css->render_typography( $attr['captionTypography'], 'Desktop' );
	}

	if ( isset( $attr['captionShadow'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-caption" );
		$css->add_property( 'text-shadow', $css->render_shadow( $attr['captionShadow'] ) );
	}

	if ( isset( $attr['overlayColor'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap.overlay-caption .pbg-insta-feed-caption" );
		$css->add_property( 'background-color', $attr['overlayColor'] );
	}

	// Lightbox.
	if ( isset( $attr['clickAction'] ) && $attr['clickAction'] === 'lightBox' ) {
		if ( isset( $attr['lightBoxOverlayColor'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox .pbg-lightbox-overlay" );
			$css->add_property( 'background-color', $attr['lightBoxOverlayColor'] );
		}
		if ( isset( $attr['lightBoxArrowsBorderRadius'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow" );
			$css->add_property( 'border-radius', $css->render_range( $attr['lightBoxArrowsBorderRadius'], 'Desktop' ) );
		}
		if ( isset( $attr['lightBoxArrowsPadding'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow" );
			$css->add_property( 'padding', $css->render_spacing( $attr['lightBoxArrowsPadding']['Desktop'], $attr['lightBoxArrowsPadding']['unit'] ) );
		}
		if ( isset( $attr['lightBoxArrowsBackground'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow" );
			$css->render_background( $attr['lightBoxArrowsBackground'], $css );
		}
		if ( isset( $attr['lightBoxArrowsHoverBackground'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow:hover" );
			$css->render_background( $attr['lightBoxArrowsHoverBackground'], $css );
		}
		if ( isset( $attr['lightBoxArrowsColor'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow .dashicons" );
			$css->add_property( 'color', $attr['lightBoxArrowsColor'] );
		}
		if ( isset( $attr['lightBoxArrowsSize'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow .dashicons" );
			$css->add_property( 'font-size', $css->render_range( $attr['lightBoxArrowsSize'], 'Desktop' ) );
		}
		if ( isset( $attr['lightBoxArrowsHColor'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow:hover .dashicons" );
			$css->add_property( 'color', $attr['lightBoxArrowsHColor'] );
		}
	}

	// Carousel.
	if ( isset( $attr['layoutStyle'] ) && $attr['layoutStyle'] === 'carousel' ) {
		if ( isset( $attr['arrowsBorderRadius'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-arrow" );
			$css->add_property( 'border-radius', $css->render_range( $attr['arrowsBorderRadius'], 'Desktop' ) );
		}
		if ( isset( $attr['arrowsPadding'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-arrow" );
			$css->add_property( 'padding', $css->render_spacing( $attr['arrowsPadding']['Desktop'], $attr['arrowsPadding']['unit'] ) );
		}
		if ( isset( $attr['arrowsBackground'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-arrow" );
			$css->render_background( $attr['arrowsBackground'], $css );
		}
		if ( isset( $attr['arrowsPosition'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-next" );
			$css->add_property( 'right', $css->render_range( $attr['arrowsPosition'], 'Desktop' ) );
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-prev" );
			$css->add_property( 'left', $css->render_range( $attr['arrowsPosition'], 'Desktop' ) );
		}
		if ( isset( $attr['arrowsVerticalPosition'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-next" );
			$css->add_property( 'top', $css->render_range( $attr['arrowsVerticalPosition'], 'Desktop' ) );
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-prev" );
			$css->add_property( 'top', $css->render_range( $attr['arrowsVerticalPosition'], 'Desktop' ) );
		}
		if ( isset( $attr['arrowsHoverBackground'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow:hover" );
			$css->render_background( $attr['arrowsHoverBackground'], $css );
		}
		if ( isset( $attr['arrowsColor'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow:before" );
			$css->add_property( 'color', $attr['arrowsColor'] );
		}
		if ( isset( $attr['arrowsSize'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow:before" );
			$css->add_property( 'font-size', $css->render_range( $attr['arrowsSize'], 'Desktop' ) );
		}
		if ( isset( $attr['arrowsHoverColor'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow:hover:before" );
			$css->add_property( 'color', $attr['arrowsHoverColor'] );
		}
	}

	$css->start_media_query( $media_query['tablet'] );
	// Tablet Styles.
	// Container.
	if ( isset( $attr['containerBorder'] ) ) {
		$container_border_width  = $attr['containerBorder']['borderWidth'];
		$container_border_radius = $attr['containerBorder']['borderRadius'];

		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Tablet'], 'px' ) );
	}

	if ( isset( $attr['containerMargin'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'margin', $css->render_spacing( $attr['containerMargin']['Tablet'], $attr['containerMargin']['unit'] ) );
	}

	if ( isset( $attr['containerPadding'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'padding', $css->render_spacing( $attr['containerPadding']['Tablet'], $attr['containerPadding']['unit'] ) );
	}

	// Image.
	if ( isset( $attr['photoBorder'] ) ) {
		$container_border_width  = $attr['photoBorder']['borderWidth'];
		$container_border_radius = $attr['photoBorder']['borderRadius'];

		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Tablet'], 'px' ) );
	}

	if ( isset( $attr['photoMargin'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'margin', $css->render_spacing( $attr['photoMargin']['Tablet'], $attr['photoMargin']['unit'] ) );
	}

	if ( isset( $attr['photoPadding'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'padding', $css->render_spacing( $attr['photoPadding']['Tablet'], $attr['photoPadding']['unit'] ) );
	}

	if ( isset( $attr['photoHoverBorder'] ) ) {
		$container_border_width  = $attr['photoHoverBorder']['borderWidth'];
		$container_border_radius = $attr['photoHoverBorder']['borderRadius'];

		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media:hover" );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Tablet'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Tablet'], 'px' ) );
	}

	if ( isset( $attr['photoHoverMargin'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media:hover" );
		$css->add_property( 'margin', $css->render_spacing( $attr['photoHoverMargin']['Tablet'], $attr['photoHoverMargin']['unit'] ) );
	}

	// Caption.
	if ( isset( $attr['captionTypography'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-caption" );
		$css->render_typography( $attr['captionTypography'], 'Tablet' );
	}

	// Lightbox.
	if ( isset( $attr['clickAction'] ) && $attr['clickAction'] === 'lightBox' ) {
		if ( isset( $attr['lightBoxArrowsBorderRadius'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow" );
			$css->add_property( 'border-radius', $css->render_range( $attr['lightBoxArrowsBorderRadius'], 'Tablet' ) );
		}
		if ( isset( $attr['lightBoxArrowsPadding'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow" );
			$css->add_property( 'padding', $css->render_spacing( $attr['lightBoxArrowsPadding']['Tablet'], $attr['lightBoxArrowsPadding']['unit'] ) );
		}
		if ( isset( $attr['lightBoxArrowsSize'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow .dashicons" );
			$css->add_property( 'font-size', $css->render_range( $attr['lightBoxArrowsSize'], 'Tablet' ) );
		}
	}

	// Carousel.
	if ( isset( $attr['layoutStyle'] ) && $attr['layoutStyle'] === 'carousel' ) {
		if ( isset( $attr['arrowsBorderRadius'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-arrow" );
			$css->add_property( 'border-radius', $css->render_range( $attr['arrowsBorderRadius'], 'Tablet' ) );
		}
		if ( isset( $attr['arrowsPadding'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-arrow" );
			$css->add_property( 'padding', $css->render_spacing( $attr['arrowsPadding']['Tablet'], $attr['arrowsPadding']['unit'] ) );
		}
		if ( isset( $attr['arrowsPosition'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-next" );
			$css->add_property( 'right', $css->render_range( $attr['arrowsPosition'], 'Tablet' ) );
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-prev" );
			$css->add_property( 'left', $css->render_range( $attr['arrowsPosition'], 'Tablet' ) );
		}
		if ( isset( $attr['arrowsVerticalPosition'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-next" );
			$css->add_property( 'top', $css->render_range( $attr['arrowsVerticalPosition'], 'Tablet' ) );
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-prev" );
			$css->add_property( 'top', $css->render_range( $attr['arrowsVerticalPosition'], 'Tablet' ) );
		}
		if ( isset( $attr['arrowsSize'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow:before" );
			$css->add_property( 'font-size', $css->render_range( $attr['arrowsSize'], 'Tablet' ) );
		}
	}

	$css->stop_media_query();
	$css->start_media_query( $media_query['mobile'] );
	// Mobile Styles.
	// Container.
	if ( isset( $attr['containerBorder'] ) ) {
		$container_border_width  = $attr['containerBorder']['borderWidth'];
		$container_border_radius = $attr['containerBorder']['borderRadius'];

		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Mobile'], 'px' ) );
	}

	if ( isset( $attr['containerMargin'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'margin', $css->render_spacing( $attr['containerMargin']['Mobile'], $attr['containerMargin']['unit'] ) );
	}

	if ( isset( $attr['containerPadding'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed" );
		$css->add_property( 'padding', $css->render_spacing( $attr['containerPadding']['Mobile'], $attr['containerPadding']['unit'] ) );
	}

	// Image.
	if ( isset( $attr['photoBorder'] ) ) {
		$container_border_width  = $attr['photoBorder']['borderWidth'];
		$container_border_radius = $attr['photoBorder']['borderRadius'];

		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Mobile'], 'px' ) );
	}

	if ( isset( $attr['photoMargin'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'margin', $css->render_spacing( $attr['photoMargin']['Mobile'], $attr['photoMargin']['unit'] ) );
	}

	if ( isset( $attr['photoPadding'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media" );
		$css->add_property( 'padding', $css->render_spacing( $attr['photoPadding']['Mobile'], $attr['photoPadding']['unit'] ) );
	}

	if ( isset( $attr['photoHoverBorder'] ) ) {
		$container_border_width  = $attr['photoHoverBorder']['borderWidth'];
		$container_border_radius = $attr['photoHoverBorder']['borderRadius'];

		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media:hover" );
		$css->add_property( 'border-width', $css->render_spacing( $container_border_width['Mobile'], 'px' ) );
		$css->add_property( 'border-radius', $css->render_spacing( $container_border_radius['Mobile'], 'px' ) );
	}

	if ( isset( $attr['photoHoverMargin'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-media:hover" );
		$css->add_property( 'margin', $css->render_spacing( $attr['photoHoverMargin']['Mobile'], $attr['photoHoverMargin']['unit'] ) );
	}

	// Caption.
	if ( isset( $attr['captionTypography'] ) ) {
		$css->set_selector( ".{$unique_id} .pbg-insta-feed-wrap .pbg-insta-feed-caption" );
		$css->render_typography( $attr['captionTypography'], 'Mobile' );
	}

	// Lightbox.
	if ( isset( $attr['clickAction'] ) && $attr['clickAction'] === 'lightBox' ) {
		if ( isset( $attr['lightBoxArrowsBorderRadius'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow" );
			$css->add_property( 'border-radius', $css->render_range( $attr['lightBoxArrowsBorderRadius'], 'Mobile' ) );
		}
		if ( isset( $attr['lightBoxArrowsPadding'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow" );
			$css->add_property( 'padding', $css->render_spacing( $attr['lightBoxArrowsPadding']['Mobile'], $attr['lightBoxArrowsPadding']['unit'] ) );
		}
		if ( isset( $attr['lightBoxArrowsSize'] ) ) {
			$css->set_selector( ".{$unique_id}.wp-block-premium-instagram-feed-posts .pbg-lightbox button.pbg-arrow .dashicons" );
			$css->add_property( 'font-size', $css->render_range( $attr['lightBoxArrowsSize'], 'Mobile' ) );
		}
	}

	// Carousel.
	if ( isset( $attr['layoutStyle'] ) && $attr['layoutStyle'] === 'carousel' ) {
		if ( isset( $attr['arrowsBorderRadius'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-arrow" );
			$css->add_property( 'border-radius', $css->render_range( $attr['arrowsBorderRadius'], 'Mobile' ) );
		}
		if ( isset( $attr['arrowsPadding'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-arrow" );
			$css->add_property( 'padding', $css->render_spacing( $attr['arrowsPadding']['Mobile'], $attr['arrowsPadding']['unit'] ) );
		}
		if ( isset( $attr['arrowsPosition'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-next" );
			$css->add_property( 'right', $css->render_range( $attr['arrowsPosition'], 'Mobile' ) );
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-prev" );
			$css->add_property( 'left', $css->render_range( $attr['arrowsPosition'], 'Mobile' ) );
		}
		if ( isset( $attr['arrowsVerticalPosition'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-next" );
			$css->add_property( 'top', $css->render_range( $attr['arrowsVerticalPosition'], 'Mobile' ) );
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow.slick-prev" );
			$css->add_property( 'top', $css->render_range( $attr['arrowsVerticalPosition'], 'Mobile' ) );
		}
		if ( isset( $attr['arrowsSize'] ) ) {
			$css->set_selector( ".{$unique_id} .pbg-insta-feed .slick-slider .slick-arrow:before" );
			$css->add_property( 'font-size', $css->render_range( $attr['arrowsSize'], 'Mobile' ) );
		}
	}

	$css->stop_media_query();

	return $css->css_output();
}

/**
 * Get Instagram Posts.
 *
 * @param string $access_token Instagram Access Token.
 *
 * @return array
 */
function pbg_get_instagram_posts( $access_token ) {
	$posts = array();
	if ( $access_token ) {
		$access_token = PBG_Blocks_Integrations::check_instagram_token( $access_token );
		$api_url      = sprintf( 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,username,timestamp,permalink,caption,children,thumbnail_url&limit=200&access_token=%s', $access_token );

		$response = wp_remote_get(
			$api_url,
			array(
				'timeout'   => 60,
				'sslverify' => false,
			)
		);

		if ( ! is_wp_error( $response ) ) {
			$response = wp_remote_retrieve_body( $response );
			$posts    = json_decode( $response, true );
		}
	}

	return $posts;
}

/**
 * Renders the `premium/instagram-feed-posts` block on server.
 *
 * @param array    $attributes The block attributes.
 * @param string   $content    The saved content.
 * @param WP_Block $block      The parsed block.
 *
 * @return string Returns the post content with the legacy widget added.
 */
function render_block_pbg_instagram_feed_posts( $attributes, $content, $block ) {
	$block_helpers = pbg_blocks_helper();
	$unique_id     = rand( 100, 10000 );
	$id            = 'premium-instagram-feed-posts-' . esc_attr( $unique_id );
	$block_id      = ( ! empty( $attributes['blockId'] ) ) ? $attributes['blockId'] : $id;
	$access_token  = ( ! empty( $block->context['accessToken'] ) ) ? $block->context['accessToken'] : '';
	$layout_style  = ( ! empty( $attributes['layoutStyle'] ) ) ? $attributes['layoutStyle'] : 'grid';
	$posts         = apply_filters( 'pbg_instagram_posts', pbg_get_instagram_posts( $access_token ) );

	add_filter(
		'premium_instagram_feed_localize_script',
		function ( $data ) use ( $block_id, $posts, $attributes ) {
			$data[ $block_id ] = array(
				'posts'      => $posts,
				'attributes' => $attributes,
			);
			return $data;
		}
	);

	// Block css file from "assets/css" after run grunt task.
	if ( 'carousel' === $layout_style ) {
		wp_enqueue_style(
			'premium-instagram-feed-carousel',
			PREMIUM_BLOCKS_URL . 'assets/css/minified/carousel.min.css',
			array(),
			PREMIUM_BLOCKS_VERSION,
			'all'
		);
	}

	$style_id = 'pbg-blocks-style' . esc_attr( $unique_id );
	if ( ! wp_style_is( $unique_id, 'enqueued' ) && apply_filters( 'Premium_BLocks_blocks_render_inline_css', true, 'column', $unique_id ) ) {
		$css = get_premium_instagram_feed_posts_css( $attributes, $block_id );
		if ( $block_helpers->should_render_inline( 'instagram-feed-posts', $unique_id ) ) {
				$block_helpers->add_custom_block_css( $css );
		} else {
			$block_helpers->render_inline_css( $css, 'instagram-feed-posts' );
		}
	};

	return $content;
}


/**
 * Register the my block block.
 *
 * @uses render_block_pbg_instagram_feed_posts()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_instagram_feed_posts() {
	register_block_type(
		PREMIUM_BLOCKS_PATH . 'blocks-config/instagram-feed-posts',
		array(
			'render_callback' => 'render_block_pbg_instagram_feed_posts',
		)
	);
}

register_block_pbg_instagram_feed_posts();


