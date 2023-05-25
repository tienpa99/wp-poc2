<?php

class HappyForms_WP_Customize_Form_Manager {

	/**
	 * A reference to the $wp_customize object.
	 *
	 * @var WP_Customize_Manager
	 */
	private $manager;

	/**
	 * WP_Customize_Form_Manager constructor.
	 *
	 * @since  1.0
	 */
	public function __construct() {
		require_once( happyforms_get_core_folder() . '/helpers/helper-validation.php' );

		/*
		 * Note the customize_register action is triggered in
		 * WP_Customize_Manager::wp_loaded() which is itself the
		 * callback for the wp_loaded action at priority 10. So
		 * this wp_loaded action just has to be added at a
		 * priority less than 10.
		 */
		add_action( 'wp_loaded', array( $this, 'wp_loaded' ), 1 );

		// Ajax callbacks.
		add_action( 'wp_ajax_happyforms-update-form', array( $this, 'ajax_update_form' ) );
		add_action( 'wp_ajax_happyforms-form-part-add', array( $this, 'ajax_form_part_add' ) );
		add_action( 'wp_ajax_happyforms-form-parts-add', array( $this, 'ajax_form_parts_add' ) );
		add_action( 'wp_ajax_happyforms-form-fetch-partial-html', array( $this, 'ajax_fetch_partial' ) );
		add_action( 'wp_ajax_happyforms-get-custom-css', array( $this, 'ajax_get_custom_css' ) );
	}

	/**
	 * Get the form from the current request `form_id` parameter.
	 *
	 * @since  1.0
	 *
	 * @return array The current form data.
	 */
	public function get_current_form() {
		$form_id = intval( $_REQUEST['form_id'] );
		$form = happyforms_get_form_controller()->get( $form_id );

		if ( is_wp_error( $form ) ) {
			wp_die( $form->get_error_message() );
			exit;
		}

		$form = apply_filters( 'happyforms_customize_get_current_form', $form );

		return $form;
	}

	/**
	 * Action: reset Customize hooks and
	 * inject HappyForms logic and scripts.
	 *
	 * @since  1.0
	 *
	 * @hooked action wp_loaded
	 *
	 * @return void
	 */
	public function wp_loaded() {
		$this->get_current_form();

		global $wp_customize;

		remove_all_actions( 'customize_register' );
		remove_all_actions( 'customize_controls_enqueue_scripts' );

		$this->manager = $wp_customize;
		$this->library = happyforms_get_part_library();

		// Carry the happyforms customize query parameter over to preview screen
		add_action( 'customize_controls_init', array( $this, 'preview_screen_preserve_query_arg' ) );
		// Register a custom nonce
		add_filter( 'customize_refresh_nonces', array( $this, 'add_customize_nonce' ) );
		// Output header styles
		add_filter( 'customize_controls_print_scripts', array( $this, 'customize_controls_print_styles' ) );
		// Enqueue dynamic controls for the Customizer
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_scripts_customizer' ) );
		// Print templates
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'customize_controls_print_footer_scripts' ) );
	}

	/**
	 * Action: preserve the `happyforms` GET parameter
	 * across Customize preview sessions.
	 *
	 * @since  1.0
	 *
	 * @hooked action customize_controls_init
	 *
	 * @return void
	 */
	public function preview_screen_preserve_query_arg() {
		$this->manager->set_preview_url(
			add_query_arg(
				array( 'happyforms' => '1' ),
				$this->manager->get_preview_url()
			)
		);
	}

	/**
	 * Action: inject the HappyForms nonce in a Customize session.
	 *
	 * @since  1.0
	 *
	 * @hooked action customize_refresh_nonces
	 *
	 * @return array
	 */
	public function add_customize_nonce( $nonces ) {
		$nonces['happyforms'] = wp_create_nonce( 'happyforms' );
		return $nonces;
	}

	/**
	 * Action: update a form with data coming from the Customize screen.
	 *
	 * @since  1.0
	 *
	 * @hooked action wp_ajax_happyforms-update-form
	 *
	 * @return void
	 */
	public function ajax_update_form() {
		if ( ! check_ajax_referer( 'happyforms', 'happyforms-nonce', false ) ) {
			status_header( 400 );
			wp_send_json_error( 'bad_nonce' );
		}

		if ( ! current_user_can( 'customize' ) ) {
			status_header( 403 );
			wp_send_json_error( 'customize_not_allowed' );
		}

		if ( ! isset( $_POST['form'] ) || empty( $_POST['form'] ) ) {
			status_header( 403 );
			wp_send_json_error( 'empty form data' );
		}

		$form_data = json_decode( wp_unslash( $_POST['form'] ), true );
		$result = happyforms_get_form_controller()->update( $form_data );
		$data = array();

		if ( is_wp_error( $result ) ) {
			$data['message'] = $result->get_error_message();
			wp_send_json_error( $data );
		}

		$is_new_form = ( isset( $form_data['ID'] ) && 0 === $form_data['ID'] );
		if ( $is_new_form ) {
			$notice_id = 'happyforms_form_created';
			$notice_content = __( 'To publish your new form, add a <i>Forms</i> block anywhere your theme allows blocks to be added. If you\'re using an older theme or page builder, you might instead need to copy the form\'s shortcode and paste it into your site as your theme\'s author recommends.', 'happyforms' );

			happyforms_get_admin_notices()->register(
				$notice_id,
				$notice_content,
				array(
					'type' => 'info',
					'dismissible' => true,
					'screen' => array( 'edit-happyform' ),
					'one-time' => true,
				)
			);
		}

		wp_send_json_success( $result );
	}

	/**
	 * Action: return part metadata after it has been added
	 * to the form in a format the Customize preview can
	 * handle.
	 *
	 * @since  1.0
	 *
	 * @hooked action wp_ajax_happyforms-form-part-added
	 *
	 * @return void
	 */
	public function ajax_form_part_add() {
		if ( ! check_ajax_referer( 'happyforms', 'happyforms-nonce', false ) ) {
			status_header( 400 );
			wp_send_json_error( 'bad_nonce' );
		}

		if ( ! current_user_can( 'customize' ) ) {
			status_header( 403 );
			wp_send_json_error( 'customize_not_allowed' );
		}

		if ( ! isset( $_POST['form'] ) || empty( $_POST['form'] )
			|| ! isset( $_POST['part'] ) || empty( $_POST['part'] ) ) {
			status_header( 403 );
			wp_send_json_error( 'Missing data' );
		}

		$form_data = json_decode( wp_unslash( $_POST['form'] ), true );
		$part_data = $_POST['part'];
		$template = happyforms_get_form_part( $part_data, $form_data );
		$template = stripslashes( $template );

		header( 'Content-type: text/html' );
		echo( $template );
		exit();
	}

	public function ajax_form_parts_add() {
		if ( ! check_ajax_referer( 'happyforms', 'happyforms-nonce', false ) ) {
			status_header( 400 );
			wp_send_json_error( 'bad_nonce' );
		}

		if ( ! current_user_can( 'customize' ) ) {
			status_header( 403 );
			wp_send_json_error( 'customize_not_allowed' );
		}

		if ( ! isset( $_POST['form'] ) || empty( $_POST['form'] )
			|| ! isset( $_POST['parts'] ) || empty( $_POST['parts'] ) ) {
			status_header( 403 );
			wp_send_json_error( 'Missing data' );
		}

		$form_data = json_decode( wp_unslash( $_POST['form'] ), true );
		$parts_data = $_POST['parts'];
		$parts_html = array();

		foreach( $parts_data as $part_data ) {
			$template = happyforms_get_form_part( $part_data, $form_data );
			$template = stripslashes( $template );

			ob_start();
			echo( $template );
			$html = ob_get_clean();

			$parts_html[$part_data['id']] = $html;
		}

		wp_send_json_success( $parts_html );
	}

	/**
	 * Action: output styles for the Customize screen.
	 *
	 * @since  1.0
	 *
	 * @hooked action customize_controls_print_scripts
	 *
	 * @return void
	 */
	public function customize_controls_print_styles() {
		?>
		<style>
		#customize-save-button-wrapper,
		#customize-info,
		#customize-notifications-area {
			display: none !important;
		}
		</style>
		<?php
	}

	/**
	 * Action: enqueue HappyForms styles and scripts
	 * for the Customizer part.
	 *
	 * @since  1.0
	 *
	 * @hooked action customize_controls_enqueue_scripts
	 *
	 * @return void
	 */
	public function enqueue_scripts_customizer() {
		wp_enqueue_style(
			'happyforms-customize',
			happyforms_get_plugin_url() . 'core/assets/css/customize.css',
			array( 'wp-color-picker', 'wp-pointer' ), happyforms_get_version()
		);

		wp_enqueue_style( 'code-editor' );

		$customize_deps = apply_filters(
			'happyforms_customize_dependencies',
			array(
				'backbone',
				'underscore',
				'jquery',
				'jquery-ui-core',
				'jquery-effects-core',
				'jquery-ui-sortable',
				'jquery-ui-slider',
				'jquery-ui-button',
				'wp-color-picker',
				'wp-pointer',
				'customize-controls',
				'csslint',
				'code-editor'
			)
		);

		wp_register_script(
			'happyforms-customize',
			happyforms_get_plugin_url() . 'inc/assets/js/customize.js',
			$customize_deps, happyforms_get_version(), true
		);

		$data = apply_filters( 'happyforms_customize_settings', array(
			'form' => $this->get_current_form(),
			'formParts' => $this->library->get_parts(),
			'baseUrl' => get_home_url( null, '/' ),
			'unlabeledFieldLabel' => happyforms_get_part_label( array( 'label' => '' ) ),
			'abandonAlertMessage' => __( 'The changes you made will be lost if you navigate away from this page.', 'happyforms' ),
		) );

		wp_localize_script( 'happyforms-customize', '_happyFormsSettings', $data );
		wp_enqueue_script( 'happyforms-customize' );

		// Rich text editor
		if ( ! class_exists( '_WP_Editors', false ) ) {
			require( ABSPATH . WPINC . '/class-wp-editor.php' );
		}

		wp_enqueue_editor();

		do_action( 'happyforms_customize_enqueue_scripts' );
	}

	/**
	 * Action: return part metadata after it has been added
	 * to the form in a format the Customize preview can
	 * handle.
	 *
	 * @since  1.0
	 *
	 * @hooked action wp_ajax_happyforms-form-part-added
	 *
	 * @return void
	 */
	public function ajax_fetch_partial() {
		if ( ! check_ajax_referer( 'happyforms', 'happyforms-nonce', false ) ) {
			status_header( 400 );
			wp_send_json_error( 'bad_nonce' );
		}

		if ( ! current_user_can( 'customize' ) ) {
			status_header( 403 );
			wp_send_json_error( 'customize_not_allowed' );
		}

		if ( ! isset( $_POST['form'] ) ) {
			status_header( 403 );
			wp_send_json_error( 'Missing data' );
		}

		$form_data = json_decode( wp_unslash( $_POST['form'] ), true );
		$partial_name = sanitize_text_field( $_POST['partial_name'] );
		$template = happyforms_get_form_partial( $partial_name, $form_data );
		$template = stripslashes( $template );

		header( 'Content-type: text/html' );
		echo ( $template );
		exit();
	}

	public function ajax_get_custom_css() {
		if ( ! check_ajax_referer( 'happyforms', 'happyforms-nonce', false ) ) {
			status_header( 400 );
			wp_send_json_error( 'bad_nonce' );
		}

		if ( ! current_user_can( 'customize' ) ) {
			status_header( 403 );
			wp_send_json_error( 'customize_not_allowed' );
		}

		if ( ! isset( $_POST['form'] ) || empty( $_POST['form'] ) ) {
			status_header( 403 );
			wp_send_json_error( 'empty form data' );
		}

		$form = json_decode( wp_unslash( $_POST['form'] ), true );
		$additional_css = $form['additional_css'];
		$form_wrapper_id = happyforms_get_form_wrapper_id( $form );
		$additional_css = happyforms_get_prefixed_css( $additional_css, "#{$form_wrapper_id}" );

		header( 'Content-type: text/plain' );
		echo ( $additional_css );
		exit();
	}

	/**
	 * Action: output Javascript templates for the form editing interface.
	 *
	 * @since  1.0
	 *
	 * @hooked action customize_controls_print_footer_scripts
	 *
	 * @return void
	 */
	public function customize_controls_print_footer_scripts() {
		global $wp_customize;

		require_once( happyforms_get_core_folder() . '/templates/customize-header-actions.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-sidebar.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-steps.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-item.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-setup.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-build.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-parts-drawer.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-style.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-email.php' );
		require_once( happyforms_get_core_folder() . '/templates/customize-form-messages.php' );

		_WP_Editors::print_default_editor_scripts();
	}

}
