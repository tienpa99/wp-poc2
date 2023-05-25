<?php

namespace CTXFeed\V5\Template;

use CTXFeed\V5\Output\FormatOutput;
use CTXFeed\V5\Utility\Config;
use CTXFeed\V5\File\FileFactory;
use CTXFeed\V5\Helper\FeedHelper;
use CTXFeed\V5\Helper\ProductHelper;
use CTXFeed\V5\Product\AttributeValueByType;
use CTXFeed\V5\Product\ProductFactory;
use CTXFeed\V5\Product\ProductInfo;
use CTXFeed\V5\Utility\Settings;

class CustomXMLTemplate implements TemplateInterface {
	/**
	 * @var Config $config Contain Feed Config.
	 */
	private $config;
	/**
	 * @var array $ids Contain Product Ids.
	 */
	private $ids;
	/**
	 * @var array $structure Contain Feed Structure.
	 */
	private $structure;

	private $feedHeader;
	/**
	 * This variable is responsible for holding feed string
	 *
	 * @since   1.0.0
	 * @var     string $feedString Contains feed information
	 * @access  public
	 */
	private $feedString;
	private $feedFooter;
	private $s;
	private $productEngine;
	private $variationElementsStart;

	public function __construct( $ids, $config ) {
		$this->ids                    = $ids;
		$this->config                 = $config;
		$getStructure                 = TemplateFactory::get_structure( $config );
		$this->structure              = $getStructure['structure'];
		$this->variationElementsStart = $getStructure['variationElementsStart'];
	}

	/**
	 * Get Feed Body.
	 *
	 * @return string
	 */
	public function get_feed() {
//		$feed = ProductFactory::get_content( $this->ids, $this->config ,$this->structure);
//		$feed = $feed->make_body();
//
//		return self::removeHeaderFooter( $feed );

		// Get XML Elements from feed config
		$Elements = $this->structure;

		if ( ! empty( $this->structure ) && ! empty( $this->ids ) ) {
			foreach ( $this->ids as $pid ) {
				$product             = wc_get_product( $pid );
				$this->productEngine = new ProductInfo( $product, $this->config );
				//TODO: PRODUCT VALIDATION AND FILTER IMPLEMENTATION


				//TODO Filter products by Condition
//                if (isset($this->config['fattribute']) && count($this->config['fattribute']) && !$this->productEngine->filter_product($product)) {


				// Start making XML Elements
				foreach ( $Elements as $each => $element ) {


					if ( $each === $this->variationElementsStart && $product->is_type( 'variable' ) && $product->has_child() ) {

						$variations = $product->get_children();
						foreach ( $variations as $variation ) {
							$variation = wc_get_product( $variation );
							foreach ( $Elements as $variationElement ) {
								if ( $variationElement['for'] === 'variation' ) {
//									$element['elementTextInfo'] = '';
//									$element['attr_type']       = 'text';
//									$element['attr_value']      = $image;
//									unset( $variationElement['attr_code'] );
									$this->feedString .= $this->make_xml_element( $variationElement, $variation );
								}
							}
						}

					}

					if ( $element['for'] === 'variation' ) {
						continue;
					}

					if ( $element['for'] === 'ifVariationAvailable' && $product->get_type() !== 'variable' ) {
						continue;
					}

					if ( $element['for'] === 'images' && isset( $element['attr_code'] ) ) {
						$images = $this->productEngine->custom_xml_images( $product );
						if ( ! empty( $images ) ) {
							foreach ( $images as $image ) {
								$element['elementTextInfo'] = $image;
								$element['attr_type']       = 'text';
								$element['attr_value']      = $image;
								unset( $element['attr_code'] );
								$this->feedString .= $this->make_xml_element( $element, $product );
							}
						}
					} elseif ( $element['for'] === 'categories' && isset( $element['attr_code'] ) ) {
						$categories = $this->productEngine->custom_xml_categories( $product );
						if ( ! empty( $categories ) ) {
							foreach ( $categories as $category ) {
								$element['elementTextInfo'] = $category;
								$element['attr_type']       = 'text';
								$element['attr_value']      = $category;
								unset( $element['attr_code'] );
								$this->feedString .= $this->make_xml_element( $element, $product );
							}
						}
					} else {
						$this->feedString .= $this->make_xml_element( $element, $product );
					}
				}

			}
		}

		return $this->feedString;
	}

	/**
	 * Get Feed Header.
	 *
	 * @return string
	 */
	public function get_header() {
		$getHeader = explode( '{{each product start}}', $this->config->feed_config_custom2 );
		$header    = trim( $getHeader[0] );
		$getNodes  = explode( "\n", $header );

		if ( ! empty( $getNodes ) ) {
			foreach ( $getNodes as $value ) {
				// Add header info to feed file
				$value = preg_replace( '/\\\\/', '', $value );
				if ( strpos( $value, 'return' ) !== false ) {
					$return       = woo_feed_get_string_between( $value, '{(', ')}' );
					$return_value = $this->process_eval( $return );
					$value        = preg_replace( '/\{\(.*?\)\}/', $return_value, $value );
				}
				$this->feedHeader .= $value;
				$this->s          += 2;
			}
		} else {
			$this->feedHeader .= '<?xml version="1.0" encoding="utf-8" ?>' . "\n";
		}

		return $this->feedHeader;
	}

	/**
	 * Get Feed Footer.
	 *
	 * @return string
	 */
	public function get_footer() {
		$getFooter = explode( '{{each product end}}', $this->config->feed_config_custom2 );
		$getNodes  = explode( "\n", $getFooter[1] );
		if ( ! empty( $getNodes ) ) {
			foreach ( $getNodes as $value ) {
				$this->s -= 2;
				// Add header info to feed file
				$this->feedFooter .= $value;
			}
		}

		return $this->feedFooter;
	}

	/**
	 * @param $element
	 * @param $product
	 *
	 * @return string
	 */
	public function make_xml_element( $element, $product ) {
		$p      = false;
		$string = '';
		$start  = '';
		$end    = '';
		$output = '';


		$this->productEngine = new ProductInfo( $product, $this->config );

		// Start XML Element
		if (
			empty( $element['elementTextInfo'] ) && // Get the root element.
			empty( $element['end'] ) &&
			6 === count( $element )
		) {

			// Start XML Element
			$elementStart = $this->processStartingElement( $element, $product );

			$end    .= '<' . $elementStart . '>';
			$string .= $end . "\n";
			$p      = true;
		} elseif ( ! empty( $element['start'] ) ) {
			$elementStart = $this->processStartingElement( $element, $product );
			$start        .= '<' . $elementStart . '>';
		}

		// Make XML Element Text
		if ( ! empty( $element['elementTextInfo'] ) ) {
			if ( 'attribute' === $element['attr_type'] ) {
				$output = ProductHelper::getAttributeValueByType( $element['attr_code'], $product, $this->config );
				$output = ProductHelper::str_replace( $output, $element['attr_code'], $this->config );
			} elseif ( 'return' === $element['attr_type'] ) {
				$output = $this->getReturnTypeValue( $element, $product );
			} elseif ( 'php' === $element['attr_type'] ) {
				if ( isset( $element['to_return'] ) && ! empty( $element['to_return'] ) ) {
					$output = $this->returnPHPFunction( $element['to_return'] );
				}
			} elseif ( 'text' === $element['attr_type'] ) {
				$output = ( isset( $element['attr_value'] ) && ! empty( $element['attr_value'] ) ) ? $element['attr_value'] : '';
			}

			$pluginAttribute = null;
			if ( 'attribute' === $element['attr_type'] ) {
				$pluginAttribute = $element['attr_code'];
			}

			// Format output according to commands
			if ( array_key_exists( 'formatter', $element ) ) {
				$formatOutput = new FormatOutput( $product, $this->config, $pluginAttribute );
				$output       = $formatOutput->process_command( $output, $element['formatter'] );
			}
			$p = false;
		}

		// End XML Element
		if ( '/' . $element['end'] === $element['start'] && empty( $element['elementTextInfo'] ) && 6 === count( $element ) ) {
			if ( ! empty( $element['end'] ) ) {
				$end .= '<' . $element['start'] . '>';
			}
			$string .= $end . "\n";
			$p      = true;
		} else if ( ! empty( $element['end'] ) ) {
			$end .= '</' . $element['end'] . ">\n";
		}

		if ( ! $p ) {
			// Add Prefix and Suffix
			$prefix = isset( $element['prefix'] ) ? preg_replace( '!\s+!', ' ', $element['prefix'] ) : '';
			$suffix = isset( $element['suffix'] ) ? preg_replace( '!\s+!', ' ', $element['suffix'] ) : '';
//			$output = $this->productEngine->process_prefix_suffix( $output, $prefix, $suffix, isset( $element['attr_code'] ) ? $element['attr_code'] : '' );

			// Add CDATA if needed
			if ( ! empty( $output ) ) {
				$output = $this->addCDATA( $element['include_cdata'], $output );
			}

			$string .= $start . $output . $end;
			$p      = false;
		}

		return $string;
	}


	/**
	 * Add Quotation mark to store code value.
	 *
	 * @return string
	 */
	public function addQuotation( $string ) {
		return "'" . str_replace( array( "'", "\"", "&quot;" ), "", htmlspecialchars( $string ) ) . "'";
	}

	/**
	 * Remove Quotation mark from xml element.
	 *
	 * @return string
	 */
	public function removeQuotation( $string ) {
		return str_replace( array( "'", "\"", "&quot;" ), "", $string );
	}

	/**
	 * Extract Start Code attributes value and replace.
	 *
	 * @param $element
	 * @param $product
	 *
	 * @return array|string
	 */
	public function processStartingElement( $element, $product ) {
		$elementStart = stripslashes( $element['start'] );
		if ( ! empty( $element['start_code'] ) ) {
			$start_attr_codes = array();
			foreach ( $element['start_code'] as $attrValue ) {
				if ( strpos( $attrValue, 'return' ) !== false ) {
					$start_attr_code                                = woo_feed_get_string_between( $attrValue, '{(', ')}' );
					$tempAttribute                                  = array(
						'to_return' => $start_attr_code,
						'attr'      => $attrValue,
					);
					$start_attr_code                                = $this->getReturnTypeValue( $tempAttribute, $product );
					$start_attr_codes[ stripslashes( $attrValue ) ] = $this->addQuotation( $start_attr_code );

				} else {
					$start_attr_code                = woo_feed_get_string_between( $attrValue, '{', '}' );
					$start_attr_code                = $this->getAttributeTypeAndValue( $start_attr_code, $product );
					$start_attr_codes[ $attrValue ] = $this->addQuotation( $start_attr_code );

				}
			}
			$elementStart = str_replace( array_keys( $start_attr_codes ), array_values( $start_attr_codes ), $elementStart );
		}

		return $elementStart;
	}


	public function process_eval( $attribute ) {
		$return = preg_replace( '/\\\\/', '', $attribute );

		return eval( $return );
	}

	public function getReturnTypeValue( $attribute, $product ) {
		$variables = array();
		if ( ! empty( $attribute ) && strpos( $attribute['to_return'], '$' ) !== false ) {
			$pattern = '/\$\S+/';
			preg_match_all( $pattern, $attribute['to_return'], $matches, PREG_SET_ORDER );
			$matches = array_column( $matches, 0 );
			foreach ( $matches as $variable ) {
				if ( strpos( $variable, '$' ) !== false ) {
					$variable                             = str_replace( array( '$', ';' ), '', $variable );
					$attribute['attr_code']               = $variable;
					$variables[ $attribute['attr_code'] ] = $this->getAttributeTypeAndValue( $attribute['attr_code'], $product );
				}
			}
		}

		extract( $variables, EXTR_OVERWRITE ); // phpcs:ignore
		$return = $attribute['to_return'];
		$return = preg_replace( '/\\\\/', '', $return );

		return eval( $return );
	}

	public function getAttributeTypeAndValue( $attribute, $product ) {

		return ProductHelper::getAttributeValueByType( $attribute, $product, $this->config );

	}

	/** Return the php function of the attribute
	 *
	 * @param $function
	 *
	 * @return mixed
	 */
	private function returnPHPFunction( $function ) {
		return $function;
	}

	/** Add CDATA to String
	 *
	 * @param string $status
	 * @param string $output
	 *
	 * @return string
	 */
	private function addCDATA( $status, $output ) {
		if ( 'yes' === $status ) {
			$output = $this->removeCDATA( $output );

			return '<![CDATA[' . $output . ']]>';
		}

		return $output;
	}

	/** Remove CDATA from String
	 *
	 * @param string $output
	 *
	 * @return string
	 */
	private function removeCDATA( $output ) {
		return str_replace( [ "<![CDATA[", "]]>" ], "", $output );
	}
}
