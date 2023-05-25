<?php

namespace CTXFeed\V5\Helper;

use CTXFeed\V5\Product\AttributeValueByType;
use CTXFeed\V5\Query\QueryFactory;
use CTXFeed\V5\Utility\Config;
use DateTime;
use WC_Product;
use WC_Product_External;
use WC_Product_Grouped;
use WC_Product_Variable;
use WC_Product_Variation;

class ProductHelper {
	/**
	 * Advance Custom Field (ACF) Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_ACF_FIELDS = 'acf_fields_';
	/**
	 * Post meta prefix for dropdown item
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const POST_META_PREFIX = 'wf_cattr_';
	/**
	 * Product Attribute (taxonomy & local) Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_ATTRIBUTE_PREFIX = 'wf_attr_';
	/**
	 * Product Taxonomy Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_TAXONOMY_PREFIX = 'wf_taxo_';
	/**
	 * Product Category Mapping Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_CATEGORY_MAPPING_PREFIX = 'wf_cmapping_';
	/**
	 * Product Dynamic Attribute Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_DYNAMIC_ATTRIBUTE_PREFIX = 'wf_dattribute_';
	/**
	 * WordPress Option Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const WP_OPTION_PREFIX = 'wf_option_';
	/**
	 * Extra Attribute Prefix
	 *
	 * @since 3.2.20
	 */
	const PRODUCT_EXTRA_ATTRIBUTE_PREFIX = 'wf_extra_';
	/**
	 * Product Attribute Mappings Prefix
	 *
	 * @since 3.3.2*
	 */
	const PRODUCT_ATTRIBUTE_MAPPING_PREFIX = 'wp_attr_mapping_';
	/**
	 * Product Custom Field Prefix
	 *
	 * @since 3.1.18
	 * @var string
	 */
	const PRODUCT_CUSTOM_IDENTIFIER = 'woo_feed_';

	/**
	 * @param Config $config Feed Configuration.
	 * @param array  $args   Query Arguments
	 *
	 * @return array
	 */
	public static function get_ids( $config, $args = [], $settings = null ) {
		return QueryFactory::get_ids( $config, $args, $settings );
	}

	/**
	 * Get Product Gallery Items (URL) array.
	 * This can contains empty array values
	 *
	 * @param WC_Product|WC_Product_Variable|WC_Product_Variation|WC_Product_Grouped|WC_Product_External|WC_Product_Composite $product
	 *
	 * @return string[]
	 * @since 3.2.6
	 */
	public static function get_product_gallery( $product ) {
		$imgUrls       = [];
		$attachmentIds = [];

		if ( $product->is_type( 'variation' ) ) {
			if ( class_exists( 'Woo_Variation_Gallery' ) ) {
				/**
				 * Get Variation Additional Images for "Additional Variation Images Gallery for WooCommerce"
				 *
				 * @plugin Additional Variation Images Gallery for WooCommerce
				 * @link   https://wordpress.org/plugins/woo-variation-gallery/
				 */
				$attachmentIds = get_post_meta( $product->get_id(), 'woo_variation_gallery_images', true );
			} elseif ( class_exists( 'WooProductVariationGallery' ) ) {
				/**
				 * Get Variation Additional Images for "Variation Images Gallery for WooCommerce"
				 *
				 * @plugin Variation Images Gallery for WooCommerce
				 * @link   https://wordpress.org/plugins/woo-product-variation-gallery/
				 */
				$attachmentIds = get_post_meta( $product->get_id(), 'rtwpvg_images', true );
			} elseif ( class_exists( 'WC_Additional_Variation_Images' ) ) {
				/**
				 * Get Variation Additional Images for "WooCommerce Additional Variation Images"
				 *
				 * @plugin WooCommerce Additional Variation Images
				 * @link   https://woocommerce.com/products/woocommerce-additional-variation-images/
				 */
				$attachmentIds = explode( ',', get_post_meta( $product->get_id(), '_wc_additional_variation_images', true ) );
			} elseif ( class_exists( 'WOODMART_Theme' ) ) {
				/**
				 * Get Variation Additional Images for "WOODMART Theme -> Variation Gallery Images Feature"
				 *
				 * @theme WOODMART
				 * @link  https://themeforest.net/item/woodmart-woocommerce-wordpress-theme/20264492
				 */
				$var_id    = $product->get_id();
				$parent_id = $product->get_parent_id();

				$variation_obj = get_post_meta( $parent_id, 'woodmart_variation_gallery_data', true );
				if ( isset( $variation_obj, $variation_obj[ $var_id ] ) ) {
					$attachmentIds = explode( ',', $variation_obj[ $var_id ] );
				} else {
					$attachmentIds = explode( ',', get_post_meta( $var_id, 'wd_additional_variation_images_data', true ) );
				}
			} else {
				/**
				 * If any Variation Gallery Image plugin not installed then get Variable Product Additional Image Ids .
				 */
				$attachmentIds = wc_get_product( $product->get_parent_id() )->get_gallery_image_ids();
			}
		}

		/**
		 * Get Variable Product Gallery Image ids if Product is not a variation
		 * or variation does not have any gallery images
		 *
		 * Test case write is pending
		 */
		if ( empty( $attachmentIds ) ) {
			$attachmentIds = $product->get_gallery_image_ids();
		}

		if ( $attachmentIds && is_array( $attachmentIds ) ) {
			$mKey = 1;
			foreach ( $attachmentIds as $attachmentId ) {
				$imgUrls[ $mKey ] = woo_feed_get_formatted_url( wp_get_attachment_url( $attachmentId ) );
				$mKey ++;
			}
		}

		return $imgUrls;
	}

	/**
	 * Get Product Meta Value.
	 *
	 * @param            $meta
	 * @param WC_Product $product
	 * @param            $config
	 *
	 * @return mixed|void
	 */
	public static function get_product_meta( $meta, $product, $config ) {
		$value = get_post_meta( $product->get_id(), $meta, true );
		// if empty get meta value of parent post
		if ( '' === $value && $product->is_type( 'variation' ) ) {
			$value = get_post_meta( $product->get_parent_id(), $meta, true );
		}

		return apply_filters( 'woo_feed_filter_product_meta', $value, $product, $config );
	}

	/**
	 * Get Product Custom Field values.
	 *
	 * @param            $field
	 * @param WC_Product $product
	 * @param            $config
	 *
	 * @return mixed
	 */
	public static function get_custom_filed( $field, $product, $config ) {
		$meta = $field;
		if ( $product->is_type( 'variation' ) ) {
			$meta = $field . '_var';
		}

		$meta = apply_filters( 'woo_feed_custom_field_meta', $meta, $product, $config );

		if ( strpos( $meta, '_identifier' ) !== false ) {
			$old_meta_key = $meta;
			$new_meta_key = str_replace( '_identifier', '', $meta );
		} else {
			$new_meta_key = $meta;
			$old_meta_key = str_replace( 'woo_feed_', 'woo_feed_identifier_', $meta );
		}

		$new_meta_value = self::get_product_meta( $new_meta_key, $product, $config );
		$old_meta_value = self::get_product_meta( $old_meta_key, $product, $config );

		if ( empty( $new_meta_value ) ) {
			return self::format_custom_field_value( $old_meta_value, $meta );
		}

		return self::format_custom_field_value( $new_meta_value, $meta );
	}

	private static function format_custom_field_value( $value, $meta ) {
		if ( strpos( $meta, 'availability_date' ) ) {
			return date( 'c', strtotime( $value ) );
		}

		return $value;
	}

	/**
	 * Get Product Taxonomy.
	 *
	 * @param string     $taxonomy
	 * @param WC_Product $product
	 * @param Config     $config
	 *
	 * @return mixed|void
	 *
	 * Test case write is pending
	 */
	public static function get_product_taxonomy( $taxonomy, $product, $config ) {
		$id        = woo_feed_parent_product_id( $product );
		$separator = apply_filters( 'woo_feed_product_taxonomy_term_list_separator', ',', $config, $product );
		$term_list = get_the_term_list( $id, $taxonomy, '', $separator, '' );

		if ( is_object( $term_list ) && get_class( $term_list ) === 'WP_Error' ) {
			$term_list = '';
		}
		$getTaxonomy = woo_feed_strip_all_tags( $term_list );

		return apply_filters( 'woo_feed_filter_product_taxonomy', $getTaxonomy, $product, $config );
	}

	/**
	 * Ger Product Attribute
	 *
	 * @param                            $attr
	 *
	 * @param                            $product
	 * @param \CTXFeed\V5\Utility\Config $config
	 *
	 * @return string
	 * @since 2.2.3
	 */
	public static function get_product_attribute( $attr, $product, $config ) {
		$id = $product->get_id();

		if ( woo_feed_wc_version_check( 3.2 ) ) {
			if ( woo_feed_wc_version_check( 3.6 ) ) {
				$attr = str_replace( 'pa_', '', $attr );
			}
			$value = $product->get_attribute( $attr );

			// if empty get attribute of parent post
			if ( '' === $value && $product->is_type( 'variation' ) ) {
				$product = wc_get_product( $product->get_parent_id() );
				$value   = $product->get_attribute( $attr );
			}

			$getAttribute = $value;
		} else {
			$getAttribute = implode( ',', wc_get_product_terms( $id, $attr, array( 'fields' => 'names' ) ) );
		}

		return apply_filters( 'woo_feed_filter_product_attribute', $getAttribute, $attr, $product, $config );
	}

	/**
	 * Get ACF Field values
	 *
	 * @param WC_Product $product
	 * @param string     $field_key ACF Filed Key with prefix "acf_fields_"
	 *
	 * @return mixed|string
	 *
	 * Test case write is pending
	 */
	public
	static function get_acf_field(
		$product, $field_key
	) {
		$field = str_replace( 'acf_fields_', '', $field_key );
		if ( class_exists( 'ACF' ) ) {
			return get_field( $field, $product->get_id() );
		}

		return '';
	}

	/**
	 * Return Category Mapping Values by Product Id [Parent Product for variation]
	 *
	 * @param string $mappingName Category Mapping Name
	 * @param int    $product_id  Product ID / Parent Product ID for variation product
	 *
	 * @return mixed
	 *
	 * This function already exist on CTXFeed\V5\Output\CategoryMapping.
	 * Test case already written on CTXFeed\tests\wpunit\Output\CategoryMappingTest.
	 *
	 */
	public
	static function get_category_mapping(
		$mappingName, $product_id
	) {
		$getValue                           = maybe_unserialize( get_option( $mappingName ) );
		$cat_map_value                      = '';
		$suggestive_category_list_merchants = array(
			'google',
			'facebook',
			'pinterest',
			'bing',
			'bing_local_inventory',
			'snapchat'
		);

		if ( ! isset( $getValue['cmapping'] ) && ! isset( $getValue['gcl-cmapping'] ) ) {
			return '';
		}

		//get product terms
		$categories = get_the_terms( $product_id, 'product_cat' );

		//get cmapping value
		if ( isset( $getValue['gcl-cmapping'] ) && in_array( $getValue['mappingprovider'], $suggestive_category_list_merchants, true ) ) {
			$cmapping = is_array( $getValue['gcl-cmapping'] ) ? array_reverse( $getValue['gcl-cmapping'], true ) : $getValue['gcl-cmapping'];
		} else {
			$cmapping = is_array( $getValue['cmapping'] ) ? array_reverse( $getValue['cmapping'], true ) : $getValue['cmapping'];
		}

		// Fixes empty mapped category issue
		if ( ! empty( $categories ) && is_array( $categories ) && count( $categories ) ) {
			$categories = array_reverse( $categories );
			foreach ( $categories as $category ) {
				if ( isset( $cmapping[ $category->term_id ] ) && ! empty( $cmapping[ $category->term_id ] ) ) {
					$cat_map_value = $cmapping[ $category->term_id ];
					break;
				}
			}
		}

		return $cat_map_value;
	}

	/**
	 * Get Attribute Mapping Value.
	 *
	 * @param WC_Product $product
	 * @param            $attribute
	 * @param            $merchant_attribute
	 * @param            $config
	 *
	 * @return string
	 *
	 * This function already exist on CTXFeed\V5\Output\AttributesMapping.
	 * Test case already written on CTXFeed\tests\wpunit\Output\AttributesMappingTest.
	 *
	 */
	public
	static function get_attribute_mapping(
		$product, $attribute, $merchant_attribute, $config
	) {
		$getAttributeValueByType = new AttributeValueByType( $attribute, $merchant_attribute, $product, $config );
		$attributes              = get_option( $attribute );
		$glue                    = ! empty( $attributes['glue'] ) ? $attributes['glue'] : " ";
		$output                  = '';

		if ( isset( $attributes['mapping'] ) ) {
			foreach ( $attributes['mapping'] as $map ) {
				$get_value = $getAttributeValueByType->get_value( $map );
				if ( ! empty( $get_value ) ) {
					$output .= $glue . $get_value;
				}
			}
		}

		//trim extra glue
		$output = trim( $output, $glue );

		// remove extra whitespace
		$output = preg_replace( '!\s\s+!', ' ', $output );

		return apply_filters( 'woo_feed_filter_attribute_mapping', $output, $attribute, $product, $config );
	}

	/**
	 * Get the value of a dynamic attribute
	 *
	 * @param WC_Product $product
	 * @param            $attributeName
	 * @param            $merchant_attribute
	 * @param            $config
	 *
	 * @return mixed|string
	 * @since 3.2.0
	 *
	 * This function already exist on CTXFeed\V5\Output\DynamicAttributes.
	 * Test case already written on CTXFeed\tests\wpunit\Output\DynamicAttributesTest.
	 *
	 */
	public
	static function get_dynamic_attribute(
		$product, $attributeName, $merchant_attribute, $config
	) {
		$getAttributeValueByType = new AttributeValueByType( $attributeName, $merchant_attribute, $product, $config );
		$getValue                = maybe_unserialize( get_option( $attributeName ) );
		$wfDAttributeCode        = isset( $getValue['wfDAttributeCode'] ) ? $getValue['wfDAttributeCode'] : '';
		$attribute               = isset( $getValue['attribute'] ) ? (array) $getValue['attribute'] : array();
		$condition               = isset( $getValue['condition'] ) ? (array) $getValue['condition'] : array();
		$compare                 = isset( $getValue['compare'] ) ? (array) $getValue['compare'] : array();
		$type                    = isset( $getValue['type'] ) ? (array) $getValue['type'] : array();

		$prefix = isset( $getValue['prefix'] ) ? (array) $getValue['prefix'] : array();
		$suffix = isset( $getValue['suffix'] ) ? (array) $getValue['suffix'] : array();

		$value_attribute = isset( $getValue['value_attribute'] ) ? (array) $getValue['value_attribute'] : array();
		$value_pattern   = isset( $getValue['value_pattern'] ) ? (array) $getValue['value_pattern'] : array();

		$default_type            = isset( $getValue['default_type'] ) ? $getValue['default_type'] : 'attribute';
		$default_value_attribute = isset( $getValue['default_value_attribute'] ) ? $getValue['default_value_attribute'] : '';
		$default_value_pattern   = isset( $getValue['default_value_pattern'] ) ? $getValue['default_value_pattern'] : '';

		$result = '';

		// Check If Attribute Code exist
		if ( $wfDAttributeCode && count( $attribute ) ) {
			foreach ( $attribute as $key => $name ) {
				if ( ! empty( $name ) ) {
					$conditionName = $getAttributeValueByType->get_value( $name );
					if ( 'weight' === $name ) {
						$unit = ' ' . get_option( 'woocommerce_weight_unit' );
						if ( ! empty( $unit ) ) {
							$conditionName = (float) str_replace( $unit, '', $conditionName );
						}
					}

					$conditionCompare  = $compare[ $key ];
					$conditionOperator = $condition[ $key ];

					if ( ! empty( $conditionCompare ) ) {
						$conditionCompare = trim( $conditionCompare );
					}
					$conditionValue = '';
					if ( 'pattern' === $type[ $key ] ) {
						$conditionValue = $value_pattern[ $key ];
					} elseif ( 'attribute' === $type[ $key ] ) {
						$conditionValue = $getAttributeValueByType->get_value( $value_attribute[ $key ] );
					} elseif ( 'remove' === $type[ $key ] ) {
						$conditionValue = '';
					}

					switch ( $conditionOperator ) {
						case '==':
							if ( $conditionName == $conditionCompare ) {
								$result = self::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case '!=':
							if ( $conditionName != $conditionCompare ) {
								$result = self::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case '>=':
							if ( $conditionName >= $conditionCompare ) {
								$result = self::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}

							break;
						case '<=':
							if ( $conditionName <= $conditionCompare ) {
								$result = self::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case '>':
							if ( $conditionName > $conditionCompare ) {
								$result = self::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case '<':
							if ( $conditionName < $conditionCompare ) {
								$result = self::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case 'contains':
							if ( false !== stripos( $conditionName, $conditionCompare ) ) {
								$result = self::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case 'nContains':
							if ( stripos( $conditionName, $conditionCompare ) === false ) {
								$result = self::price_format( $name, $conditionName, $conditionValue );
								if ( '' !== $result ) {
									$result = $prefix[ $key ] . $result . $suffix[ $key ];
								}
							}
							break;
						case 'between':
							$compare_items = explode( ',', $conditionCompare );

							if ( isset( $compare_items[1] ) && is_numeric( $compare_items[0] ) && is_numeric( $compare_items[1] ) ) {
								if ( $conditionName >= $compare_items[0] && $conditionName <= $compare_items[1] ) {
									$result = self::price_format( $name, $conditionName, $conditionValue );
									if ( '' !== $result ) {
										$result = $prefix[ $key ] . $result . $suffix[ $key ];
									}
								}
							} else {
								$result = '';
							}
							break;
						default:
							break;
					}
				}
			}
		}

		if ( '' === $result ) {
			if ( 'pattern' === $default_type ) {
				$result = $default_value_pattern;
			} elseif ( 'attribute' === $default_type ) {
				if ( ! empty( $default_value_attribute ) ) {
					$result = $getAttributeValueByType->get_value( $default_value_attribute );
				}
			} elseif ( 'remove' === $default_type ) {
				$result = '';
			}
		}

		return apply_filters( 'woo_feed_after_dynamic_attribute_value', $result, $product, $attributeName, $merchant_attribute, $config );
	}

	/**
	 * Format price value
	 *
	 * @param string $name          Attribute Name
	 * @param int    $conditionName condition
	 * @param int    $result        price
	 *
	 * @return array|float|int|string
	 * @since 3.2.0
	 */
	public
	static function price_format(
		$name, $conditionName, $result
	) {
		// calc and return the output.
		if ( false !== strpos( $name, 'price' ) || false !== strpos( $name, 'weight' ) ) {
			if ( false !== strpos( $result, '+' ) && false !== strpos( $result, '%' ) ) {
				$result = str_replace_trim( '+', '', $result );
				$result = str_replace_trim( '%', '', $result );
				if ( is_numeric( $result ) ) {
					$result = $conditionName + ( ( $conditionName * $result ) / 100 );
				}
			} elseif ( false !== strpos( $result, '-' ) && false !== strpos( $result, '%' ) ) {
				$result = str_replace_trim( '-', '', $result );
				$result = str_replace_trim( '%', '', $result );
				if ( is_numeric( $result ) ) {
					//$result = ( ( $conditionName * $result ) / 100 ) - $conditionName;
					$result = $conditionName - ( ( $conditionName * $result ) / 100 );
				}
			} elseif ( false !== strpos( $result, '*' ) && false !== strpos( $result, '%' ) ) {
				$result = str_replace_trim( '*', '', $result );
				$result = str_replace_trim( '%', '', $result );
				if ( is_numeric( $result ) ) {
					$result = ( ( $conditionName * $result ) / 100 );
				}
			} elseif ( false !== strpos( $result, '+' ) ) {
				$result = str_replace_trim( '+', '', $result );
				if ( is_numeric( $result ) ) {
					$result = ( $conditionName + $result );
				}
			} elseif ( false !== strpos( $result, '-' ) ) {
				$result = str_replace_trim( '-', '', $result );
				if ( is_numeric( $result ) ) {
					$result = $conditionName - $result;
				}
			} elseif ( false !== strpos( $result, '*' ) ) {
				$result = str_replace_trim( '*', '', $result );
				if ( is_numeric( $result ) ) {
					$result = ( $conditionName * $result );
				}
			} elseif ( false !== strpos( $result, '/' ) ) {
				$result = str_replace_trim( '/', '', $result );
				if ( is_numeric( $result ) ) {
					$result = ( $conditionName / $result );
				}
			}
		}

		return $result;
	}

	/**
	 * Get date is Validate
	 *
	 * @param date $date Date
	 * @param string $format Date Format
	 *
	 * @return boolean
	 */

	public static function validateDate($date, $format = 'Y-m-d')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}

	/**
	 * Get attribute value by type
	 *
	 * @param string     $attribute Product attribute
	 * @param WC_Product $product
	 * @param Config     $config    Configuration
	 *
	 * @return mixed
	 */
	public
	static function getAttributeValueByType(
		$attribute, $product, $config, $merchant_attribute = null
	) {
		return ( new AttributeValueByType( $attribute, $product, $config, $merchant_attribute ) )->get_value();
	}

	/**
	 * @param string $output
	 * @param string $productAttribute
	 * @param Config $config
	 *
	 * @return string
	 *
	 * Test case write is pending
	 */
	public
	static function str_replace(
		$output, $productAttribute, $config
	) {
		// str_replace array can contain duplicate subjects, so better loop through...
		foreach ( $config->get_string_replace() as $str_replace ) {
			if ( empty( $str_replace['subject'] ) || $productAttribute !== $str_replace['subject'] ) {
				continue;
			}

			if ( strpos( $str_replace['search'], '/' ) === false ) {
				$output = preg_replace( stripslashes( '/' . $str_replace['search'] . '/mi' ), $str_replace['replace'], $output );
			} else {
				$output = str_replace( $str_replace['search'], $str_replace['replace'], $output );
			}
		}

		return $output;
	}
}
