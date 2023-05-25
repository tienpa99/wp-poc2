<?php

class HappyForms_Form_Part_Library {

	private static $instance;

	private $parts = array();

	private $standard_parts = array(
		1 => array( 'HappyForms_Part_SingleLineText', 'class-part-single-line-text' ),
		2 => array( 'HappyForms_Part_MultiLineText', 'class-part-multi-line-text' ),
		3 => array( 'HappyForms_Part_Email', 'class-part-email' ),
		5 => array( 'HappyForms_Part_Radio', 'class-part-radio' ),
		6 => array( 'HappyForms_Part_Checkbox', 'class-part-checkbox' ),
		8 => array( 'HappyForms_Part_Select', 'class-part-select' ),
		9 => array( 'HappyForms_Part_Number', 'class-part-number' ),
		100 => array( 'HappyForms_Part_LayoutDrawerGroup', 'class-part-layout-drawer-group' ),
		101 => array( 'HappyForms_Part_LayoutTitle', 'class-part-layout-title' ),
		102 => array( 'HappyForms_Part_Placeholder', 'class-part-placeholder' ),
		103 => array( 'HappyForms_Part_Media', 'class-part-media' ),
		105 => array( 'HappyForms_Part_Divider', 'class-part-divider' ),
	);

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	public function __construct() {
		require_once( happyforms_get_core_folder() . '/classes/class-form-part.php' );

		foreach ( $this->standard_parts as $index => $part ) {
			list( $class, $file ) = $part;

			require_once( happyforms_get_core_folder() . "/classes/parts/{$file}.php" );
			
			$this->register_part( $class, $index );
		}
	}

	/**
	 * Hook into WordPress.
	 *
	 * @since 1.0.0.
	 *
	 * @return void
	 */
	public function hook() {
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'customize_templates' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_enqueue_scripts' ) );
		add_filter( 'happyforms_part_class', array( $this, 'html_part_class' ), 10, 3 );
		add_filter( 'happyforms_part_data_attributes', array( $this, 'html_part_data_attributes' ), 10, 3 );
	}

	/**
	 * Register individual parts.
	 *
	 * @since 1.0.0.
	 *
	 * @param object $part Part object.
	 *
	 * @return void
	 */
	public function register_part( $part_class, $index = -1 ) {
		$part = new $part_class();

		if ( isset( $this->parts[$index] ) ) {
			$existing_part_class = get_class( $this->parts[$index] );
			throw new Exception( __( "{$existing_part_class} already registered at index {$index}", 'happyforms' ) );
		}

		$this->parts[$index] = $part;

		ksort( $this->parts, SORT_NUMERIC );
	}

	public function deregister_part( $type ) {
		foreach ( $this->parts as $index => $part ) {
			if ( $type === $part->type ) {
				unset( $this->parts[$index] );
			}
		}
	}

	/**
	 * Get all form parts.
	 *
	 * @since 1.0.0.
	 *
	 * @return array Parts data.
	 */
	public function get_parts() {
		$parts_data = array();

		foreach ( $this->parts as $part ) {
			$part_data = get_object_vars( $part );
			$part_data['defaults'] = $part->get_customize_defaults();
			$parts_data[$part->type] = $part_data;
		}

		return $parts_data;
	}

	/**
	 * Get part by type.
	 *
	 * @since 1.0.0.
	 *
	 * @param string $type Part type.
	 *
	 * @return array|bool
	 */
	public function get_part( $type ) {
		$part = false;

		foreach ( $this->parts as $_part ) {
			if ( $type === $_part->type ) {
				$part = $_part;
			}
		}

		$part = apply_filters( 'happyforms_library_get_part_' . $type, $part );

		return $part;
	}

	/**
	 * Print each part's Backbone templates for use in Customizer interface.
	 *
	 * @since 1.0.0.
	 *
	 * @return void
	 */
	public function customize_templates() {
		foreach ( $this->parts as $part ) {
			$part->customize_templates();
		}
	}

	/**
	 * Call `customize_enqueue_scripts` method in all parts to add scripts to Customizer area.
	 *
	 * @since 1.0.0.
	 *
	 * @return void
	 */
	public function customize_enqueue_scripts() {
		wp_register_script(
			'part-field-choice',
			happyforms_get_plugin_url() . 'core/assets/js/parts/part-field-choice.js',
			'happyforms-customize', happyforms_get_version(), true
		);

		$deps = array( 'happyforms-customize', 'part-field-choice' );

		foreach ( $this->parts as $part ) {
			$part->customize_enqueue_scripts( $deps );
		}
	}

	public function get_part_template( $part_data = array(), $form_data = array() ) {
		if ( isset( $part_data['type'] ) ) {
			$part = $this->get_part( $part_data['type'] );

			if ( false !== $part ) {
				ob_start();
				$part->frontend_template( $part_data, $form_data );
				return ob_get_clean();
			}
		}

		return '';
	}

	public function get_part_default_value( $part_data = array() ) {
		if ( isset( $part_data['type'] ) ) {
			$part = $this->get_part( $part_data['type'] );

			return $part->get_default_value( $part_data );
		}

		return '';
	}

	/**
	 * Applies validation rules to part data passed to method.
	 *
	 * @since 1.0.0.
	 *
	 * @param array $part_data Form part data.
	 *
	 * @return array $validated_data Validated form part data.
	 */
	public function validate_part( $part_data = array() ) {
		if ( ! isset( $part_data['type'] ) || ! isset( $part_data['id'] ) ) {
			$error = new WP_Error( 'part', __( 'Invalid data' ) );
			return $error;
		}

		$part = $this->get_part( $part_data['type'] );

		if ( ! $part ) {
			$error = new WP_Error( 'part', __( 'Field definition not found' ) );
			return $error;
		}

		$fields = $part->get_customize_fields();
		$defaults = $part->get_customize_defaults();
		// Whitelist keys
		$input_data = array_intersect_key( $part_data, $fields );
		// Extend defaults
		$input_data = wp_parse_args( $input_data, $defaults );
		$validated_data = array(
			'id' => sanitize_key( $part_data['id'] ),
		);

		foreach ( $fields as $field_name => $field_settings ) {
			if ( array_key_exists( 'sanitize', $field_settings ) ) {
				$sanitize_settings = $field_settings['sanitize'];
				$sanitize_settings = is_array( $sanitize_settings ) ? $sanitize_settings : array( $sanitize_settings );
				$sanitize_callback = $sanitize_settings[0];
				$sanitize_arguments = array_slice( $sanitize_settings, 1 );

				if ( is_callable( $sanitize_callback ) ) {
					$field_value = $input_data[$field_name];

					if ( empty( $sanitize_arguments ) ) {
						$validated_data[$field_name] = call_user_func( $sanitize_callback, $field_value );
					} else {
						$validated_data[$field_name] = call_user_func( $sanitize_callback, $field_value, $sanitize_arguments[0] );
					}
				} else {
					$error = new WP_Error( 'part', sprintf(
						__( 'Missing validation callback for field %s', 'happyforms' ),
						$field_name
					) );

					return $error;
				}
			}
		}

		return $validated_data;
	}

	public function html_part_class( $class, $part_data, $form_data ) {
		$class[] = 'happyforms-form__part';
		$class[] = 'happyforms-part happyforms-part--' . $part_data['type'];

		if ( isset( $part_data['width'] ) ) {
			$class[] = 'happyforms-part--width-' . esc_attr( $part_data['width'] );
		}

		if ( isset( $part_data['label_placement'] ) ) {
			$class[] = 'happyforms-part--label-' . esc_attr( $part_data['label_placement'] );
		}

		if ( isset( $part_data['css_class'] ) && ! empty( $part_data['css_class'] ) ) {
			$class[] = $part_data['css_class'];
		}

		return $class;
	}

	public function html_part_data_attributes( $attributes, $part_data, $form_data ) {
		$attributes['happyforms-type'] = $part_data['type'];
		$attributes['happyforms-id'] = $part_data['id'];

		if ( isset( $part_data['required'] ) && 1 === intval( $part_data['required'] ) ) {
			$attributes['happyforms-required'] = '';
		}

		if ( happyforms_is_preview() ) {
			$attributes['happyforms-id'] = $part_data['id'];
		}

		return $attributes;
	}

}

if ( ! function_exists( 'happyforms_get_part_library' ) ):

function happyforms_get_part_library() {
	return HappyForms_Form_Part_Library::instance();
}

endif;

happyforms_get_part_library();
