<?php


/**
 * Class to create a minified css output.
 */
class Premium_Blocks_css {


	/**
	 * The css selector that you're currently adding rules to
	 *
	 * @access protected
	 * @var string
	 */
	protected $_selector = '';

	/**
	 * Associative array of Google Fonts to load.
	 *
	 * Do not access this property directly, instead use the `get_google_fonts()` method.
	 *
	 * @var array
	 */
	protected static $google_fonts = array();
	public static $footer_gfonts   = array();
	public static $gfonts          = array();


	/**
	 * Stores the final css output with all of its rules for the current selector.
	 *
	 * @access protected
	 * @var string
	 */
	protected $_selector_output = '';

	/**
	 * Can store a list of additional selector states which can be added and removed.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_selector_states = array();



	/**
	 * Stores all of the rules that will be added to the selector
	 *
	 * @access protected
	 * @var string
	 */
	protected $_css = '';

	/**
	 * Stores all of the custom css.
	 *
	 * @access protected
	 * @var string
	 */
	protected $_css_string = '';

	/**
	 * The string that holds all of the css to output
	 *
	 * @access protected
	 * @var array
	 */
	protected $_output = '';

	/**
	 * Stores media queries
	 *
	 * @var null
	 */
	protected $_media_query = null;

	/**
	 * The string that holds all of the css to output inside of the media query
	 *
	 * @access protected
	 * @var string
	 */
	protected $_media_query_output = '';


	public function __construct() {
		add_action( 'wp_head', array( $this, 'frontend_gfonts' ), 90 );
		add_action( 'wp_footer', array( $this, 'frontend_footer_gfonts' ), 90 );
		$this->_output = array(
			'desktop' => '',
			'tablet'  => '',
			'mobile'  => '',
		);
	}


	public function frontend_gfonts() {
		if ( empty( self::$gfonts ) ) {
			return;
		}
		$print_google_fonts = apply_filters( 'pbg_blocks_print_google_fonts', true );
		if ( ! $print_google_fonts ) {
			return;
		}
		// Load Google Fonts.
		PBG_Fonts::set_fonts( self::$gfonts );
		PBG_Fonts::render_fonts();
	}

	/**
	 * Load Google Fonts in Frontend
	 */
	public function frontend_footer_gfonts() {
		if ( empty( self::$footer_gfonts ) ) {
			return;
		}
		$print_google_fonts = apply_filters( 'premium_blocks_print_footer_google_fonts', true );
		if ( ! $print_google_fonts ) {
			return;
		}
		// Load Google Fonts.
		PBG_Fonts::set_fonts( $print_google_fonts );
		PBG_Fonts::render_fonts();
	}

	/**
	 * Sets a selector to the object and changes the current selector to a new one
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param  string $selector - the css identifier of the html that you wish to target.
	 * @return $this
	 */
	public function set_selector( $selector = '' ) {
		// Render the css in the output string everytime the selector changes.
		if ( '' !== $this->_selector ) {
			$this->add_selector_rules_to_output();
		}
		$this->_selector = $selector;
		return $this;
	}
	/**
	 * Sets css string for final output.
	 *
	 * @param  string $string - the css string.
	 * @return $this
	 */
	public function add_css_string( $string ) {
		$this->_css_string .= $string;
		return $this;
	}

	/**
	 * Wrapper for the set_selector method, changes the selector to add new rules
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @see    set_selector()
	 * @param  string $selector the css selector.
	 * @return $this
	 */
	public function change_selector( $selector = '' ) {
		return $this->set_selector( $selector );
	}

	/**
	 * Adds a pseudo class to the selector ex. :hover, :active, :focus
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param  $state - the selector state
	 * @param  reset - if true the        $_selector_states variable will be reset
	 * @return $this
	 */
	public function add_selector_state( $state, $reset = true ) {
		if ( $reset ) {
			$this->reset_selector_states();
		}
		$this->_selector_states[] = $state;
		return $this;
	}

	/**
	 * Adds multiple pseudo classes to the selector
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param  array $states - the states you would like to add
	 * @return $this
	 */
	public function add_selector_states( $states = array() ) {
		$this->reset_selector_states();
		foreach ( $states as $state ) {
			$this->add_selector_state( $state, false );
		}
		return $this;
	}

	/**
	 * Removes the selector's pseudo classes
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @return $this
	 */
	public function reset_selector_states() {
		$this->add_selector_rules_to_output();
		if ( ! empty( $this->_selector_states ) ) {
			$this->_selector_states = array();
		}
		return $this;
	}

	/**
	 * Adds a new rule to the css output
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param  string $property - the css property.
	 * @param  string $value - the value to be placed with the property.
	 * @param  string $prefix - not required, but allows for the creation of a browser prefixed property.
	 * @return $this
	 */
	public function add_rule( $property, $value, $prefix = null ) {
		$format = is_null( $prefix ) ? '%1$s:%2$s;' : '%3$s%1$s:%2$s;';
		if ( $value && ! empty( $value ) ) {
			$this->_css .= sprintf( $format, $property, $value, $prefix );
		}
		return $this;
	}



	/**
	 * Adds a css property with value to the css output
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param  string $property - the css property
	 * @param  string $value - the value to be placed with the property
	 * @return $this
	 */
	public function add_property( $property, $value = null ) {
		if ( null === $value ) {
			return $this;
		}

		$this->add_rule( $property, $value );

		return $this;
	}

	/**
	 * Adds multiple properties with their values to the css output
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @param  array $properties - a list of properties and values
	 * @return $this
	 */
	public function add_properties( $properties ) {
		foreach ( (array) $properties as $property => $value ) {
			$this->add_property( $property, $value );
		}
		return $this;
	}

	/**
	 * Sets a media query in the class
	 *
	 * @since  1.1
	 * @param  string $value
	 * @return $this
	 */
	public function start_media_query( $value ) {
		// Add the current rules to the output
		$this->add_selector_rules_to_output();

		// Add any previous media queries to the output
		if ( $this->has_media_query() ) {
			$this->add_media_query_rules_to_output();
		}

		// Set the new media query
		$this->_media_query = $value;
		return $this;
	}

	/**
	 * Stops using a media query.
	 *
	 * @see    start_media_query()
	 *
	 * @since  1.1
	 * @return $this
	 */
	public function stop_media_query() {
		return $this->start_media_query( null );
	}

	/**
	 * Gets the media query if it exists in the class
	 *
	 * @since  1.1
	 * @return string|int|null
	 */
	public function get_media_query() {
		 return $this->_media_query;
	}

	/**
	 * Checks if there is a media query present in the class
	 *
	 * @since  1.1
	 * @return boolean
	 */
	public function has_media_query() {
		if ( ! empty( $this->get_media_query() ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Adds the current media query's rules to the class' output variable
	 *
	 * @since  1.1
	 * @return $this
	 */
	private function add_media_query_rules_to_output() {
		if ( ! empty( $this->_media_query_output ) ) {
			// Add the current media query's rules to the output.
			$media_query_output                   = $this->get_media_query();
			$this->_output[ $media_query_output ] = $this->_media_query_output;

			// Reset the media query output string.
			$this->_media_query_output = '';
		}

		return $this;
	}

	/**
	 * Adds the current selector rules to the output variable
	 *
	 * @access private
	 * @since  1.0
	 *
	 * @return $this
	 */
	private function add_selector_rules_to_output() {
		if ( ! empty( $this->_css ) ) {
			$this->prepare_selector_output();
			$selector_output = sprintf( '%1$s{%2$s}', $this->_selector_output, $this->_css );

			if ( $this->has_media_query() ) {
				// Add the current selector's rules to the media query output.
				$this->_media_query_output .= $selector_output;
				$this->reset_css();
			} else {
				// Add the current selector's rules to the desktop output.
				$this->_output['desktop'] .= $selector_output;
			}

			// Reset the css.
			$this->reset_css();
		}

		return $this;
	}

	public function render_color( $color, $opacity = null ) {
		if ( empty( $color ) || 'px' === $color || 'Default' === $color ) {
			return false;
		}
		return $color;
	}

	/**
	 * Prepares the $_selector_output variable for rendering
	 *
	 * @access private
	 * @since  1.0
	 *
	 * @return $this
	 */
	private function prepare_selector_output() {
		if ( ! empty( $this->_selector_states ) ) {
			// Create a new variable to store all of the states.
			$new_selector = '';

			foreach ( (array) $this->_selector_states as $state ) {
				$format        = end( $this->_selector_states ) === $state ? '%1$s%2$s' : '%1$s%2$s,';
				$new_selector .= sprintf( $format, $this->_selector, $state );
			}
			$this->_selector_output = $new_selector;
		} else {
			$this->_selector_output = $this->_selector;
		}
		return $this;
	}




	/**
	 * Generates the measure output.
	 *
	 * @param array $measure an array of font settings.
	 * @return string
	 */
	public function render_spacing( $measure, $unit = 'px' ) {

		if ( empty( $measure ) ) {
			return false;
		}

		if ( ! is_numeric( $measure['top'] ) && ! is_numeric( $measure['right'] ) && ! is_numeric( $measure['bottom'] ) && ! is_numeric( $measure['left'] ) ) {
			return false;
		}

		$size_string = ( is_numeric( $measure['top'] ) ? $measure['top'] : '0' ) . $unit . ' ' . ( is_numeric( $measure['right'] ) ? $measure['right'] : '0' ) . $unit . ' ' . ( is_numeric( $measure['bottom'] ) ? $measure['bottom'] : '0' ) . $unit . ' ' . ( is_numeric( $measure['left'] ) ? $measure['left'] : '0' ) . $unit;

		return $size_string;
	}

	/**
	 * Generates the font output.
	 *
	 * @param array  $font an array of font settings.
	 * @param object $css an object of css output.
	 * @param string $inherit an string to determine if the font should inherit.
	 * @return string
	 */
	public function render_typography( $font, $device ) {
		if ( empty( $font ) ) {
			return false;
		}
		if ( isset( $font['fontSize'] ) && isset( $font['fontSize'][ $device ] ) && ! empty( $font['fontSize'][ $device ] ) ) {
			$this->add_property( 'font-size', $this->render_range( $font['fontSize'], $device ) );
		}
		if ( isset( $font['lineHeight'] ) && isset( $font['lineHeight'][ $device ] ) && ! empty( $font['lineHeight'][ $device ] ) ) {
			$this->add_property( 'line-height', $this->render_range( $font['lineHeight'], $device ) );
		}
		if ( isset( $font['letterSpacing'] ) && isset( $font['letterSpacing'][ $device ] ) && ! empty( $font['letterSpacing'][ $device ] ) ) {
			$this->add_property( 'letter-spacing', $this->render_range( $font['letterSpacing'], $device ) );
		}
		if ( isset( $font['textDecoration'] ) && ! empty( $font['textDecoration'] ) ) {
			$this->add_property( 'text-decoration', $font['textDecoration'] );
		}
		if ( isset( $font['textTransform'] ) && ! empty( $font['textTransform'] ) ) {
			$this->add_property( 'text-transform', $font['textTransform'] );
		}
		if ( isset( $font['fontWeight'] ) && ! empty( $font['fontWeight'] ) && 'Default' !== $font['fontWeight'] ) {
			$this->add_property( 'font-weight', $font['fontWeight'] );
		}
		if ( isset( $font['fontStyle'] ) && ! empty( $font['fontStyle'] ) ) {
			$this->add_property( 'font-style', $font['fontStyle'] );
		}
		$family = ( isset( $font['fontFamily'] ) && ! empty( $font['fontFamily'] ) && 'Default' !== $font['fontFamily'] ? $font['fontFamily'] : '' );
		if ( ! empty( $family ) ) {
			$font_style  = ( $font['fontStyle'] ?? 'normal' ) === 'italic' ? 'i' : 'n';
			$font_weight = ( $font['fontWeight'] ?? '400' ) !== 'Default' ? intval( $font['fontWeight'] ) / 100 : '4';
			$this->add_gfont(
				array(
					'fontFamily'  => $family,
					'fontVariant' => $font_style . $font_weight,
				)
			);
			$this->add_property( 'font-family', $font['fontFamily'] );

		}
	}


	public function add_gfont( $attr ) {

		$defaults = array(
			'googleFont'     => true,
			'loadGoogleFont' => true,
			'fontFamily'     => '',
			'fontVariant'    => '',
		);
		$attr     = wp_parse_args( $attr, $defaults );

		if ( true == $attr['googleFont'] && true == $attr['loadGoogleFont'] && ! empty( $attr['fontFamily'] ) ) {
			// Check if the font has been added yet.
			if ( ! array_key_exists( $attr['fontFamily'], self::$gfonts ) ) {
				$add_font                            = array(
					'fontfamily'   => $attr['fontFamily'],
					'fontvariants' => ( isset( $attr['fontVariant'] ) && ! empty( $attr['fontVariant'] ) ? array( $attr['fontVariant'] ) : array() ),
				);
				self::$gfonts[ $attr['fontFamily'] ] = $add_font;
				// Check if wp_head has already run in which case we need to add to footer fonts.
				if ( did_action( 'wp_body_open' ) >= 1 ) {
					self::$footer_gfonts[ $attr['fontFamily'] ] = $add_font;
				}
			} else {
				if ( isset( $attr['fontVariant'] ) && ! empty( $attr['fontVariant'] ) ) {
					if ( ! in_array( $attr['fontVariant'], self::$gfonts[ $attr['fontFamily'] ]['fontvariants'], true ) ) {
						array_push( self::$gfonts[ $attr['fontFamily'] ]['fontvariants'], $attr['fontVariant'] );
						if ( did_action( 'wp_body_open' ) >= 1 ) {
							if ( ! array_key_exists( $attr['fontFamily'], self::$footer_gfonts ) ) {
								$add_font                                   = array(
									'fontfamily'   => $attr['fontFamily'],
									'fontvariants' => ( isset( $attr['fontVariant'] ) && ! empty( $attr['fontVariant'] ) ? array( $attr['fontVariant'] ) : array() ),
								);
								self::$footer_gfonts[ $attr['fontFamily'] ] = $add_font;
							} else {
								array_push( self::$footer_gfonts[ $attr['fontFamily'] ]['fontvariants'], $attr['fontVariant'] );
							}
						}
					}
				}
			}
		}
	}


	/**
	 * Generates the size output.
	 *
	 * @param array  $size an array of size settings.
	 * @param string $device the device this is showing on.
	 * @param bool   $render_zero if 0 should be rendered or not.
	 * @return string
	 */
	public function render_range( $size, $device ) {
		if ( empty( $size ) ) {
			return false;
		}
		if ( ! isset( $size[ $device ] ) || empty( $size[ $device ] ) ) {
			return false;
		}
		$size_type   = ( isset( $size['unit'] ) && ! empty( $size['unit'] ) ? $size['unit'] : 'px' );
		$size_string = $size[ $device ] . $size_type;

		return $size_string;
	}

	/**
	 * Render shadow for an element.
	 *
	 * @param array $shadow An array containing the shadow properties.
	 *                      Properties include: color, horizontal, vertical, blur, position and opacity.
	 *                      All properties are optional.
	 * @return string|bool The rendered shadow string or false if invalid parameters are passed.
	 */
	public function render_shadow( $shadow ) {
		if ( empty( $shadow ) ) {
			return false;
		}
		if ( ! isset( $shadow['color'] ) ) {
			return false;
		}
		if ( ! isset( $shadow['horizontal'] ) ) {
			return false;
		}
		if ( ! isset( $shadow['vertical'] ) ) {
			return false;
		}
		if ( ! isset( $shadow['blur'] ) ) {
			return false;
		}
		if ( ! isset( $shadow['position'] ) ) {
			return false;
		}
		if ( $shadow['position'] === 'inset' ) {
			$shadow_string = 'inset ' . ( ! empty( $shadow['horizontal'] ) ? $shadow['horizontal'] : '0' ) . 'px ' . ( ! empty( $shadow['vertical'] ) ? $shadow['vertical'] : '0' ) . 'px ' . ( ! empty( $shadow['blur'] ) ? $shadow['blur'] : '0' ) . 'px ' . ( ! empty( $shadow['color'] ) ? $this->render_color( $shadow['color'], $shadow['opacity'] ) : $this->render_color( '#000000' ) );
		} else {
			$shadow_string = ( ! empty( $shadow['horizontal'] ) ? $shadow['horizontal'] : '0' ) . 'px ' . ( ! empty( $shadow['vertical'] ) ? $shadow['vertical'] : '0' ) . 'px ' . ( ! empty( $shadow['blur'] ) ? $shadow['blur'] : '0' ) . 'px ' . ( ! empty( $shadow['color'] ) ? $this->render_color( $shadow['color'] ) : $this->render_color( '#000000' ) );
		}

		return $shadow_string;
	}

	/**
	 * Render a filter based on the given parameters.
	 *
	 * @param array $filter An array of filter parameters.
	 *                      Properties include: bright, contrast, saturation, blur and hue.
	 *                      All properties are optional.
	 * @return string|bool The rendered filter string or false if invalid parameters are passed.
	 */
	public function render_filter( $filter ) {
		if ( empty( $filter ) ) {
			return false;
		}
		if ( ! isset( $filter['bright'] ) || ! isset( $filter['contrast'] )
			|| ! isset( $filter['saturation'] ) || ! isset( $filter['blur'] ) || ! isset( $filter['hue'] ) ) {
			return false;
		}

		$filter_string = 'brightness(' . $filter['bright'] . '%) contrast(' . $filter['contrast'] . '%) saturate(' . $filter['saturation'] . '%) blur(' . $filter['blur'] . 'px) hue-rotate(' . $filter['hue'] . 'deg)';

		return $filter_string;
	}

	/**
	 * Render a background based on the given parameters.
	 *
	 * @param array  $background An array of background parameters.
	 * @param object $css The CSS object to add properties to.
	 *
	 * @return void|false False if no background is specified, otherwise void.
	 */
	public function render_background( $background, $device ) {
		if ( empty( $background ) ) {
			return false;
		}
		if ( ! is_array( $background ) ) {
			return false;
		}
		$background_string = '';
		$type              = ( isset( $background['backgroundType'] ) && ! empty( $background['backgroundType'] ) ? $background['backgroundType'] : 'transparent' );
		$color_type        = '';
		if($type==='transparent'){
			$this->add_property( 'background-color', "transparent" );

		}
		if ( isset( $background['backgroundColor'] ) && ! empty( $background['backgroundColor'] ) && $type == 'solid' ) {

			$color_type = $background['backgroundColor'];
		}
		if ( 'solid' === $type && isset( $background['backgroundImageURL'] ) ) {
			$image_url = ( isset( $background['backgroundImageURL'] ) && ! empty( $background['backgroundImageURL'] ) ? $background['backgroundImageURL'] : '' );
			if ( ! empty( $image_url ) ) {
				$repeat   = ( isset( $background['backgroundRepeat'] ) && ! empty( $background['backgroundRepeat'] ) ? $background['backgroundRepeat'] : '' );
				$size     = ( isset( $background['backgroundSize'] ) && ! empty( $background['backgroundSize'] ) ? $background['backgroundSize'] : '' );
				$position = ( isset( $background['backgroundPosition'] ) && ! empty( $background['backgroundPosition'] ) ? $background['backgroundPosition'] : 'center center' );
				// $background_string = ( ! empty( $color_type ) ? $color_type . ' ' : '' ) . $image_url . ( ! empty( $repeat ) ? ' ' . $repeat : '' ) . ( ! empty( $position ) ? ' ' . $position : '' ) . ( ! empty( $size ) ? ' ' . $size : '' ) . ( ! empty( $attachement ) ? ' ' . $attachement : '' );
				$this->add_property( 'background-color', $color_type );
				$this->add_property( 'background-image', 'url(' . $image_url . ')' );
				$this->add_property( 'background-repeat', $this->get_responsive_css( $background['backgroundRepeat'], $device ) );
				$this->add_property( 'background-position', $this->get_responsive_css( $background['backgroundPosition'], $device ) );
				$this->add_property( 'background-size', $this->get_responsive_css( $background['backgroundSize'], $device ) );
			} else {
				if ( ! empty( $color_type ) ) {
					$background_string = $color_type;
					$this->add_property( 'background-color', $color_type );
				}
			}
		} elseif ( 'gradient' === $type ) {
			$first_grid  = $background['backgroundColor'] ? $background['backgroundColor'] : 'rgba(255,255,255,0)';
			$second_grid = $background['gradientColorTwo'] ? $background['gradientColorTwo'] : '#777';
			if ( 'radial' === $background['gradientType'] ) {
				$container_bg = 'radial-gradient(at ' . $background['gradientPosition'] . ', ' . $first_grid . ' ' . $background['gradientLocationOne'] . '%, ' . $second_grid . ' ' . $background['gradientLocationTwo'] . '%)';
			} elseif ( 'radial' !== $background['backgroundType'] ) {
				$container_bg = 'linear-gradient(' . $background['gradientAngle'] . 'deg, ' . $first_grid . ' ' . $background['gradientLocationOne'] . '%, ' . $second_grid . ' ' . $background['gradientLocationTwo'] . '%)';
			}
			$this->add_property( 'background', $container_bg );
		} else {
			if ( ! empty( $color_type ) ) {
				$background_string = $color_type;
				$this->add_property( 'background', $color_type );
			}
		}
	}

	/**
	 * Resets the css variable
	 *
	 * @access private
	 * @since  1.1
	 *
	 * @return void
	 */
	private function reset_css() {
		$this->_css = '';
		return;
	}

	/**
	 * Returns the google fonts array from the compiled css.
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @return string
	 */
	public function fonts_output() {
		return self::$google_fonts;
	}

	/**
	 * Returns the minified css in the $_output variable
	 *
	 * @access public
	 * @since  1.0
	 *
	 * @return string
	 */
	public function css_output() {
		// Add current selector's rules to output
		$this->add_selector_rules_to_output();

		// Output minified css
		return $this->_output;
	}

	public function get_responsive_value( $values, $side = '', $device = 'Desktop', $unit = 'px' ) {
		return isset( $values[ $device ][ $side ] ) && $values[ $device ][ $side ] ? "{$values[$device][$side]}{$unit}" : '';
	}

	public function get_responsive_css( $values, $device = 'Desktop' ) {
		return isset( $values[ $device ] ) && $values[ $device ] ? "{$values[$device]}" : '';
	}
}
