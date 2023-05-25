<?php

function fca_pc_woo_product_page_ga( $options ) {
	if ( function_exists( 'is_product' ) && is_product() ) {
	
		$p = fca_pc_get_woo_product();
		
		if ( $p ) {
			
			$woo_id_mode = empty( $options['woo_product_id'] ) ? 'post_id' : $options['woo_product_id'];
			$id = $woo_id_mode === 'post_id' ? $p->get_id() : $p->get_sku();
			
			$items = array(
				'item_id' => $id,
				'item_name' => $p->get_title(),
			);	
			
			$ga_data = array(
				'value' => wc_get_price_to_display( $p ),
				'currency' => get_woocommerce_currency(),
				'items' => $items,
			);

			wp_localize_script( 'fca_pc_client_js', 'fcaPcWooProductGA', $ga_data );
		}
	}
}
add_action( 'fca_pc_start_pixel_output', 'fca_pc_woo_product_page_ga', 10, 1 );

function fca_pc_woo_add_to_cart_ga( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {
	
	$p = fca_pc_get_woo_product( $product_id );

	if ( $p ) {
		
		$options = get_option( 'fca_pc', array() );
		$woo_id_mode = empty( $options['woo_product_id'] ) ? 'post_id' : $options['woo_product_id'];
		$id = $woo_id_mode === 'post_id' ? $p->get_id() : $p->get_sku();
				
		$items = array(
			'item_id' => $id,
			'item_name' => $p->get_title(),
		);
		
		$ga_data = array(
			'value' => wc_get_price_to_display( $p ),
			'currency' => get_woocommerce_currency(),
			'items' => $items,
		);
		
		setcookie( 'fca_pc_woo_add_to_cart_ga', json_encode( $ga_data ), 0, '/' );
	}
}
add_action( 'woocommerce_add_to_cart', 'fca_pc_woo_add_to_cart_ga', 10, 6 );

function fca_pc_woo_ajax_add_to_cart_ga() {

	$p = fca_pc_get_woo_product( sanitize_text_field( $_POST['product_id'] ) );

	if ( $p ) {
		
		$options = get_option( 'fca_pc', array() );
		$woo_id_mode = empty( $options['woo_product_id'] ) ? 'post_id' : $options['woo_product_id'];
		$id = $woo_id_mode === 'post_id' ? $p->get_id() : $p->get_sku();
		
		$items = array(
			'item_id' => $id,
			'item_name' => $p->get_title(),
		);
		
		$ga_data = array(
			'value' => wc_get_price_to_display( $p ),
			'currency' => get_woocommerce_currency(),
			'items' => $items,
		);
		
		wp_send_json_success( $ga_data );
		
	}

}
add_action( 'wp_ajax_fca_pc_woo_ajax_add_to_cart_ga', 'fca_pc_woo_ajax_add_to_cart_ga' );
add_action( 'wp_ajax_nopriv_fca_pc_woo_ajax_add_to_cart_ga', 'fca_pc_woo_ajax_add_to_cart_ga' );

function fca_pc_initiate_checkout_ga( $options ) {
	if ( function_exists( 'is_checkout' ) && is_checkout() && !is_order_received_page() ) {
		
		$value = 0;
		$items = array();
			
		$woo_id_mode = empty( $options['woo_product_id'] ) ? 'post_id' : $options['woo_product_id'];
				
		forEach ( WC()->cart->get_cart() as $item ) {
			
			$value = $value + $item['line_total'] + $item['line_tax'];
			$id = $woo_id_mode === 'post_id' ? $item['product_id'] : wc_get_product( $item['product_id'] )->get_sku();
			
			$item = array(
				'item_id' => $id,
				'item_name' => get_the_title( $item['product_id'] ),
			);
			
			$category = get_the_terms( $item['product_id'], 'product_cat' );
			
			if ( $category ) {
				$max_product_categories = 5;
				$n = 1;
				forEach ( $category as $term  ) {
					if( $n === 1 ) {
						$item['item_category'] = $term->name;
					} else if ( $n <= $max_product_categories ) {
						$keyname = "item_category$n"; //e.g. item_category2 
						$item[$keyname] = $term->name;
					} else {
						//ONLY SUPPORTS 5 CATEGORIES CURRENTLY
						//https://developers.google.com/analytics/devguides/collection/ga4/reference/events#begin_checkout
					}
					$content_category[] = $term->name;
				}
			}
			
			$items[] = $item;

		}
		
		
		$ga_data = array(
			'value' => $value,
			'currency' => get_woocommerce_currency(),
			'items' => $items,
		);
		
		wp_localize_script( 'fca_pc_client_js', 'fcaPcWooCheckoutCartGA', $ga_data );
		
		
	}
}
add_action( 'fca_pc_start_pixel_output', 'fca_pc_initiate_checkout_ga', 10, 1 );

function fca_pc_purchase_ga( $options ) {
	//WOOCOMMERCE THANK YOU REDIRECT PLUGIN SUPPORT
	$is_thank_you_page = isset( $_GET['order'] ) && isset( $_GET['key'] );

	if ( function_exists( 'is_order_received_page' ) && ( is_order_received_page() OR $is_thank_you_page ) ) {
		
		global $wp;
		$order_id = isset( $wp->query_vars['order-received'] ) ? intval( $wp->query_vars['order-received'] ) : intval( $wp->query_vars['order'] );
		$order = wc_get_order( $order_id );
		
			
		
		$woo_id_mode = empty( $options['woo_product_id'] ) ? 'post_id' : $options['woo_product_id'];
		$woo_extra_params = empty( $options['woo_extra_params'] ) ? false : true;
		
		if( $order ){
			$value = 0;
			$items = array();
			
			forEach ( $order->get_items() as $item ) {
				$value = $value + $item['line_total'] + $item['line_tax'];
				$id = $woo_id_mode === 'post_id' ? $item['product_id'] : wc_get_product( $item['product_id'] )->get_sku();
				
				$item = array(
					'item_id' => $id,
					'item_name' => get_the_title( $item['product_id'] ),
				);
				
				$category = get_the_terms( $item['product_id'], 'product_cat' );
				
				if ( $category ) {
					$max_product_categories = 5;
					$n = 1;
					forEach ( $category as $term  ) {
						
						if( $n === 1 ) {
							$item['item_category'] = $term->name;
						} else if ( $n <= $max_product_categories ) {
							$keyname = "item_category$n"; //e.g. item_category2 
							$item[$keyname] = $term->name;
						} else {
							//ONLY SUPPORTS 5 CATEGORIES CURRENTLY
							//https://developers.google.com/analytics/devguides/collection/ga4/reference/events#begin_checkout
						}
						
					}
				}
				
				$items[] = $item;
			}
			
			$ga_data = array(
				'value' => $value,
				'currency' => get_woocommerce_currency(),
				'items' => $items,
				'transaction_id' => $order_id,
			);
			
			if ( $woo_extra_params ) {
				
				$ga_data['lifetime_value'] = fca_pc_get_woo_ltv( $order->get_billing_email() );
				
				$extra_params = array(
					'get_used_coupons' => 'coupon',
					'get_billing_city' => 'billing_city',
					'get_billing_state' => 'billing_state',
					'get_payment_method' => 'payment_method',
					'get_shipping_method' => 'shipping_method',
				);
				
				forEach ( $extra_params as $key => $value ) {
					if ( $order->$key() ) {
						$ga_data[$value] = $order->$key();
					}	
				}
								
			}
						
			wp_localize_script( 'fca_pc_client_js', 'fcaPcWooPurchaseGA', $ga_data );

		}
	}
}
add_action( 'fca_pc_start_pixel_output', 'fca_pc_purchase_ga', 10, 1 );

