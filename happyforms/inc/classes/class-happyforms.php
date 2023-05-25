<?php
class HappyForms extends HappyForms_Core {

	public $default_notice;

	public $action_archive = 'archive';

	public $onboarding_list_url = 'https://emailoctopus.com/lists/a58bf658-425e-11ea-be00-06b4694bee2a/members/embedded/1.3/add';

	public $action_onboarding = 'happyforms-submit-onboarding';

	public function initialize_plugin() {
		parent::initialize_plugin();

		add_action( 'happyforms_do_setup_control', array( $this, 'do_control' ), 10, 3 );
		add_action( 'happyforms_do_email_control', array( $this, 'do_control' ), 10, 3 );
		add_action( 'happyforms_do_style_control', array( $this, 'do_control' ), 10, 3 );
		add_action( 'happyforms_do_messages_control', array( $this, 'do_control' ), 10, 3 );
		add_filter( 'happyforms_setup_controls', array( $this, 'add_dummy_setup_controls' ) );
		add_filter( 'happyforms_email_controls', array( $this, 'add_dummy_email_controls' ) );
		add_filter( 'happyforms_style_controls', array( $this, 'add_dummy_style_controls' ) );
		add_filter( 'happyforms_messages_controls', array( $this, 'add_dummy_messages_controls' ) );
		add_action( 'parse_request', array( $this, 'parse_archive_request' ) );
		add_action( 'admin_init', [ $this, 'register_modals' ] );
		add_action( 'load-plugins.php', array( $this, 'redirect_to_forms_page' ) );
		add_action( 'happyforms_modal_dismissed', [ $this, 'modal_dismissed' ] );
		add_action( "wp_ajax_{$this->action_onboarding}", [ $this, 'ajax_action_onboarding' ] );
		add_filter( 'happyforms_dashboard_modal_settings', [ $this, 'get_dashboard_modal_settings' ] );
		add_action( 'happyforms_customize_enqueue_scripts', array( $this, 'customize_enqueue_styles' ) );

		if ( is_admin() ) {
			require_once( happyforms_get_integrations_folder() . '/classes/class-integrations-page-controller.php' );
			happyforms_get_export_controller();
		}

		$this->register_dummy_parts();
	}

	public function register_dummy_parts() {
		$part_library = happyforms_get_part_library();

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-website-url-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_WebsiteUrl_Dummy', 4 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-attachment-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Attachment_Dummy', 7 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-phone-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Phone_Dummy', 10 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-date-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Date_Dummy', 11 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-scale-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Scale_Dummy', 12 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-signature-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Signature_Dummy', 13 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-rating-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Rating_Dummy', 14 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-optin-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_OptIn_Dummy', 15 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-scrollable-terms-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_ScrollableTerms_Dummy', 16 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-payments-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Payments_Dummy', 17 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-toggletip-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_Toggletip_Dummy', 104 );

		require_once( happyforms_get_include_folder() . '/classes/parts/class-part-page-break-dummy.php' );
		$part_library->register_part( 'HappyForms_Part_PageBreak_Dummy', 106 );

		require_once( happyforms_get_include_folder() . '/classes/class-answer-limiter-dummy.php' );
	}

	public function add_dummy_setup_controls( $controls ) {
		$block_mails_label_1 = __( 'Trash submission if it contains words in', 'happyforms' );
		$block_mails_label_2 = __( 'Disallowed Comment Keys', 'happyforms' );
		$options_discussion_url = get_admin_url( null, 'options-discussion.php' );

		$setup_controls = array(
			1449 => array(
				'type' => 'text_dummy',
				'dummy_id' => 'redirect_url',
				'label' => __( 'Redirect to this page address (URL) after submission', 'happyforms' ),
				'placeholder' => __( 'Search or type URL', 'happyforms' ),
			),

			1450 => array(
				'type' => 'checkbox_dummy',
				'dummy_id' => 'shuffle_parts',
				'label' => __( 'Shuffle order of fields', 'happyforms' ),
			),

			1500 => array(
				'type' => 'checkbox_dummy',
				'dummy_id' => 'captcha',
				'label' => __( 'Use reCAPTCHA', 'happyforms' ),
			),

			1655 => array(
				'type' => 'number_dummy',
				'dummy_id' => 'abandoned_resume_response_expire',
				'label' => __( 'Let submitters save a draft for set number of days', 'happyforms' ),
			),

			1800 => array(
				'type' => 'checkbox_dummy',
				'dummy_id' => 'preview_before_submit',
				'label' => __( 'Require submitters to review a submission', 'happyforms' ),
			),

			2301 => array(
				'type' => 'number_dummy',
				'dummy_id' => 'restrict_entries',
				'label' => __( 'Max number of submissions', 'happyforms' ),
			),

			3190 => array(
				'type' => 'number_dummy',
				'dummy_id' => 'delete_submission_days',
				'label' => __( "Erase submitter's personal data after set number of days", 'happyforms' ),
			),

			3200 => array(
				'type' => 'checkbox_dummy',
				'dummy_id' => 'block_emails',
				'label' => sprintf( '%s <a href="%s" target="_blank" class="external">%s</a>', $block_mails_label_1, $options_discussion_url, $block_mails_label_2 ),
			),
		);

		$controls = happyforms_safe_array_merge( $controls, $setup_controls );

		return $controls;
	}

	public function add_dummy_email_controls( $controls ) {
		$email_controls = array(
			630 => array(
				'type' => 'email-parts-list_dummy',
				'dummy_id' => 'confirmation_email_respondent_address',
				'label' => __( 'To email address', 'happyforms' ),
			),

			1660 => array(
				'type' => 'checkbox_dummy',
				'dummy_id' => 'abandoned_resume_send_alert_email',
				'label' => __( 'Send abandonment email', 'happyforms' ),
			),
		);

		$controls = happyforms_safe_array_merge( $controls, $email_controls );

		return $controls;
	}

	public function add_dummy_style_controls( $controls ) {
		$style_controls = array(
			5792 => array(
				'type' => 'panel_dummy',
				'dummy_id' => 'rating',
				'label' => __( 'Rating', 'happyforms' ),
			),

			8560 => array(
				'type' => 'panel_dummy',
				'dummy_id' => 'multistep',
				'label' => __( 'Multi Step', 'happyforms' ),
			),
		);

		$controls = happyforms_safe_array_merge( $controls, $style_controls );

		return $controls;
	}

	public function add_dummy_messages_controls( $controls ) {
		$messages_controls = array(
			42 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'form_redirect_submission',
				'label' => __( 'Form redirected after submission', 'happyforms' ),
			),
			43 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'form_reached_reply_limit',
				'label' => __( 'Form has reached its reply limit', 'happyforms' ),
			),
			44 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'submitter_returned_draft',
				'label' => __( 'Submitter has returned to a draft', 'happyforms' ),
			),
			45 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'submitter_review_page',
				'label' => __( 'Submitter is viewing review page', 'happyforms' ),
			),
			46 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'payment_completed',
				'label' => __( 'Payment completed', 'happyforms' ),
			),
			47 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'payment_failed',
				'label' => __( 'Payment failed', 'happyforms' ),
			),
			48 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'payment_cancelled',
				'label' => __( 'Payment cancelled', 'happyforms' ),
			),
			2273 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'previous_page',
				'label' => __( 'Previous page', 'happyforms' ),
			),
			2274 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'redirect_page',
				'label' => __( 'Redirect to page', 'happyforms' ),
			),
			2031 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'print_submission',
				'label' => __( 'Print user submission', 'happyforms' ),
			),
			2271 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'edit_reply',
				'label' => __( 'Edit reply', 'happyforms' ),
			),
			2061 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'clear_saved_reply',
				'label' => __( 'Clear saved draft reply', 'happyforms' ),
			),
			2270 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'save_draft_reply',
				'label' => __( 'Save draft reply', 'happyforms' ),
			),
			2141 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'upload_files',
				'label' => __( 'Upload files', 'happyforms' ),
			),
			2161 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'remove_uploaded_file',
				'label' => __( 'Remove uploaded file', 'happyforms' ),
			),
			2272 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'review_reply',
				'label' => __( 'Review reply', 'happyforms' ),
			),
			2254 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'start_drawing_signature',
				'label' => __( 'Start drawing signature', 'happyforms' ),
			),
			2255 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'start_over_signature',
				'label' => __( 'Start over drawing signature', 'happyforms' ),
			),
			2256 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'clear_drawn_signature',
				'label' => __( 'Clear drawn signature', 'happyforms' ),
			),
			2257 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'done_drawing_signature',
				'label' => __( 'Done drawing signature', 'happyforms' ),
			),
			4052 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'field_answer_limit',
				'label' => __( 'Field answer reached its limit', 'happyforms' ),
			),
			4053 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'coupon_invalid',
				'label' => __( 'Coupon code invalid', 'happyforms' ),
			),
			4061 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'required_file_not_uploaded',
				'label' => __( 'Required file isn\'t uploaded', 'happyforms' ),
			),
			4106 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'required_not_scrolled',
				'label' => __( 'Required terms haven\'t been scrolled', 'happyforms' ),
			),
			4121 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'field_disallowed_word',
				'label' => __( 'Field contains disallowed word', 'happyforms' ),
			),
			4122 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'disallowed_ip',
				'label' => __( 'Disallowed IP address or browser', 'happyforms' ),
			),
			4123 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'file_too_big',
				'label' => __( 'This file\'s size is too big', 'happyforms' ),
			),
			4124 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'file_not_allowed',
				'label' => __( 'This file\'s type not allowed', 'happyforms' ),
			),
			4125 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'file_name_exists',
				'label' => __( 'A file with this name has already been uploaded', 'happyforms' ),
			),
			4126 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'uploaded_few_files',
				'label' => __( 'User uploaded too few files', 'happyforms' ),
			),
			4341 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'price_too_low',
				'label' => __( 'Price is too low', 'happyforms' ),
			),
			6101 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'phone_field_country_code_label',
				'label' => __( 'Phone field country code label', 'happyforms' ),
			),
			6102 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'phone_field_number_label',
				'label' => __( 'Phone field number label', 'happyforms' ),
			),
			6121 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'total_files_uploaded',
				'label' => __( 'Total files uploaded', 'happyforms' ),
			),
			6136 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'payment_method',
				'label' => __( 'Payment method', 'happyforms' ),
			),
			6137 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'pay_what_you_want',
				'label' => __( 'Pay what you want', 'happyforms' ),
			),
			6141 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'submitter_redirected_paypal',
				'label' => __( 'Submitter will be redirected to PayPal', 'happyforms' ),
			),
			6142 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'paypal_payment',
				'label' => __( 'PayPal payment', 'happyforms' ),
			),
			6171 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'stripe_processing_payment',
				'label' => __( 'Stripe is processing payment', 'happyforms' ),
			),
			6172 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'stripe_payment',
				'label' => __( 'Stripe payment', 'happyforms' ),
			),
			6173 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'stripe_card_field',
				'label' => __( 'Stripe card field', 'happyforms' ),
			),
			6174 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'card_number',
				'label' => __( 'Card number', 'happyforms' ),
			),
			6175 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'card_expiration',
				'label' => __( 'Card expiration', 'happyforms' ),
			),
			6176 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'card_security_code',
				'label' => __( 'Card security code', 'happyforms' ),
			),
			6177 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'coupon_field_label',
				'label' => __( 'Coupon field label', 'happyforms' ),
			),
			6178 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'apply_coupon_label',
				'label' => __( 'Apply coupon button label', 'happyforms' ),
			),
			6241 => array(
				'type' => 'text_dummy_reset',
				'dummy_id' => 'current_page',
				'label' => __( 'Current page', 'happyforms' ),
			),
		);

		$controls = happyforms_safe_array_merge( $controls, $messages_controls );

		return $controls;
	}

	public function do_control( $control, $field, $index ) {
		$type = $control['type'];

		if ( 'checkbox_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/checkbox_dummy.php' );
		}

		if ( 'number_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/number_dummy.php' );
		}

		if ( 'email-parts-list_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/email-parts-list-dummy.php' );
		}

		if ( 'panel_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/panel_dummy.php' );
		}

		if ( 'text_dummy' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/text_dummy.php' );
		}

		if ( 'text_dummy_reset' === $type ) {
			require( happyforms_get_include_folder() . '/templates/customize-controls/text_dummy_reset.php' );
		}
	}

	public function admin_enqueue_scripts() {
		parent::admin_enqueue_scripts();

		wp_enqueue_style(
			'happyforms-free-admin',
			happyforms_get_plugin_url() . 'inc/assets/css/admin.css',
			array( 'thickbox' ), happyforms_get_version()
		);

		wp_enqueue_script(
			'happyforms-free-admin',
			happyforms_get_plugin_url() . 'inc/assets/js/admin/dashboard.js',
			array( 'happyforms-admin' ), happyforms_get_version(), true
		);

		wp_enqueue_style(
			'happyforms-dashboard-modals-upgrade',
			happyforms_get_plugin_url() . 'inc/assets/css/dashboard-modals.css',
			array( 'happyforms-dashboard-modals' ), happyforms_get_version()
		);

		$this->enqueue_onboarding_modal();
		$this->enqueue_upgrade_modal();
	}

	public function parse_archive_request() {
		global $pagenow;

		if ( 'edit.php' !== $pagenow ) {
			return;
		}

		$form_post_type = happyforms_get_form_controller()->post_type;

		if ( ! isset( $_GET['post_type'] ) || $form_post_type !== $_GET['post_type'] ) {
			return;
		}

		if ( ! isset( $_GET[$this->action_archive] ) ) {
			return;
		}

		$form_id = $_GET[$this->action_archive];
		$form_controller = happyforms_get_form_controller();
		$message_controller = happyforms_get_message_controller();
		$form = $form_controller->get( $form_id );

		if ( ! $form ) {
			return;
		}

		$message_controller->export_archive( $form );
	}

	public function is_new_user( $forms ) {
		if ( 1 !== count( $forms ) ) {
			return false;
		}

		$form = $forms[0];

		if ( 'Sample Form' === $form['post_title'] ) {
			return true;
		}

		return false;
	}

	public function register_modals() {
		$modals = happyforms_get_dashboard_modals();

		$modals->register_modal( 'upgrade' );
		$modals->register_modal( 'onboarding', [ 'dismissible' => true ] );
	}

	public function redirect_to_forms_page() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		if ( 'edit-happyform' === $screen->id ) {
			return;
		}

		if ( happyforms_get_dashboard_modals()->is_dismissed( 'onboarding' ) ) {
			return;
		}

		$tracking = happyforms_get_tracking();
		$status = $tracking->get_status();

		if ( 1 < intval( $status['status'] ) ) {
			return;
		}

		$url = admin_url( 'edit.php?post_type=happyform' );
		wp_safe_redirect( $url );

		exit;
	}

	public function enqueue_onboarding_modal() {
		$screen = get_current_screen();

		if ( ! $screen ) {
			return;
		}

		if ( 'edit-happyform' !== $screen->id ) {
			return;
		}

		$modals = happyforms_get_dashboard_modals();

		if ( $modals->is_dismissed( 'onboarding' ) ) {
			return;
		}

		wp_add_inline_script(
			'happyforms-dashboard-modals',
			"( function( $ ) { $( function() { happyForms.modals.openOnboardingModal(); } ); } )( jQuery );"
		);
	}

	public function enqueue_upgrade_modal() {
		global $pagenow;

		$message_post_type = happyforms_get_message_controller()->dummy_type;
		$current_post_type = get_current_screen()->post_type;

		$is_activity_screen = (
			in_array( $pagenow, array( 'edit.php', 'post.php' ) )
			&& ( $current_post_type === $message_post_type )
		);

		$is_integrations_screen = (
			isset( $_GET['page'] )
			&& 'happyforms-integrations' === $_GET['page']
		);

		$is_coupons_screen = (
			isset( $_GET['page'] )
			&& 'happyforms-coupons' === $_GET['page']
		);

		if ( ! $is_activity_screen && ! $is_integrations_screen && ! $is_coupons_screen ) {
			return;
		}

		ob_start();
		?>

		( function( $ ) {

		happyForms.modals.closeModal = function() {
			window.location.href = '<?php echo get_admin_url() . 'edit.php?post_type=happyform'; ?>';
		}

		$( function() {
			happyForms.modals.openUpgradeModal();
		} );

		} )( jQuery );

		<?php
		$script = ob_get_clean();

		wp_add_inline_script( 'happyforms-dashboard-modals', $script );
	}

	public function modal_dismissed( $id ) {
		if ( 'onboarding' === $id ) {
			happyforms_get_tracking()->update_status( 2 );
		}
	}

	public function ajax_action_onboarding() {
		$email = isset( $_POST['email'] ) ? $_POST['email'] : '';
		$email = trim( $email );

		// Submit to EmailOctopus
		if ( ! empty( $email ) ) {
			wp_remote_post( $this->onboarding_list_url, array(
				'body' => array(
					'field_0' => $email,
				),
			) );
		}
	}

	public function get_dashboard_modal_settings( $settings ) {
		$settings['onboardingModalAction'] = $this->action_onboarding;
		$settings['onboardingModalNonce'] = wp_create_nonce( $this->action_onboarding );

		return $settings;
	}

	public function admin_screens() {
		parent::admin_screens();

		global $pagenow;

		$message_post_type = happyforms_get_message_controller()->dummy_type;
		$current_post_type = get_current_screen()->post_type;

		if ( in_array( $pagenow, array( 'edit.php', 'post.php' ) )
			&& ( $current_post_type === $message_post_type ) ) {

			require_once( happyforms_get_include_folder() . '/classes/class-message-admin.php' );
		}
	}

	public function admin_menu() {
		parent::admin_menu();

		global $submenu;

		if ( ! isset( $submenu['happyforms'] ) ) {
			return;
		}

		$submenu_links = wp_list_pluck( $submenu['happyforms'], 2 );

		$submission_index = array_search( 'edit.php?post_type=happyforms-activity', $submenu_links );
		$coupon_index = array_search( 'happyforms-coupons', $submenu_links );
		$integration_index = array_search( 'happyforms-integrations', $submenu_links );
		$settings_index = array_search( '#settings', $submenu_links );

		if ( false === $submission_index || false === $coupon_index || false === $integration_index || false === $settings_index ) {
			return;
		}

		$submenu['happyforms'][$submission_index][0] .= "<span class='hf-members-only-menu awaiting-mod'>" . __( 'Upgrade', 'happyforms') . "</span>";
		$submenu['happyforms'][$coupon_index][0] .= "<span class='hf-members-only-menu awaiting-mod'>" . __( 'Upgrade', 'happyforms') . "</span>";
		$submenu['happyforms'][$integration_index][0] .= "<span class='hf-members-only-menu awaiting-mod'>" . __( 'Upgrade', 'happyforms') . "</span>";
		$submenu['happyforms'][$settings_index][0] .= "<span class='hf-members-only-menu awaiting-mod'>" . __( 'Upgrade', 'happyforms') . "</span>";
	}

	public function customize_enqueue_styles() {
		wp_enqueue_style(
			'happyforms-customize-css',
			happyforms_get_plugin_url() . 'inc/assets/css/customize.css',
			array(), happyforms_get_version()
		);
	}

}
