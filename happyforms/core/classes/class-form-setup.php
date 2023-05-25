<?php

class HappyForms_Form_Setup {

	/**
	 * The singleton instance.
	 *
	 * @var HappyForms_Form_Setup
	 */
	private static $instance;

	/**
	 * The singleton constructor.
	 *
	 * @return HappyForms_Form_Setup
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	/**
	 * Hook into WordPress.
	 *
	 * @return void
	 */
	public function hook() {
		// Common form extensions
		add_filter( 'happyforms_meta_fields', array( $this, 'meta_fields' ) );
		add_filter( 'happyforms_update_form_data', array( $this, 'update_html_id_checkbox' ) );

		// Customizer form display
		add_filter( 'happyforms_part_class', array( $this, 'part_class_customizer' ) );

		// Reviewable form display
		add_filter( 'happyforms_form_id', array( $this, 'form_html_id' ), 10, 2 );
		add_action( 'happyforms_do_setup_control', array( $this, 'do_control' ), 10, 3 );
		add_action( 'happyforms_do_setup_control', array( $this, 'do_deprecated_control' ), 10, 4 );

		add_filter( 'happyforms_messages_fields', array( $this, 'get_messages_fields' ) );
		add_filter( 'happyforms_messages_controls', array( $this, 'messages_controls' ) );

		// Server-side hide-on-submit
		add_action( 'happyforms_submission_success', array( $this, 'submission_success' ), 10, 3 );
	}

	public function get_fields() {
		global $current_user;

		$fields = array(
			'redirect_url' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'spam_prevention' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'form_expiration_datetime' => array(
				'default' => date( 'Y-m-d H:i:s', time() + WEEK_IN_SECONDS ),
				'sanitize' => 'happyforms_sanitize_datetime',
			),
			'save_entries' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'captcha' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'captcha_site_key' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'captcha_secret_key' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'captcha_label' => array(
				'default' => __( 'Validate your submission', 'happyforms' ),
				'sanitize' => 'sanitize_text_field'
			),
			'preview_before_submit' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'use_html_id' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox',
			),
			'html_id' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field'
			),
			'add_submit_button_class' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'submit_button_html_class' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field'
			),
			'form_hide_on_submit' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
		);

		return $fields;
	}

	public function get_messages_fields( $fields ) {
		$messages_fields = array(
			'confirmation_message' => array(
				'default' => __( "We've got your submission.", 'happyforms' ),
				'sanitize' => 'esc_html',
			),
			'error_message' => array(
				'default' => __( "Bummer. The form can't be submitted. Please check for mistakes.", 'happyforms' ),
				'sanitize' => 'esc_html'
			),
			'submit_button_label' => array(
				'default' => __( 'Send', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
		);

		$fields = array_merge( $fields, $messages_fields );

		return $fields;
	}

	public function get_controls() {
		$controls = array(
			1100 => array(
				'type' => 'add_submit_button_class-checkbox',
				'label' => __( 'Add custom CSS classes to submit button', 'happyforms' ),
				'field' => 'add_submit_button_class',
			),
			1101 => array(
				'type' => 'add_submit_button_class-group_start',
				'trigger' => 'add_submit_button_class'
			),
			1102 => array(
				'type' => 'add_submit_button_class-text',
				'label' => __( 'Submit button CSS classes', 'happyforms' ),
				'autocomplete' => 'off',
				'field' => 'submit_button_html_class'
			),
			1103 => array(
				'type' => 'add_submit_button_class-group_end',
			),
			1200 => array(
				'type' => 'use_html_id-checkbox',
				'label' => __( 'Add custom HTML ID to form', 'happyforms' ),
				'field' => 'use_html_id',
			),
			1201 => array(
				'type' => 'use_html_id-group_start',
				'trigger' => 'use_html_id'
			),
			1202 => array(
				'type' => 'use_html_id-text',
				'label' => __( 'Form HTML ID', 'happyforms' ),
				'field' => 'html_id',
				'autocomplete' => 'off',
			),
			1203 => array(
				'type' => 'use_html_id-group_end',
			),
		);

		$controls = happyforms_safe_array_merge( array(), $controls );
		$controls = apply_filters( 'happyforms_setup_controls', $controls );
		ksort( $controls, SORT_NUMERIC );

		return $controls;
	}

	public function messages_controls( $controls ) {
		$message_controls = array(
			20 => array(
				'type' => 'escaped_text',
				'label' => __( 'Form is successfully submitted', 'happyforms' ),
				'field' => 'confirmation_message',
			),
			40 => array(
				'type' => 'escaped_text',
				'label' => __( "Form can’t be submitted", 'happyforms' ),
				'field' => 'error_message',
			),
			2020 => array(
				'type' => 'text',
				'label' => __( 'Submit form', 'happyforms' ),
				'field' => 'submit_button_label',
			),
		);

		$controls = happyforms_safe_array_merge( $controls, $message_controls );

		return $controls;
	}

	public function do_control( $control, $field, $index ) {
		$type = $control['type'];
		$path = happyforms_get_core_folder() . '/templates/customize-controls/setup';

		switch( $control['type'] ) {
			case 'editor':
			case 'checkbox':
			case 'text':
			case 'number':
			case 'radio':
			case 'select':
			case 'textarea':
			case 'buttongroup':
			case 'group_start':
			case 'group_end':
				require( "{$path}/{$type}.php" );
				break;
			default:
				break;
		}
	}

	public function do_deprecated_control( $control, $field, $index ) {
		$type = $control['type'];
		$path = happyforms_get_core_folder() . '/templates/customize-controls/setup';

		switch( $type ) {
			case 'use_html_id-checkbox':
			case 'use_html_id-group_start':
			case 'use_html_id-text':
			case 'use_html_id-group_end':
				$form = happyforms_customize_get_current_form();

				if ( happyforms_is_falsy( $form['use_html_id'] ) ) {
					break;
				}

				$true_type = str_replace( 'use_html_id-', '', $type );

				require( "{$path}/{$true_type}.php" );
				break;
			case 'add_submit_button_class-checkbox':
			case 'add_submit_button_class-group_start':
			case 'add_submit_button_class-text':
			case 'add_submit_button_class-group_end':
				$form = happyforms_customize_get_current_form();

				if ( happyforms_is_falsy( $form['add_submit_button_class'] ) ) {
					break;
				}

				$true_type = str_replace( 'add_submit_button_class-', '', $type );

				require( "{$path}/{$true_type}.php" );
				break;
			default:
				break;
		}
	}

	/**
	 * Filter: add fields to form meta.
	 *
	 * @hooked filter happyforms_meta_fields
	 *
	 * @param array $fields Current form meta fields.
	 *
	 * @return array
	 */
	public function meta_fields( $fields ) {
		$fields = array_merge( $fields, $this->get_fields() );

		return $fields;
	}

	/**
	 * Filter: append -editable class to part templates.
	 *
	 * @hooked filter happyforms_part_class
	 *
	 * @return void
	 */
	public function part_class_customizer( $classes ) {
		if ( ! is_customize_preview() ) {
			return $classes;
		}

		$classes[] = 'happyforms-block-editable happyforms-block-editable--part';

		return $classes;
	}

	public function form_html_id( $id, $form ) {
		$has_html_id_checkbox = ( metadata_exists( 'post', $form['ID'], '_happyforms_use_html_id' ) );

		if ( ! empty( $form['html_id'] ) ) {
			if ( ! $has_html_id_checkbox || $has_html_id_checkbox && happyforms_is_truthy( $form['use_html_id'] ) ) {
				$id = $form['html_id'];
			}
		}

		return esc_attr( $id );
	}

	/**
	 * Updates 'Use HTML ID' value to 1 if meta data for it does not exist
	 * but HTML ID input is not empty.
	 *
	 * @hooked filter `happyforms_update_form_data`
	 *
	 * @return array
	 */
	public function update_html_id_checkbox( $update_data ) {
		$has_html_id_checkbox = ( metadata_exists( 'post', $update_data['ID'], '_happyforms_use_html_id' ) );

		if ( ! $has_html_id_checkbox && ! empty( $update_data['_happyforms_html_id'] ) ) {
			$update_data['_happyforms_use_html_id'] = 1;
		}

		return $update_data;
	}

	public function submission_success( $submission, $form, $message ) {
		add_filter( 'happyforms_form_has_captcha', '__return_false', 10, 2 );

		add_action( 'happyforms_part_before', 'ob_start', 10, 0 );
		add_action( 'happyforms_part_after', 'ob_end_clean', 10, 0 );
		add_action( 'happyforms_form_submit_before', 'ob_start', 10, 0 );
		add_action( 'happyforms_form_submit_after', 'ob_end_clean', 10, 0 );
		add_action( 'happyforms_parts_after', array( $this, 'after_submission_links'), 20 );
	}

	public function after_submission_links ( $form ) {
		$template = apply_filters( 'happyforms_after_submission_links_template', '' );

		if ( '' === $template ) {
			return;
		}

		ob_start();
		require_once( $template );
		$html = ob_get_clean();

		echo $html;
	}
}

if ( ! function_exists( 'happyforms_get_setup' ) ):

function happyforms_get_setup() {
	return HappyForms_Form_Setup::instance();
}

endif;

happyforms_get_setup();
