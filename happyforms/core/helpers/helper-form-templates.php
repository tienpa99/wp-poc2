<?php

if ( ! function_exists( 'happyforms_form_field' ) ):
/**
 * Output a hidden field with the current form ID.
 *
 * @since 1.0
 *
 * @param int|string $id The id of the current form.
 *
 * @return void
 */
function happyforms_form_field( $id ) {
	$parameter = happyforms_get_message_controller()->form_parameter; ?>
	<input type="hidden" name="<?php echo esc_attr( $parameter ); ?>" value="<?php echo esc_attr( $id ); ?>" />
	<?php
}

endif;

if ( ! function_exists( 'happyforms_action_field' ) ):
/**
 * Output a form's action attribute.
 *
 * @since 1.0
 *
 * @return void
 */
function happyforms_action_field() {
	$action = happyforms_get_message_controller()->submit_action; ?>
	<input type="hidden" name="action" value="<?php echo esc_attr( $action ); ?>">
	<?php
}

endif;

if ( ! function_exists( 'happyforms_referer_field' ) ):
/**
 * Output a form's action attribute.
 *
 * @since 1.0
 *
 * @return void
 */
function happyforms_client_referer_field() {
	?>
	<input type="hidden" name="client_referer" value="">
	<?php
}

endif;

if ( ! function_exists( 'happyforms_referer_field' ) ):
/**
 * Output a form's action attribute.
 *
 * @since 1.0
 *
 * @return void
 */
function happyforms_current_post_id() {
	$post_id = is_singular() ? get_the_ID() : '';
	?>
	<input type="hidden" name="current_post_id" value="<?php echo $post_id; ?>">
	<?php
}

endif;

if ( ! function_exists( 'happyforms_honeypot' ) ) :

function happyforms_honeypot( $form ) {
	$controller = happyforms_get_form_controller();
	$names = array( 'single_line_text', 'multi_line_text', 'number' );
	$key = array_rand( $names, 1 );
	$name = $names[$key];

	if ( $controller->has_honeypot_protection( $form ) ) : ?>
	<input type="text" name="<?php echo $form['ID']; ?>-<?php echo $name; ?>" style="position:absolute;left:-5000px;" tabindex="-1" autocomplete="off"> <span class="screen-reader-text"><?php _e( 'Leave this field blank', 'happyforms' ); ?></span>
	<?php endif;
}

endif;

if ( ! function_exists( 'happyforms_submit' ) ):
/**
 * Output the form submit button.
 *
 * @since 1.0
 *
 * @param array $form Current form data.
 *
 * @return void
 */
function happyforms_submit( $form ) {
	$template_path = happyforms_get_core_folder() . '/templates/partials/form-submit.php';
	$template_path = apply_filters( 'happyforms_get_submit_template_path', $template_path, $form );
	include( $template_path );
}

endif;

if ( ! function_exists( 'happyforms_message_notices' ) ):
/**
 * Output notices for the current submission,
 * related to the form.
 *
 * @since 1.0
 *
 * @param string $location The notice location to display.
 *
 * @return void
 */
function happyforms_message_notices( $location = '' ) {
	$notices = happyforms_get_session()->get_messages( $location );
	$class = apply_filters( 'happyforms_message_notices_class', '' );

	happyforms_the_message_notices( $notices, $class );
}

endif;

if ( ! function_exists( 'happyforms_part_error_message' ) ):
/**
 * Output error message related to part.
 *
 * @since 1.0
 *
 * @param string $part_name Full part name to check for.
 *
 * @return void
 */
function happyforms_part_error_message( $part_name = '', $component = 0 ) {
	$notices = happyforms_get_session()->get_messages( $part_name );

	happyforms_the_part_error_message( $notices, $part_name, $component );
}

endif;

if ( ! function_exists( 'happyforms_the_message_notices' ) ):
/**
 * Output notices.
 *
 * @param string $notices A list of notices to display.
 *
 * @return void
 */
function happyforms_the_message_notices( $notices = array(), $class = '' ) {
	if ( ! empty( $notices ) ) : ?>
		<div class="happyforms-message-notices <?php echo $class; ?>">
			<?php foreach( $notices as $notice ): ?>
			<div class="happyforms-message-notice <?php echo esc_attr( $notice['type'] ); ?>">
				<h2><?php echo $notice['message']; ?></h2>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
	endif;
}

endif;

if ( ! function_exists( 'happyforms_the_part_error_message' ) ):
/**
 * Output part error message.
 *
 * @param string $notices A list of notices to display.
 * @param string $part_name Full name of the part to display notice for.
 *
 * @return void
 */
function happyforms_the_part_error_message( $notices = array(), $part_name = '', $component = 0 ) {
	if ( ! empty( $notices ) ) : ?>
		<?php
		$notice_id = "happyforms-error-{$part_name}";
		$notice_id = ( $component ) ? "{$notice_id}_{$component}" : $notice_id;
		$notice_class = 'happyforms-part-error-notice';
		$notice_class = ( $component ) ? "{$notice_class} {$notice_class}__{$component}" : $notice_class;
		?>
		<div class="<?php echo $notice_class; ?>" id="<?php echo $notice_id; ?>">
			<?php
			foreach( $notices as $notice ) :
				if ( is_array( $notice['message'] ) && isset( $notice['message'][$component] ) ) {
					$message = $notice['message'][$component];
				} elseif ( ! is_array( $notice['message'] ) && 0 === $component ) {
					$message = $notice['message'];
				} else {
					continue;
				}
			?>
				<p><?php echo happyforms_get_error_icon() ;?><span><?php echo $message; ?></span></p>
			<?php endforeach; ?>
		</div>
		<?php
	endif;
}

endif;

if ( ! function_exists( 'happyforms_get_error_icon' ) ):

function happyforms_get_error_icon() {
	$icon = '<svg role="img" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z" class=""></path></svg>';

	return $icon;
}

endif;

if ( ! function_exists( 'happyforms_print_part_description' ) ):
/**
 * Output description of the part.
 *
 * @since 1.1
 *
 * @param array  $part_data Form part data.
 *
 * @return void
 */
function happyforms_print_part_description( $part_data ) {
	 if ( empty( $part_data['description'] ) ) {
		return;
	 }

	?><span class="happyforms-part__description"><?php echo esc_html( $part_data['description'] ); ?></span><?php

}

endif;

if ( ! function_exists( 'happyforms_get_form_action' ) ):
/**
 * Returns the action for this form.
 *
 * @since 1.1
 *
 * @param array $form_id Current form id.
 *
 * @return string
 */
function happyforms_get_form_action( $form_id ) {
	/**
	 * Filter the action for this form.
	 *
	 * @since 1.1
	 *
	 * @param string $value The default action, an empty string.
	 *
	 * @return string The filtered action value.
	 */
	return apply_filters( 'happyforms_form_action', '', $form_id );
}

endif;

if ( ! function_exists( 'happyforms_form_action' ) ):
/**
 * Prints the action for this form.
 *
 * @since 1.1
 *
 * @param array $form_id Current form id.
 *
 * @return void
 */
function happyforms_form_action( $form_id ) {
	echo happyforms_get_form_action( $form_id );
}

endif;

if ( ! function_exists( 'happyforms_get_part_name' ) ):
/**
 * Returns the current form part field name.
 *
 * @since 1.1
 *
 * @param array $part_id Current part data.
 * @param array $form_id Current form data.
 *
 * @return string
 */
function happyforms_get_part_name( $part, $form ) {
	$name = $form['ID'] . '_' . $part['id'];

	/**
	 * Filter the field name for this form part.
	 *
	 * @since 1.1
	 *
	 * @param string $name The default name.
	 * @param array  $part Current part data.
	 * @param array  $form Current form data.
	 *
	 * @return string The filtered part name.
	 */
	return apply_filters( 'happyforms_part_name', $name, $part, $form );
}

endif;

if ( ! function_exists( 'happyforms_the_part_name' ) ):
/**
 * Output the current form part field name.
 *
 * @since 1.3
 *
 * @param array $part Current part data.
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_the_part_name( $part, $form ) {
	echo esc_attr( happyforms_get_part_name( $part, $form ) );
}

endif;

if ( ! function_exists( 'happyforms_get_part_value' ) ):
/**
 * Returns the default submission value for this form part.
 *
 * @since 1.4
 *
 * @param array  $part_id   Current part data.
 * @param array  $form_id   Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return string
 */
function happyforms_get_part_value( $part, $form, $component = false, $empty = '' ) {
	$default_value = happyforms_get_part_library()->get_part_default_value( $part );

	/**
	 * Filter the default submission value for this form part.
	 *
	 * @since 1.4
	 *
	 * @param string $value     The default value.
	 * @param array  $part      Current part data.
	 * @param array  $form      Current form data.
	 * @param string $component An optional part sub-component.
	 *
	 * @return string The filtered part name.
	 */
	$default_value = apply_filters( 'happyforms_part_value', $default_value, $part, $form );
	$part_name = happyforms_get_part_name( $part, $form );
	$session_value = happyforms_get_session()->get_value( $part_name );
	$value = ( false !== $session_value ) ? $session_value : $default_value;

	if ( false !== $component && is_array( $value ) ) {
		$value = isset( $value[$component] ) ? $value[$component] : $empty;
	}

	return $value;
}

endif;

if ( ! function_exists( 'happyforms_the_part_value' ) ):
/**
 * Output the default submission value for this form part.
 *
 * @since 1.4
 *
 * @param array  $part      Current part data.
 * @param array  $form      Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return void
 */
function happyforms_the_part_value( $part, $form, $component = false ) {
	$value = happyforms_get_part_value( $part, $form, $component );
	$value = apply_filters( 'happyforms_the_part_value', $value, $part, $form, $component );
	$value = htmlspecialchars( $value );

	echo $value;
}

endif;

if ( ! function_exists( 'happyforms_get_part_preview_value' ) ):
/**
 * Get the submitted part value in form preview context.
 *
 * @since 1.4
 *
 * @param array  $part      Current part data.
 * @param array  $form      Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return void
 */
function happyforms_get_part_preview_value( $part, $form ) {
	$part_class = happyforms_get_part_library()->get_part( $part['type'] );
	$part_value = happyforms_get_part_value( $part, $form );
	$validated_value = $part_class->validate_value( $part_value, $part, $form );
	$value = happyforms_stringify_part_value( $validated_value, $part, $form );
	$value = happyforms_get_message_part_value( $value, $part, 'preview' );

	return $value;
}

endif;

if ( ! function_exists( 'happyforms_the_part_preview_value' ) ):
/**
 * Output the submitted part value in form preview context.
 *
 * @since 1.4
 *
 * @param array  $part      Current part data.
 * @param array  $form      Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return void
 */
function happyforms_the_part_preview_value( $part, $form ) {
	echo happyforms_get_part_preview_value( $part, $form );
}

endif;

if ( ! function_exists( 'happyforms_get_part_attributes' ) ):
/**
 * Returns additional HTML attributes for this form part.
 *
 * @since 1.4
 *
 * @param array  $part_id   Current part data.
 * @param array  $form_id   Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return string
 */
function happyforms_get_part_attributes( $part, $form, $component = false ) {
	/**
	 * Filter the default submission value for this form part.
	 *
	 * @since 1.4
	 *
	 * @param string $value     The default value.
	 * @param array  $part      Current part data.
	 * @param array  $form      Current form data.
	 * @param string $component An optional part sub-component.
	 *
	 * @return string The filtered part attributes.
	 */
	return apply_filters( 'happyforms_part_attributes', array(), $part, $form, $component );
}

endif;

if ( ! function_exists( 'happyforms_the_part_attributes' ) ):
/**
 * Output additional HTML attributes for this form part.
 *
 * @since 1.4
 *
 * @param array  $part      Current part data.
 * @param array  $form      Current form data.
 * @param string $component An optional part sub-component.
 *
 * @return void
 */
function happyforms_the_part_attributes( $part, $form, $component = false ) {
	$attributes = happyforms_get_part_attributes( $part, $form, $component );
	$attributes = implode( ' ', $attributes );

	echo $attributes;
}

endif;

if ( ! function_exists( 'happyforms_get_form_title' ) ):
/**
 * Return the form title.
 *
 * @since 1.3
 *
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_get_form_title( $form ) {
	if ( empty( $form['post_title'] ) ) {
		return __( '(no title)', 'happyforms' );
	}

	return esc_html( $form['post_title'] );
}

endif;

if ( ! function_exists( 'happyforms_get_form_wrapper_id' ) ) :
/**
 * Get form wrapper's HTML ID.
 *
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_get_form_wrapper_id( $form ) {
	$id = 'happyforms-' . esc_attr( $form['ID'] );

	return apply_filters( 'happyforms_form_id', $id, $form );
}

endif;

if ( ! function_exists( 'happyforms_get_form_id' ) ):
/**
 * Get a form's html id.
 *
 * @param array $form    Current form data.
 *
 * @return string
 */
function happyforms_get_form_id( $form ) {
	/**
	 * Filter the id a form element.
	 *
	 * @param string $id    Current id.
	 * @param array $form   Current form data.
	 *
	 * @return string
	 */
	$id = 'happyforms-form-' . esc_attr( $form['ID'] );

	return $id;
}

endif;

if ( ! function_exists( 'happyforms_the_form_id' ) ):
/**
 * Output a form's html id.
 *
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_the_form_id( $form ) {
	echo happyforms_get_form_id( $form );
}

endif;

if ( ! function_exists( 'happyforms_get_form_container_id' ) ):
	/**
	 * Get a form's html id.
	 *
	 * @param array $form    Current form data.
	 *
	 * @return string
	 */
	function happyforms_get_form_container_id( $form ) {
		/**
		 * Filter the id a form container element.
		 *
		 * @param string $id    Current id.
		 * @param array $form   Current form data.
		 *
		 * @return string
		 */
		$id = 'happyforms-' . esc_attr( $form['ID'] );
		$id = apply_filters( 'happyforms_form_id', $id, $form );

		return $id;
	}

	endif;

	if ( ! function_exists( 'happyforms_the_form_container_id' ) ):
	/**
	 * Output a form's container html id.
	 *
	 * @param array $form Current form data.
	 *
	 * @return string
	 */
	function happyforms_the_form_container_id( $form ) {
		echo happyforms_get_form_container_id( $form );
	}

	endif;

if ( ! function_exists( 'happyforms_get_form_class' ) ):
/**
 * Get a form's html class.
 *
 * @since 1.3
 *
 * @param array $form    Current form data.
 *
 * @return string
 */
function happyforms_get_form_class( $form ) {
	/**
	 * Filter the list of classes of a form element.
	 *
	 * @since 1.3
	 *
	 * @param array $classes List of current classes.
	 * @param array $form    Current form data.
	 *
	 * @return string
	 */
	$classes = apply_filters( 'happyforms_form_class', array(), $form );
	$classes = implode( ' ', $classes );

	return $classes;
}

endif;

if ( ! function_exists( 'happyforms_the_form_class' ) ):
/**
 * Output a form's html class.
 *
 * @since 1.3
 *
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_the_form_class( $form ) {
	echo happyforms_get_form_class( $form );
}

endif;

if ( ! function_exists( 'happyforms_get_form_part' ) ):
/**
 * Get a part block markup.
 *
 * @since 1.3
 *
 * @param array $part Current part data.
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_get_form_part( $part, $form ) {
	$html = happyforms_get_part_library()->get_part_template( $part, $form );

	return $html;
}

endif;

if ( ! function_exists( 'happyforms_the_form_part' ) ):
/**
 * Output a part block.
 *
 * @since 1.3
 *
 * @param array $part Current part data.
 * @param array $form Current form data.
 *
 * @return void
 */
function happyforms_the_form_part( $part, $form ) {
	do_action( 'happyforms_part_before', $part, $form );
	echo happyforms_get_form_part( $part, $form );
	do_action( 'happyforms_part_after', $part, $form );
}

endif;

if ( ! function_exists( 'happyforms_get_part_class' ) ):
/**
 * Get a part wrapper's html classe.
 *
 * @since 1.3
 *
 * @param array $part    Current part data.
 * @param array $form    Current form data.
 *
 * @return string
 */
function happyforms_get_part_class( $part, $form ) {
	/**
	 * Filter the list of classes of a form part element.
	 *
	 * @since 1.3
	 *
	 * @param array $classes List of current classes.
	 * @param array $part    Current part data.
	 * @param array $form    Current form data.
	 *
	 * @return string
	 */
	$classes = apply_filters( 'happyforms_part_class', array(), $part, $form );
	$classes = implode( ' ', $classes );

	return $classes;
}

endif;

if ( ! function_exists( 'happyforms_the_part_class' ) ):
/**
 * Output a part wrapper's html class.
 *
 * @since 1.3
 *
 * @param array $part Current part data.
 * @param array $form Current form data.
 *
 * @return string
 */
function happyforms_the_part_class( $part, $form ) {
	echo happyforms_get_part_class( $part, $form );
}

endif;

if ( ! function_exists( 'happyforms_get_part_id' ) ):
/**
 * Get a part wrapper's id.
 *
 * @since 1.3
 *
 * @param string $part_id Current part id.
 * @param string $form_id Current form id.
 *
 * @return string
 */
function happyforms_get_part_id( $part_id, $form_id ) {
	$id = esc_attr( 'happyforms-' . $form_id . '_' . $part_id );

	/**
	 * Filter the html id of a form part element.
	 *
	 * @since 1.3
	 *
	 * @param string $id         Current part id.
	 * @param string $part_id    Current part id.
	 * @param string $form_id    Current form id.
	 *
	 * @return string
	 */
	$id = apply_filters( 'happyforms_part_id', $id, $part_id, $form_id );

	return $id;
}

endif;

if ( ! function_exists( 'happyforms_the_part_id' ) ):
/**
 * Outputs a part wrapper's id.
 *
 * @since 1.3
 *
 * @param array  $part Current part data.
 * @param array  $form Current form data.
 *
 * @return string
 */
function happyforms_the_part_id( $part, $form ) {
	echo happyforms_get_part_id( $part['id'], $form['ID'] );
}

endif;

if ( ! function_exists( 'happyforms_get_form_styles' ) ):
/**
 * Get a form's styles.
 *
 * @since 1.4.5
 *
 * @param array $form Current form data.
 *
 * @return array
 */
function happyforms_get_form_styles( $form ) {
	$styles = happyforms_get_styles()->form_html_styles( $form );

	/**
	 * Filter the css styles of a form.
	 *
	 * @since 1.4.5
	 *
	 * @param array $styles Current styles attributes.
	 * @param array $form   Current form data.
	 *
	 * @return array
	 */
	$styles = apply_filters( 'happyforms_form_styles', $styles, $form );

	return $styles;
}

endif;

if ( ! function_exists( 'happyforms_the_form_styles' ) ):
/**
 * Output a form's styles.
 *
 * @since 1.4.5
 *
 * @param array $form Current form data.
 *
 * @return array
 */
function happyforms_the_form_styles( $form ) {
	happyforms_get_form_assets()->print_frontend_styles( $form );
	$styles = happyforms_get_form_styles( $form );
	?>
	<!-- HappyForms CSS variables -->
	<style>
	#<?php happyforms_the_form_container_id( $form ); ?> {
		<?php foreach( $styles as $key => $style ) {
			$variable = $style['variable'];
			$value = $form[$key];
			$unit = isset( $style['unit'] ) ? $style['unit']: '';

			echo "{$variable}: {$value}{$unit};\n";
		} ?>
	}
	</style>
	<!-- End of HappyForms CSS variables -->
	<?php
}

endif;

if ( ! function_exists( 'happyforms_additional_css' ) ):
/**
 * Output a form's styles.
 *
 * @param array $form Current form data.
 */
function happyforms_additional_css( $form ) {
	$additional_css = happyforms_get_meta( $form['ID'], 'additional_css', true );
	$form_wrapper_id = happyforms_get_form_wrapper_id( $form );
	$additional_css = happyforms_get_prefixed_css( $additional_css, "#{$form_wrapper_id}" );
	?>
	<!-- HappyForms Additional CSS -->
	<style data-happyforms-additional-css>
	<?php echo $additional_css; ?>
	</style>
	<!-- End of HappyForms Additional CSS -->
	<?php
}

endif;

if ( ! function_exists( 'happyforms_get_form_property' ) ):
/**
 * Get a form property.
 *
 * @since 1.3
 *
 * @param array  $form Current form data.
 * @param string $key  The key to retrieve the style for.
 *
 * @return string
 */
function happyforms_get_form_property( $form, $key ) {
	if ( is_array( $form ) ) {
		$value = isset( $form[$key] ) ? $form[$key] : '';
		$value = is_numeric( $value ) ? intval( $value ) : $value;
	} else {
		$value = happyforms_get_meta( $form, $key, true );
	}

	return $value;
}

endif;

if ( ! function_exists( 'happyforms_get_part_data_attributes' ) ) :
/**
 * Get the html data- attributes of a form part element.
 *
 * @since 1.3
 *
 * @param array  $part Current part data.
 * @param array  $form Current form data.
 *
 * @return array
 */
function happyforms_get_part_data_attributes( $part, $form ) {
	/**
	 * Filter the html data- attributes of a form part element.
	 *
	 * @since 1.3
	 *
	 * @param array  $attributes Current part attributes.
	 * @param string $part_id    Current part data.
	 * @param string $form_id    Current form data.
	 *
	 * @return string
	 */
	$attributes = apply_filters( 'happyforms_part_data_attributes', array(), $part, $form );
	$data = array();

	foreach ( $attributes as $attribute => $value ) {
		$data[] = "data-{$attribute}=\"{$value}\"";
	}

	$data = implode( ' ', $data );

	return $data;
}

endif;

if ( ! function_exists( 'happyforms_the_part_data_attributes' ) ) :
/**
 * Output a part's html data- attributes
 *
 * @since 1.3
 *
 * @param array  $part Current part data.
 * @param array  $form Current form data.
 *
 * @return void
 */
function happyforms_the_part_data_attributes( $part, $form ) {
	echo happyforms_get_part_data_attributes( $part, $form );
}

endif;

if ( ! function_exists( 'happyforms_the_part_label' ) ) :
/**
 * Output a part label
 *
 * @since 1.3
 *
 * @param string $id   Current part id.
 * @param array  $part Current part data.
 * @param array  $form Current form data.
 *
 * @return void
 */
function happyforms_the_part_label( $part, $form ) {
	?>
	<div class="happyforms-part__label-container">
		<?php if ( 'hidden' !== $part['label_placement'] ) : ?>
		<label for="<?php happyforms_the_part_id( $part, $form ); ?>" class="happyforms-part__label">
			<span class="label"><?php echo esc_html( $part['label'] ); ?></span>
			<?php $is_required = isset( $part['required'] ) && 1 === intval( $part['required'] ); ?>
			<?php if ( $is_required || happyforms_is_preview_context() ): ?>
				<span class="happyforms-required"><?php echo happyforms_get_form_property( $form, 'required_field_label' ); ?></span>
			<?php endif; ?>
			<?php if ( ! $is_required || happyforms_is_preview_context() ): ?>
				<span class="happyforms-optional"><?php echo happyforms_get_form_property( $form, 'optional_part_label' ); ?></span>
			<?php endif; ?>
		</label>
		<?php endif; ?>
	</div>
	<?php
}

endif;

if ( ! function_exists( 'happyforms_get_part_options' ) ) :

function happyforms_get_part_options( $options, $part, $form ) {
	$options = apply_filters( 'happyforms_part_options', $options, $part, $form );

	return $options;
}

endif;

if ( ! function_exists( 'happyforms_get_months' ) ) :

function happyforms_get_months( $form = array() ) {
	$months = array(
		1 => __( 'January', 'happyforms' ),
		2 => __( 'February', 'happyforms' ),
		3 => __( 'March', 'happyforms' ),
		4 => __( 'April', 'happyforms' ),
		5 => __( 'May', 'happyforms' ),
		6 => __( 'June', 'happyforms' ),
		7 => __( 'July', 'happyforms' ),
		8 => __( 'August', 'happyforms' ),
		9 => __( 'September', 'happyforms' ),
		10 => __( 'October', 'happyforms' ),
		11 => __( 'November', 'happyforms' ),
		12 => __( 'December', 'happyforms' )
	);

	$months = apply_filters( 'happyforms_get_months', $months, $form );

	return $months;
}

endif;

if ( ! function_exists( 'happyforms_get_days' ) ) :

function happyforms_get_days() {
	$days = apply_filters( 'happyforms_get_days', range( 1, 31 ) );

	return $days;
}

endif;

if ( ! function_exists( 'happyforms_get_site_date_format' ) ) :

function happyforms_get_site_date_format() {
	$site_date_format = get_option( 'date_format' );

	$format = 'day_first';

	if ( 0 === strpos( $site_date_format, 'F' ) || 0 === strpos( $site_date_format, 'm' ) ||
		0 === strpos( $site_date_format, 'M' ) || 0 === strpos( $site_date_format, 'n' ) ) {
		$format = 'month_first';
	}

	return apply_filters( 'happyforms_date_part_format', $format );
}

endif;

if ( ! function_exists( 'happyforms_select' ) ) :

function happyforms_select( $options, $part, $form, $placeholder = '' ) {
	$value = happyforms_get_part_value( $part, $form );
	$placeholder_value = ( isset( $part['placeholder'] ) ) ? $part['placeholder'] : $placeholder;

	foreach( $options as $option_value => $option ) {
		if ( isset( $option['is_default'] ) && 1 == $option['is_default'] ) {
			$value = $option_value;
		}
	}

	include( happyforms_get_core_folder() . '/templates/partials/happyforms-select.php' );
}

endif;

if ( ! function_exists( 'happyforms_get_steps' ) ) :

function happyforms_get_steps( $form ) {
	$steps = happyforms_get_form_controller()->get_default_steps( $form );
	$steps = apply_filters( 'happyforms_get_steps', $steps, $form );
	ksort( $steps );
	$steps = array_values( $steps );

	return $steps;
}

endif;

if ( ! function_exists( 'happyforms_get_current_step' ) ) :

function happyforms_get_current_step( $form, $index = false ) {
	$steps = happyforms_get_steps( $form );
	$session = happyforms_get_session();
	$step = $session->current_step();

	if ( isset( $steps[$step] ) ) {
		return $index ? $step : $steps[$step];
	}

	return false;
}

endif;

if ( ! function_exists( 'happyforms_get_next_step' ) ) :

function happyforms_get_next_step( $form, $index = false ) {
	$steps = happyforms_get_steps( $form );
	$session = happyforms_get_session();
	$step = $session->current_step() + 1;

	if ( isset( $steps[$step] ) ) {
		return $index ? $step : $steps[$step];
	}

	return false;
}

endif;

if ( ! function_exists( 'happyforms_get_last_step' ) ) :

function happyforms_get_last_step( $form, $index = false ) {
	$steps = happyforms_get_steps( $form );
	$last_step = count( $steps ) - 1;

	return $index ? $last_step : $steps[$last_step];
}

endif;

if ( ! function_exists( 'happyforms_is_last_step' ) ) :

function happyforms_is_last_step( $form, $step = false ) {
	$steps = happyforms_get_steps( $form );
	$step = false !== $step ? $step : happyforms_get_current_step( $form );
	$is_last = $steps[count( $steps ) - 1] === $step;

	return $is_last;
}

endif;

if ( ! function_exists( 'happyforms_step_field' ) ) :

function happyforms_step_field( $form ) {
	$session = happyforms_get_session();
	$step = $session->current_step();
	?>
	<input type="hidden" name="happyforms_step" value="<?php echo $step; ?>" />
	<?php
}

endif;

if ( ! function_exists( 'happyforms_is_falsy' ) ) :

function happyforms_is_falsy( $value ) {
	$falsy = empty( $value ) || 'false' === $value || 0 === intval( $value );

	return $falsy;
}

endif;

if ( ! function_exists( 'happyforms_is_truthy' ) ) :

function happyforms_is_truthy( $value ) {
	$truthy = ! happyforms_is_falsy( $value );

	return $truthy;
}

endif;

if ( ! function_exists( 'happyforms_get_rating_icons' ) ) :

function happyforms_get_rating_icons( $part ) {
	$icons = array( '😢', '😟', '😐', '🙂',  '😍' );

	if ( 'yesno' === $part['rating_type'] ) {
		switch ( $part['rating_visuals'] ) {
			case 'smileys':
				$icons = array( '😟', '😁' );
				break;
			case 'thumbs':
				$icons = array( '👎', '👍' );
				break;
		}
	}

	return $icons;
}

endif;

if ( ! function_exists( 'happyforms_get_narrative_format' ) ) :

function happyforms_get_narrative_format( $format ) {
	$format = preg_replace( '/\[([^\/\]]*)\]/m', '%s', $format );

	return $format;
}

endif;

if ( ! function_exists( 'happyforms_get_narrative_tokens' ) ) :

function happyforms_get_narrative_tokens( $format, $with_placeholders = false ) {
	$matches = preg_match_all( '/\[([^\/\]]*)\]/m', $format, $tokens );

	if ( ! $matches ) {
		return array();
	}

	$tokens = $tokens[1];

	if ( ! $with_placeholders ) {
		$tokens = array_fill( 0, count( $tokens ), '' );
	}

	return $tokens;
}

endif;

if ( ! function_exists( 'happyforms_get_form_attributes' ) ):

function happyforms_get_form_attributes( $form ) {
	$attributes = apply_filters( 'happyforms_get_form_attributes', array(
		'novalidate' => 'true'
	), $form );

	return $attributes;
}

endif;

if ( ! function_exists( 'happyforms_the_form_attributes' ) ):

function happyforms_the_form_attributes( $form ) {
	$attributes = happyforms_get_form_attributes( $form );
	$html_attributes = array();

	foreach( $attributes as $attribute => $value ) {
		$value = esc_attr( $value );
		$html_attributes[] = "{$attribute}=\"{$value}\"";
	}

	$html_attributes = implode( ' ', $html_attributes );
	echo $html_attributes;
}

endif;

if ( ! function_exists( 'happyforms_get_shortcode' ) ):

function happyforms_get_shortcode( $form_id = 'ID' ) {
	$shortcode = "[form id=\"{$form_id}\"]";
	$shortcode = apply_filters( 'happyforms_get_shortcode', $shortcode, $form_id );

	return $shortcode;
}

endif;

if ( ! function_exists( 'happyforms_get_previous_part' ) ):

function happyforms_get_previous_part( $part, $form ) {
	$part_id = $part['id'];
	$parts = array_values( $form['parts'] );
	$part_ids = wp_list_pluck( $parts, 'id' );
	$part_index = array_search( $part_id, $part_ids );
	$part_index = $part_index - 1;

	if ( isset( $parts[$part_index] ) ) {
		return $parts[$part_index];
	}

	return false;
}

endif;

if ( ! function_exists( 'happyforms_get_next_part' ) ):

function happyforms_get_next_part( $part, $form ) {
	$part_id = $part['id'];
	$parts = array_values( $form['parts'] );
	$part_ids = wp_list_pluck( $parts, 'id' );
	$part_index = array_search( $part_id, $part_ids );
	$part_index = $part_index + 1;

	if ( isset( $parts[$part_index] ) ) {
		return $parts[$part_index];
	}

	return false;
}

endif;

if ( ! function_exists( 'happyforms_get_form_partial' ) ):

function happyforms_get_form_partial( $partial_name, $form ) {
	$file = happyforms_get_include_folder() . '/templates/partials/' . $partial_name . '.php';

	if ( ! file_exists( $file ) ) {
		$file = happyforms_get_core_folder() . '/templates/partials/' . $partial_name . '.php';
	}

	ob_start();
	require( $file );
	$html = ob_get_clean();

	return $html;
}

endif;

if ( ! function_exists( 'happyforms_is_stepping' ) ):

function happyforms_is_stepping() {
	$stepping = defined( 'HAPPYFORMS_STEPPING' ) && HAPPYFORMS_STEPPING;

	return $stepping;
}

endif;

if ( ! function_exists( 'happyforms_get_part_states' ) ):
/**
 * Output notices for the current submission,
 * related to the form as a whole or specific parts.
 *
 * @since 1.0
 *
 * @param string $location The notice location to display.
 *
 * @return void
 */
function happyforms_get_part_states( $location = '' ) {
	$states = happyforms_get_session()->get_states( $location );

	return $states;
}

endif;

if ( ! function_exists( 'happyforms_get_prefixed_css' ) ):
/**
 * Prefix CSS selectors with specified prefix.
 *
 * @param string $css CSS to be prefixed.
 * @param string $prefix Prefix to add in front of each selector.
 *
 * @return string
 */
function happyforms_get_prefixed_css( $css, $prefix ) {
	$css = preg_replace( '!/\*.*?\*/!s', '', $css );
	$parts = explode( '}', $css );
	$is_media_query = false;

	foreach ( $parts as &$part ) {
		$part = trim( $part );

		if ( empty( $part ) ) {
			continue;
		}

		$part_contents = explode( '{', $part );

		if ( 2 === substr_count( $part, '{' ) ) {
			$media_query = $part_contents[0] . '{';
			$part_contents[0] = $part_contents[1];
			$is_media_query = true;
		}

		$sub_parts = explode( ',', $part_contents[0] );

		foreach ( $sub_parts as &$sub_part ) {
			$sub_part = $prefix . ' ' . trim( $sub_part );
		}

		if ( 2 === substr_count( $part, '{' ) ) {
			$part = $media_query . "\n" . implode( ', ', $sub_parts ) . '{'. $part_contents[2];
		} else if ( empty($part[0] ) && $is_media_query ) {
			$is_media_query = false;
			$part = implode( ', ', $sub_parts ). '{'. $part_contents[2]. "}\n";
		} else {
			if ( isset( $part_contents[1] ) ) {
				$part = implode( ', ', $sub_parts ) . '{'. $part_contents[1];
			}
		}
	}

	return preg_replace( '/\s+/',' ', implode( '} ', $parts ) );
}

endif;

if ( ! function_exists( 'happyforms_parts_autocorrect_attribute' ) ):

function happyforms_parts_autocorrect_attribute( $part ) {
	if ( apply_filters( 'happyforms_add_autocorrect_attribute', true, $part ) ) {
		echo 'autocorrect="off"';
	}
}

endif;

if ( ! function_exists( 'happyforms_get_client_ip' ) ) :

function happyforms_get_client_ip() {
	$keys = array( 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' );

	foreach( $keys as $key ) {
		if ( isset( $_SERVER[$key] ) && ! empty( $_SERVER[$key] ) ) {
			return $_SERVER[$key];
		}
	}

	return '';
}

endif;
