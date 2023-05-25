<?php

class HappyForms_Part_Media extends HappyForms_Form_Part {

	public $type = 'media';

	public function __construct() {
		$this->label = __( 'Media', 'happyforms' );
		$this->description = __( 'For adding a single image, video, animated gif or audio clip.', 'happyforms' );

		add_filter( 'happyforms_message_part_visible', array( $this, 'message_part_visible' ), 10, 2 );
		add_filter( 'happyforms_email_part_visible', array( $this, 'email_part_visible' ), 10, 3 );
		add_filter( 'happyforms_email_part_label', array( $this, 'email_part_label' ), 10, 4 );
		add_filter( 'happyforms_email_part_value', array( $this, 'email_part_value' ), 10, 4 );
		add_filter( 'happyforms_csv_part_visible', array( $this, 'csv_part_visible' ), 10, 2 );
	}

	/**
	 * Get all part meta fields defaults.
	 *
	 * @since 1.0.0.
	 *
	 * @return array
	 */
	public function get_customize_fields() {
		$fields = array(
			'type' => array(
				'default' => $this->type,
				'sanitize' => 'sanitize_text_field',
			),
			'label' => array(
				'default' => __( '', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'label_placement' => array(
				'default' => 'show',
				'sanitize' => 'sanitize_text_field'
			),
			'description' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field'
			),
			'width' => array(
				'default' => 'full',
				'sanitize' => 'sanitize_key'
			),
			'css_class' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field'
			),
			'attachment' => array(
				'default' => 0,
				'sanitize' => 'intval',
			),
			'required' => array(
				'default' => 0,
				'sanitize' => 'intval',
			),
		);

		return happyforms_get_part_customize_fields( $fields, $this->type );
	}

	/**
	 * Get template for part item in customize pane.
	 *
	 * @since 1.0.0.
	 *
	 * @return string
	 */
	public function customize_templates() {
		$template_path = happyforms_get_core_folder() . '/templates/parts/customize-media.php';
		$template_path = happyforms_get_part_customize_template_path( $template_path, $this->type );

		require_once( $template_path );
	}

	/**
	 * Get front end part template with parsed data.
	 *
	 * @since 1.0.0.
	 *
	 * @param array	$part_data 	Form part data.
	 * @param array	$form_data	Form (post) data.
	 *
	 * @return string	Markup for the form part.
	 */
	public function frontend_template( $part_data = array(), $form_data = array() ) {
		$part = wp_parse_args( $part_data, $this->get_customize_defaults() );
		$form = $form_data;

		include( happyforms_get_core_folder() . '/templates/parts/frontend-media.php' );
	}

	/**
	 * Get all possible messages definitions.
	 *
	 * @since 1.0.0.
	 *
	 * @return array	Associative array, specifying message type and copy.
	 */
	public function get_message_definitions() {
		return array();
	}

	/**
	 * Sanitize submitted value before storing it.
	 *
	 * @since 1.0.0.
	 *
	 * @param array $part_data Form part data.
	 *
	 * @return string
	 */
	public function sanitize_value( $part_data = array(), $form_data = array(), $request = array() ) {
		$sanitized_value = $this->get_default_value( $part_data );
		$part_name = happyforms_get_part_name( $part_data, $form_data );

		if ( isset( $request[$part_name] ) ) {
			$sanitized_value = sanitize_text_field( $request[$part_name] );
		}

		return $sanitized_value;
	}

	/**
	 * Validate value before submitting it.
	 *
	 * @since 1.0.0.
	 *
	 * @param array $part Form part data.
	 * @param string $value Submitted value.
	 *
	 * @return string
	 */
	public function validate_value( $value, $part = array(), $form = array() ) {
		$validated_value = esc_attr( $value );

		return $validated_value;
	}

	public function message_part_visible( $visible, $part ) {
		if ( $this->type === $part['type'] ) {
			$visible = false;
		}

		return $visible;
	}

	public function email_part_visible( $visible, $part, $form ) {
		if ( $this->type === $part['type'] ) {
			$visible = true;
		}

		return $visible;
	}

	public function csv_part_visible( $visible, $part ) {
		if ( $this->type === $part['type'] ) {
			$visible = false;
		}

		return $visible;
	}

	public function email_part_value( $value, $message, $part, $form ) {
		if ( $this->type !== $part['type'] ) {
			return $value;
		}

		if ( 0 === $part['attachment'] ) {
			return $value;
		}

		$attachment = get_posts( array(
			'post_type' => 'attachment',
			'p' => $part['attachment'],
		) );

		if ( ! empty( $attachment ) ) {
			$attachment = $attachment[0];
			$src = wp_get_attachment_url( $attachment->ID );
			$filename = basename( $src );

			$value = "<a href={$src}>{$filename}</a>";
		}


		return $value;
	}

	public function email_part_label( $label, $message, $part, $form ) {
		if ( $this->type === $part['type'] ) {
			if ( '' === $part['label'] ) {
				$label = '';
			}
		}

		return $label;
	}

	/**
	 * Enqueue scripts in customizer area.
	 *
	 * @since 1.0.0.
	 *
	 * @param array	List of dependencies.
	 *
	 * @return void
	 */
	public function customize_enqueue_scripts( $deps = array() ) {
		wp_enqueue_media();

		wp_enqueue_script(
			'happyforms-media-handle',
			happyforms_get_plugin_url() . 'core/assets/js/customize/media.js',
			array( 'happyforms-customize' ),
			happyforms_get_version(),
			true
		);

		wp_enqueue_script(
			'part-media',
			happyforms_get_plugin_url() . 'core/assets/js/parts/part-media.js',
			$deps, happyforms_get_version(), true
		);
	}
}
