<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

/**
 * Define PBG_Blocks_Integrations class
 */
class PBG_Blocks_Integrations {

	/**
	 * Class instance
	 *
	 * @var instance
	 */
	private static $instance = null;

	/**
	 * Creates and returns an instance of the class
	 *
	 * @access public
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor for the class
	 */
	public function __construct() {
		add_action( 'wp_ajax_pbg-get-instagram-token', array( $this, 'get_instagram_token' ) );
		add_action( 'wp_ajax_pbg-get-instagram-feed', array( $this, 'get_instagram_feed' ) );
	}

	/**
	 * Get Instagram account token for Instagram Feed widget
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function get_instagram_token() {
		check_ajax_referer( 'pbg-social', 'nonce' );
		$api_url = 'https://appfb.premiumaddons.com/wp-json/fbapp/v2/instagram';

		$response = wp_remote_get(
			$api_url,
			array(
				'timeout'   => 60,
				'sslverify' => false,
			)
		);

		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, true );
		error_log( wp_json_encode( $body ) . ' instagram token' );
		$transient_name = 'pbg_insta_token_' . substr( $body, -8 );

		$expire_time = 59 * DAY_IN_SECONDS;

		set_transient( $transient_name, $body, $expire_time );

		wp_send_json_success( $body );
	}

	/**
	 * Get Instagram feeds by token
	 *
	 * @access public
	 *
	 * @return void
	 */
	public function get_instagram_feed() {
		check_ajax_referer( 'pbg-social', 'nonce' );
		$access_token = isset( $_POST['accessToken'] ) ? sanitize_text_field( wp_unslash( $_POST['accessToken'] ) ) : '';
		if ( ! $access_token ) {
			wp_send_json_error();
		}
		$posts = array();

		$access_token = $this->check_instagram_token( $access_token );
		$api_url      = sprintf( 'https://graph.instagram.com/me/media?fields=id,media_type,media_url,username,timestamp,permalink,caption,children,thumbnail_url&limit=200&access_token=%s', $access_token );

		$response = wp_remote_get(
			$api_url,
			array(
				'timeout'   => 60,
				'sslverify' => false,
			)
		);

		if ( is_wp_error( $response ) ) {
			wp_send_json_error();
		}
		$response = wp_remote_retrieve_body( $response );
		$posts    = json_decode( $response, true );

		wp_send_json_success( $posts );
	}

	/**
	 * Check Instagram token expiration
	 *
	 * @access public
	 *
	 * @param string $old_token the original access token.
	 *
	 * @return void
	 */
	public static function check_instagram_token( $old_token ) {
		$token = get_transient( 'pbg_insta_token_' . substr( $old_token, -8 ) );

		$refreshed_token = $old_token;

		if ( ! $token ) {
			$response        = wp_remote_retrieve_body(
				wp_remote_get( 'https://graph.instagram.com/refresh_access_token?grant_type=ig_refresh_token&access_token=' . $token )
			);
			$response        = json_decode( $response, true );
			$refreshed_token = isset( $response->access_token ) ? $response->access_token : $old_token;
			$transient_name  = 'pbg_insta_token_' . substr( $old_token, -8 );
			$expire_time     = 59 * DAY_IN_SECONDS;
			set_transient( $transient_name, $refreshed_token, $expire_time );
		}
		return $refreshed_token;
	}

	/**
	 * Get Time
	 *
	 * @param  string $time_text
	 * @return int
	 */
	public static function get_time( $time_text ) {
		switch ( $time_text ) {
			case 'minute':
				$time = MINUTE_IN_SECONDS;
				break;
			case 'hour':
				$time = HOUR_IN_SECONDS;
				break;
			case 'day':
				$time = DAY_IN_SECONDS;
				break;
			case 'week':
				$time = WEEK_IN_SECONDS;
				break;
			case 'month':
				$time = MONTH_IN_SECONDS;
				break;
			case 'year':
				$time = YEAR_IN_SECONDS;
				break;
			default:
				$time = HOUR_IN_SECONDS;
		}
		return $time;
	}
}

PBG_Blocks_Integrations::get_instance();
