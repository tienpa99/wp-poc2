<?php

class HappyForms_Form_Email {

	private static $instance;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		self::$instance->hook();

		return self::$instance;
	}

	public function hook() {
		add_filter( 'happyforms_meta_fields', array( $this, 'meta_fields' ) );
		add_action( 'happyforms_do_email_control', array( happyforms_get_setup(), 'do_control' ), 10, 3 );
		add_action( 'happyforms_do_email_control', array( $this, 'do_control' ), 10, 3 );
	}

	public function get_fields() {
		global $current_user;

		$fields = array(
			'receive_email_alerts' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'alert_email_from_address' => array(
				'default' => ( $current_user->user_email ) ? $current_user->user_email : '',
				'sanitize' => 'happyforms_sanitize_emails',
			),
			'email_recipient' => array(
				'default' => ( $current_user->user_email ) ? $current_user->user_email : '',
				'sanitize' => 'happyforms_sanitize_emails',
			),
			'email_bccs' => array(
				'default' => '',
				'sanitize' => 'happyforms_sanitize_emails',
			),
			'alert_email_from_name' => array(
				'default' => get_bloginfo( 'name' ),
				'sanitize' => 'sanitize_text_field',
			),
			'alert_email_subject' => array(
				'default' => __( 'New submission to your form', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'include_submitters_ip' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'include_referral_link' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'include_submission_date_time' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'alert_email_reply_to' => array(
				'default' => '',
				'sanitize' => 'sanitize_text_field',
			),
			'send_confirmation_email' => array(
				'default' => 1,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'confirmation_email_sender_address' => array(
				'default' => ( $current_user->user_email ) ? $current_user->user_email : '',
				'sanitize' => 'happyforms_sanitize_emails',
			),
			'confirmation_email_reply_to' => array(
				'default' => ( $current_user->user_email ) ? $current_user->user_email : '',
				'sanitize' => 'happyforms_sanitize_emails',
			),
			'confirmation_email_from_name' => array(
				'default' => get_bloginfo( 'name' ),
				'sanitize' => 'sanitize_text_field',
			),
			'confirmation_email_subject' => array(
				'default' => __( 'We received your message', 'happyforms' ),
				'sanitize' => 'sanitize_text_field',
			),
			'confirmation_email_content' => array(
				'default' => __( 'Your message has been successfully sent. We appreciate you contacting us and we’ll be in touch soon.', 'happyforms' ),
				'sanitize' => 'esc_html',
			),
			'confirmation_email_include_values' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'confirmation_email_include_submitters_ip' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'confirmation_email_include_referral_link' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
			'confirmation_email_include_submission_date_time' => array(
				'default' => 0,
				'sanitize' => 'happyforms_sanitize_checkbox'
			),
		);

		return $fields;
	}

	public function get_controls() {
		$controls = array(
			340 => array(
				'type' => 'checkbox',
				'label' => __( 'Email me a copy of each submission', 'happyforms' ),
				'field' => 'receive_email_alerts',
			),
			341 => array(
				'type' => 'group_start',
				'trigger' => 'receive_email_alerts'
			),

			350 => array(
				'type' => 'text',
				'label' => __( 'To email address', 'happyforms' ),
				'field' => 'email_recipient',
			),
			351 => array(
				'type' => 'text',
				'label' => __( 'To Bcc email address', 'happyforms' ),
				'field' => 'email_bccs',
			),
			352 => array(
				'type' => 'text',
				'label' => __( 'From email address', 'happyforms' ),
				'field' => 'alert_email_from_address',
			),
			353 => array(
				'type' => 'email-parts-list',
				'label' => __( 'Reply email address', 'happyforms' ),
				'field' => 'alert_email_reply_to',
			),
			440 => array(
				'type' => 'text',
				'label' => __( 'Email display name', 'happyforms' ),
				'field' => 'alert_email_from_name',
			),
			450 => array(
				'type' => 'text',
				'label' => __( 'Email subject', 'happyforms' ),
				'field' => 'alert_email_subject',
			),
			451 => array(
				'type' => 'checkbox',
				'label' => __( 'Include submitter\'s IP address', 'happyforms' ),
				'field' => 'include_submitters_ip',
			),
			452 => array(
				'type' => 'checkbox',
				'label' => __( 'Include referral link', 'happyforms' ),
				'field' => 'include_referral_link',
			),
			453 => array(
				'type' => 'checkbox',
				'label' => __( 'Include submission date and time', 'happyforms' ),
				'field' => 'include_submission_date_time',
			),
			540 => array(
				'type' => 'group_end'
			),
			550 => array(
				'type' => 'checkbox',
				'label' => __( 'Email submitter a copy of their reply', 'happyforms' ),
				'field' => 'send_confirmation_email',
			),
			551 => array(
				'type' => 'group_start',
				'trigger' => 'send_confirmation_email'
			),
			631 => array(
				'type' => 'text',
				'label' => __( 'From email address', 'happyforms' ),
				'field' => 'confirmation_email_sender_address',
			),
			632 => array(
				'type' => 'text',
				'label' => __( 'Reply email address', 'happyforms' ),
				'field' => 'confirmation_email_reply_to',
			),
			650 => array(
				'type' => 'text',
				'label' => __( 'Email display name', 'happyforms' ),
				'field' => 'confirmation_email_from_name',
			),
			750 => array(
				'type' => 'text',
				'label' => __( 'Email subject', 'happyforms' ),
				'field' => 'confirmation_email_subject',
			),
			850 => array(
				'type' => 'editor',
				'label' => __( 'Email content', 'happyforms' ),
				'field' => 'confirmation_email_content',
			),
			860 => array(
				'type' => 'checkbox',
				'label' => __( "Include submitted values", 'happyforms' ),
				'field' => 'confirmation_email_include_values'
			),
			861 => array(
				'type' => 'checkbox',
				'label' => __( "Include submitter's IP address", 'happyforms' ),
				'field' => 'confirmation_email_include_submitters_ip'
			),
			862 => array(
				'type' => 'checkbox',
				'label' => __( "Include referral link", 'happyforms' ),
				'field' => 'confirmation_email_include_referral_link'
			),
			863 => array(
				'type' => 'checkbox',
				'label' => __( "Include submission date and time", 'happyforms' ),
				'field' => 'confirmation_email_include_submission_date_time'
			),
			870 => array(
				'type' => 'group_end'
			)
		);

		$controls = happyforms_safe_array_merge( array(), $controls );
		$controls = apply_filters( 'happyforms_email_controls', $controls );
		ksort( $controls, SORT_NUMERIC );

		return $controls;
	}

	public function do_control( $control, $field, $index ) {
		$type = $control['type'];

		if ('email-parts-list' == $type ) {
			require( happyforms_get_core_folder() . '/templates/customize-controls/email/email-parts-list.php' );
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

}

if ( ! function_exists( 'happyforms_get_email' ) ):

function happyforms_get_email() {
	return HappyForms_Form_Email::instance();
}

endif;

happyforms_get_email();
