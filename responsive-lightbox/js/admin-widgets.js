( function( window, $ ) {

	/* Gallery widget */
	var rl_galleries = [];
	var rl_gallery_frames = [];
	var rl_gallery_ids = [];
	var rl_gallery_images = [];

	/* Single image widget */
	var rl_image_frame;
	var rl_image_container;
	var rl_image_input;

	// ready event
	$( function() {
		init_rl_gallery_widget();
		init_rl_image_widget();
	} );

	// custom events trigger
	$( document ).on( 'widget-updated widget-added', function() {
		init_rl_gallery_widget();
		init_rl_image_widget();
	} );

	function init_rl_gallery_widget() {
		rl_galleries = $( '.rl-gallery-widget' );

		if ( rl_galleries.length > 0 ) {
			$.each( rl_galleries, function( index, gallery ) {
				var gallery_id_attr = $( gallery ).attr( 'id' );
				var gallery_id_arr = gallery_id_attr.match( /\-\d*\-/ );

				if ( gallery_id_arr != null ) {
					var gallery_id = gallery_id_arr.shift().replace( /-/g, '' );

					rl_gallery_frames[gallery_id] = [ ];
					rl_gallery_ids[gallery_id] = $( gallery ).find( '.rl-gallery-ids' );
					rl_gallery_images[gallery_id] = $( gallery ).find( '.rl-gallery-images' );

					// image ordering
					rl_gallery_images[gallery_id].sortable( {
						items: 'li.rl-gallery-image',
						cursor: 'move',
						scrollSensitivity: 40,
						forcePlaceholderSize: true,
						forceHelperSize: false,
						helper: 'clone',
						opacity: 0.65,
						placeholder: 'rl-gallery-sortable-placeholder',
						start: function( event, ui ) {
							ui.item.css( 'border-color', '#f6f6f6' );
						},
						stop: function( event, ui ) {
							ui.item.removeAttr( 'style' );
						},
						update: function( event, ui ) {
							var attachment_ids = [ ];

							$( rl_gallery_images[gallery_id] ).find( 'li.rl-gallery-image' ).each( function() {
								var attachment_id = jQuery( this ).attr( 'data-attachment_id' );

								attachment_ids.push( attachment_id );
							} );

							rl_gallery_ids[gallery_id].val( attachment_ids.join( ',' ) );
							rl_gallery_ids[gallery_id].trigger( 'change' );
						}
					} );

					// removing images
					$( rl_gallery_images[gallery_id] ).on( 'click', '.rl-gallery-image-remove', function() {
						var li = $( this ).closest( 'li.rl-gallery-image' );
						var attachment_ids = rl_gallery_ids[gallery_id].val().split( ',' ).map( function( el ) {
							return parseInt( el );
						} );

						// remove id
						attachment_ids = _.without( attachment_ids, parseInt( li.data( 'attachment_id' ) ) );

						// remove attachment
						li.remove();

						// update attachment ids
						rl_gallery_ids[gallery_id].val( attachment_ids.join( ',' ) );
						rl_gallery_ids[gallery_id].trigger( 'change' );

						return false;
					} );
				}
			} );
		}

		// open the modal on click
		$( '.rl-gallery-widget-select' ).on( 'click', function( event ) {
			event.preventDefault();

			var gallery = $( this ).closest( '.rl-gallery-widget' );
			var gallery_id = 0;
			var gallery_id_attr = $( gallery ).attr( 'id' );
			var gallery_id_arr = gallery_id_attr.match( /\-\d*\-/ );

			if ( gallery_id_arr != null ) {
				gallery_id = gallery_id_arr.shift().replace( /-/g, '' );

				var attachment_ids = rl_gallery_ids[gallery_id].val();

				// if the media frame already exists, reopen it.
				if ( rl_gallery_frames[gallery_id].length != 0 ) {
					rl_gallery_frames[gallery_id].open();

					return;
				}

				// create the media frame.
				rl_gallery_frames[gallery_id] = wp.media( {
					// Set the title of the modal.
					title: rlArgsWidgets.textSelectImages,
					multiple: true,
					button: {
						text: rlArgsWidgets.textUseImages
					},
					library: { type: 'image' },
					multiple: true
				} );

				rl_gallery_frames[gallery_id].on( 'open', function() {
					var selection = rl_gallery_frames[gallery_id].state().get( 'selection' );
					var attachment_ids = rl_gallery_ids[gallery_id].val().split( ',' );

					$.each( attachment_ids, function() {
						let _this = this;

						if ( ! isNaN( parseFloat( _this ) ) && isFinite( _this ) ) {
							attachment = wp.media.attachment( this );
							attachment.fetch();
							selection.add( attachment ? [ attachment ] : [ ] );
						}
					} );
				} );

				// when an image is selected, run a callback.
				rl_gallery_frames[gallery_id].on( 'select', function() {
					var selection = rl_gallery_frames[gallery_id].state().get( 'selection' );
					var selected_ids = [ ];
					var attachment_ids = rl_gallery_ids[gallery_id].val().split( ',' ).map( function( el ) {
						return parseInt( el );
					} );

					if ( selection ) {
						selection.map( function( attachment ) {
							if ( attachment.id ) {
								selected_ids.push( attachment.id );

								// is image already in gallery?
								if ( $.inArray( attachment.id, attachment_ids ) !== -1 )
									return;

								attachment_ids.push( attachment.id );
								attachment = attachment.toJSON();

								// is preview size available?
								if ( attachment.sizes && attachment.sizes['thumbnail'] ) {
									attachment.url = attachment.sizes['thumbnail'].url;
								}

								rl_gallery_images[gallery_id].append( '\
									<li class="rl-gallery-image" data-attachment_id="' + attachment.id + '">\
										<div class="rl-gallery-inner"><div class="centered"><img src="' + attachment.url + '" /></div></div>\
										<div class="rl-gallery-actions"><a href="#" class="rl-gallery-image-remove dashicons-before dashicons-no" title="' + rlArgsWidgets.textRemoveImage + '"></a></div>\
									</li>'
									);
							}
						} );
					}

					// assign copy of attachments ids
					var copy = attachment_ids;

					for ( var i = 0; i < attachment_ids.length; i++ ) {
						// unselected image?
						if ( $.inArray( attachment_ids[i], selected_ids ) === -1 ) {
							$( rl_gallery_images[gallery_id] ).find( 'li.rl-gallery-image[data-attachment_id="' + attachment_ids[i] + '"]' ).remove();

							copy = _.without( copy, attachment_ids[i] )
						}
					}

					rl_gallery_ids[gallery_id].val( _.uniq( copy ).join( ',' ) );
					rl_gallery_ids[gallery_id].trigger( 'change' );
				} );

				// finally, open the modal.
				rl_gallery_frames[gallery_id].open();
			}
		} );
	}

	function init_rl_image_widget() {
		$( '.rl-image-widget-select' ).on( 'click', function( event ) {
			var _this = $( this );

			rl_image_container = _this.parent().find( '.rl-image-widget-content' );
			rl_image_input = _this.parent().find( '.rl-image-widget-image-id' );

			event.preventDefault();

			// create a new media frame
			rl_image_frame = wp.media( {
				title: rlArgsWidgets.textSelectImage,
				button: {
					text: rlArgsWidgets.textUseImage
				},
				library: { type: 'image' },
				multiple: false
			} );

			// when an image is selected in the media frame...
			rl_image_frame.on( 'select', function() {
				// get media attachment details from the frame state
				var attachment = rl_image_frame.state().get( 'selection' ).first().toJSON();

				// send the attachment URL to our custom image input field.
				rl_image_container.html( '<img src="' + attachment.url + '" alt="" />' );

				// send the attachment id to our hidden input
				rl_image_input.val( attachment.id );
				rl_image_input.trigger( 'change' );
			} );

			// when an image is already selected in the media frame...
			rl_image_frame.on( 'open', function() {
				var selection = rl_image_frame.state().get( 'selection' );

				// get current image
				var attachment = wp.media.attachment( rl_image_input.val() );

				attachment.fetch();

				// preselect in media frame
				selection.add( attachment ? [ attachment ] : [ ] );
			} );

			rl_image_frame.open();
		} );

		$( '.rl-image-link-to' ).on( 'change', function() {
			if ( $( this ).val() === 'custom' )
				$( this ).parent().next().slideDown( 'fast' );
			else
				$( this ).parent().next().slideUp( 'fast' );
		} );
	}

} )( this, jQuery );