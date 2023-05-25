<?php
/**
 * WooCommerce class.
 *
 * @since 1.0.4
 *
 * @package TPAPI
 * @author  Devin Vinson
 */
class TPAPI_WooCommerce {

	/**
	 * Holds the class object.
	 *
	 * @since 1.0.4
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Path to the file.
	 *
	 * @since 1.0.4
	 *
	 * @var string
	 */
	public $file = __FILE__;

	/**
	 * The minimum WooCommerce version required.
	 *
	 * @since 1.0.4
	 *
	 * @var string
	 */
	const MINIMUM_VERSION = '3.2.0';

	/**
	 * Holds the base class object.
	 *
	 * @since 1.0.4
	 *
	 * @var object
	 */
	public $base;

	/**
	 * Primary class constructor.
	 *
	 * @since 1.0.4
	 */
	public function __construct() {
		// Set base tp object.
		$this->set();

	}

	/**
	 * Sets our object instance and base class instance.
	 *
	 * @since 1.0.4
	 */
	public function set() {
		self::$instance = $this;
		$this->base     = TPAPI::get_instance();
	}

	/**
	 * Support WooCommerce product images in webhook payload
	 *
	 * @since 1.0.4
	 */
	public static function add_tp_product_data_to_wc_api( $response, $post, $request ) {
		if ( empty( $response->data['line_items'] ) ) {
			return $response;
		}

		foreach ( (array) $response->data['line_items'] as $key => $product ) {
			if ( empty( $product['product_id'] ) ) {
				continue;
			}

			$product_id = absint( $product['product_id'] );

			// Product Image
			$thumbnail_id = get_post_thumbnail_id( $product_id );
			if ( $thumbnail_id ) {
				$attachment = wp_get_attachment_image_src( $thumbnail_id, 'woocommerce_thumbnail' );
				if ( ! empty( $attachment[0] ) ) {
					$response->data['line_items'][ $key ]['tp_image_thumbnail_url'] = $attachment[0];
				}
			}

			// Product URL
			if ( function_exists( 'wc_get_product' ) ) {
				$wc_product = wc_get_product( $product_id );
				$permalink  = is_object( $wc_product ) && method_exists( $wc_product, 'get_permalink' )
					? $wc_product->get_permalink()
					: null;
				if ( $permalink ) {
					$response->data['line_items'][ $key ]['tp_product_url'] = $permalink;
				}
			}
		}

		return $response;
	}

}
