<?php

namespace CTXFeed\V5\Structure;

use CTXFeed\V5\Merchant\MerchantAttributeReplaceFactory;
use CTXFeed\V5\Shipping\ShippingFactory;
use CTXFeed\V5\Utility\Settings;
use WC_Tax;

class GoogleStructure implements StructureInterface {
	private $config;
	
	public function __construct( $config ) {
		$this->config               = $config;
		$this->config->itemWrapper  = 'item';
		$this->config->itemsWrapper = 'items';
	}
	
	
	public function get_grouped_attributes() {
		$group['installment']       = [
			'installment_months',
			'installment_amount'
		];
		$group['subscription_cost'] = [
			'subscription_period',
			'subscription_period_length',
			'subscription_amount'
		];
		$group['product_detail']    = [
			'section_name',
			'attribute_name',
			'attribute_value'
		];
		$group[]                    = [
			'product_highlight_1',
			'product_highlight_2',
			'product_highlight_3',
			'product_highlight_4',
			'product_highlight_5',
			'product_highlight_6',
			'product_highlight_7',
			'product_highlight_8',
			'product_highlight_9',
			'product_highlight_10'
		];
		$group['tax']               = [
			'tax_country',
			'tax_region',
			'tax_rate',
			'tax_ship'
		];
		$group['shipping']          = [
			'country',
			'region',
			'service',
			'price',
			'location_id',
			'location_group_name',
			'min_handling_time',
			'max_handling_time',
			'min_transit_time',
			'max_transit_time'
		];
		
		return $group;
	}
	
	public function getXMLStructure() {
		$product_detail = [];
		$subscription   = [];
		$installment    = [];
		$tax            = [];
		$group          = $this->get_grouped_attributes();
		$attributes     = $this->config->attributes;
		$mattributes    = $this->config->mattributes;
		$static         = $this->config->default;
		$type           = $this->config->type;
		$wrapper        = $this->config->itemWrapper;
		$data           = [];
		foreach ( $mattributes as $key => $attribute ) {
			$installment_sub = str_replace( "installment_", "", $attribute );
			$installment_sub = MerchantAttributeReplaceFactory::replace_attribute( $installment_sub, $this->config );
			
			$subscription_sub = str_replace( "subscription_", "", $attribute );
			$subscription_sub = MerchantAttributeReplaceFactory::replace_attribute( $subscription_sub, $this->config );
			
			$tax_attrs            = substr_count( implode( '|', $mattributes ), 'tax_' );
			$attributeValue       = ( $type[ $key ] === 'pattern' ) ? $static[ $key ] : $attributes[ $key ];
			$replacedAttribute    = MerchantAttributeReplaceFactory::replace_attribute( $attribute, $this->config );
			$product_detail_label = MerchantAttributeReplaceFactory::replace_attribute( 'product_detail', $this->config );
			$shipping_label       = MerchantAttributeReplaceFactory::replace_attribute( 'shipping', $this->config );
			$tax_label            = MerchantAttributeReplaceFactory::replace_attribute( 'tax', $this->config );
			$installment_label    = MerchantAttributeReplaceFactory::replace_attribute( 'installment', $this->config );
			// Installment Attribute
			if ( in_array( $attribute, $group['installment'], true ) && count( $installment ) < 1 ) {
				$installment[ $installment_sub ] = $attributeValue;
			} elseif ( in_array( $attribute, $group['installment'], true ) ) {
				$installment[ $installment_sub ]        = $attributeValue;
				$data[ $wrapper ][ $installment_label ] = $installment;
				$installment                            = [];
			} // Subscription Attributes
			elseif ( in_array( $attribute, $group['subscription_cost'], true ) && count( $subscription ) < 2 ) {
				$subscription[ $subscription_sub ] = $attributeValue;
			} elseif ( in_array( $attribute, $group['subscription_cost'], true ) ) {
				$subscription_cost                      = MerchantAttributeReplaceFactory::replace_attribute( 'subscription_cost', $this->config );
				$subscription[ $subscription_sub ]      = $attributeValue;
				$data[ $wrapper ][ $subscription_cost ] = $subscription;
				$subscription                           = [];
			} elseif ( strpos( $attribute, 'product_highlight' ) !== false ) {
				$product_highlight                        = MerchantAttributeReplaceFactory::replace_attribute( 'product_highlight', $this->config );
				$data[ $wrapper ][][ $product_highlight ] = $attributeValue;
			} elseif ( strpos( $attribute, 'images_' ) !== false ) {
				$attribute                        = MerchantAttributeReplaceFactory::replace_attribute( $attribute, $this->config );
				$data[ $wrapper ][][ $attribute ] = $attributeValue;
			} elseif ( in_array( $attribute, $group['tax'], true ) ) {
				$sub = str_replace( [ 'tax_', 'ship' ], [ '', 'tax_ship' ], $attribute );
				$sub = MerchantAttributeReplaceFactory::replace_attribute( $sub, $this->config );
				if ( count( $tax ) < $tax_attrs - 1 ) {
					$tax[ $sub ] = $attributeValue;
					
				} else {
					$tax[ $sub ]                      = $attributeValue;
					$data[ $wrapper ][][ $tax_label ] = $tax;
					$tax                              = [];
				}
			} elseif ( in_array( $attribute, $group['product_detail'], true ) ) {
				if ( $attribute === 'section_name' || $attribute === 'attribute_name' ) {
					$product_detail[ $replacedAttribute ] = $attributeValue;
				} elseif ( $attribute === 'attribute_value' ) {
					$product_detail[ $replacedAttribute ]        = $attributeValue;
					$data[ $wrapper ][][ $product_detail_label ] = $product_detail;
					$product_detail                              = [];
				}
			} elseif ( in_array( $attribute, $group['shipping'], true ) ) {
//				$shipping[ $replacedAttribute ]      = $attributeValue;
//				$data[ $wrapper ][ 'shipping_structure' ] = $shipping;
				$data[ $wrapper ][ $shipping_label ] = 'shipping';
			} else {
				$data[ $wrapper ][ $replacedAttribute ] = $attributeValue;
			}
		}
		
		return $data;
	}
	
	/**
	 * @throws \Exception
	 */
	public function getCSVStructure() {
		$product_detail = [];
		$tax            = [];
		$group          = $this->get_grouped_attributes();
		$attributes     = $this->config->attributes;
		$mattributes    = $this->config->mattributes;
		$static         = $this->config->default;
		$type           = $this->config->type;
		$data           = [];
		$shipping       = false;
		
		foreach ( $mattributes as $key => $attribute ) {
			if ( empty( $attribute ) ) {
				continue;
			}
			
			$installment_sub  = str_replace( "installment_", "", $attribute );
			$subscription_sub = str_replace( "subscription_", "", $attribute );
			$tax_attrs        = substr_count( implode( '|', $mattributes ), 'tax_' );
			$attributeValue   = ( $type[ $key ] === 'pattern' ) ? $static[ $key ] : $attributes[ $key ];
			
			
			$product_detail_label    = MerchantAttributeReplaceFactory::replace_attribute( 'product_detail', $this->config );
			$shipping_label          = MerchantAttributeReplaceFactory::replace_attribute( 'shipping', $this->config );
			$product_highlight_label = MerchantAttributeReplaceFactory::replace_attribute( 'product_highlight', $this->config );
			
			
			if ( strpos( $attribute, 'images_' ) !== false ) {
				$data['additional_image_link'][] = $attributeValue;
			} elseif ( strpos( $attribute, 'installment_' ) !== false ) {
				$data['installment'][ $installment_sub ] = $attributeValue;
			} elseif ( strpos( $attribute, 'subscription_' ) !== false ) {
				$data['subscription_cost'][ $subscription_sub ] = $attributeValue;
			} elseif ( strpos( $attribute, 'product_highlight_' ) !== false ) {
				$data[][ $product_highlight_label ] = $attributeValue;
			} elseif ( in_array( $attribute, $group['product_detail'], true ) ) {
				if ( $attribute === 'section_name' || $attribute === 'attribute_name' ) {
					$product_detail[ $attribute ] = $attributeValue;
				} elseif ( $attribute === 'attribute_value' ) {
					$product_detail[ $attribute ]    = $attributeValue;
					$data[ $product_detail_label ][] = $product_detail;
					$product_detail                  = [];
				}
			} elseif ( in_array( $attribute, $group['shipping'], true ) ) {
				$shipping = true;
			} elseif ( $attribute === 'shipping' ) {
				continue;
			}elseif ( $attribute === 'tax' ) {
				$replacedAttribute = MerchantAttributeReplaceFactory::replace_attribute( $attribute, $this->config );
				$taxes             = $this->get_taxes();
				if ( ! empty( $taxes ) ) {
					foreach ( $taxes as $rates ) {
						if ( $rates > 0 ) {
							for ( $i = 0; $i < $rates; $i ++ ) {
								$data[][ $replacedAttribute ] = "csv_tax_" . $i;
							}
						}
					}
				}
			} else {
				$replacedAttribute            = MerchantAttributeReplaceFactory::replace_attribute( $attribute, $this->config );
				$data[][ $replacedAttribute ] = $attributeValue;
			}
		}
		
		if ( array_key_exists( 'subscription_cost', $data ) && ! empty( $data['subscription_cost'] ) ) {
			$data[]['subscription_cost'] = implode( ':', array_values( $data['subscription_cost'] ) );
			unset( $data['subscription_cost'] );
		}
		
		if ( array_key_exists( 'installment', $data ) && ! empty( $data['installment'] ) ) {
			$data[]['installment'] = implode( ':', array_values( $data['installment'] ) );
			unset( $data['installment'] );
		}
		
		if ( array_key_exists( 'additional_image_link', $data ) && ! empty( $data['additional_image_link'] ) ) {
			$imageLinks = $data['additional_image_link'];
			unset( $data['additional_image_link'] );
			$data[]['additional_image_link'] = implode( ',', array_values( $imageLinks ) );
		}
		
		if ( array_key_exists( 'product_detail', $data ) && ! empty( $data['product_detail'] ) ) {
			foreach ( $data['product_detail'] as $detail ) {
				$product_detail[] = implode( ':', array_values( $detail ) );
			}
			$data[]['product_detail'] = implode( ',', array_values( $product_detail ) );
			unset( $data['product_detail'] );
		}
		
		if ( $shipping ) {
			$allow_all_shipping = Settings::get( 'allow_all_shipping' );
			$country            = $this->config->get_shipping_country();
			
			$methods = ( ShippingFactory::get( [], $this->config ) )->get_shipping_info();
			if ( 'no' === $allow_all_shipping ) {
				$methods = array_filter( $methods, static function ( $var ) use ( $country ) {
					return ( $var['country'] === $country );
				} );
			}
			
			if ( ! empty( $methods ) ) {
				$iMax = count( $methods );
				for ( $i = 0; $i < $iMax; $i ++ ) {
					$data[][ 'shipping(' . implode( ':', $group['shipping'] ) . ')' ] = "csv_shipping_" . $i;
				}
			}
		}
		
		unset( $data['shipping'] );
		
		return $data;
	}
	
	public function get_taxes() {
		
		$all_tax_rates = [];
		// Retrieve all tax classes.
		$tax_classes = WC_Tax::get_tax_classes();
		
		// Make sure "Standard rate" (empty class name) is present.
		if ( ! in_array( '', $tax_classes, true ) ) {
			array_unshift( $tax_classes, '' );
		}
		
		// For each tax class, get all rates.
		if ( ! empty( $tax_classes ) ) {
			foreach ( $tax_classes as $tax_class ) {
				$tax_class_name                    = ( '' === $tax_class ) ? 'standard-rate' : $tax_class;
				$all_tax_rates [ $tax_class_name ] = count( WC_Tax::get_rates_for_tax_class( $tax_class ) );
			}
		}
		
		return ! empty( $all_tax_rates ) ? $all_tax_rates : false;
	}
	
	public function getTSVStructure() {
		return $this->getCSVStructure();
	}
	
	public function getTXTStructure() {
		return $this->getCSVStructure();
	}
	
	public function getXLSStructure() {
		return $this->getCSVStructure();
	}
	
	public function getJSONStructure() {
		return $this->getCSVStructure();
	}
}

