<?php
/**
 * Author: Hoang Ngo
 */

namespace Calotes\Base;

use Calotes\Component\Behavior;

/**
 * Class Component
 *
 * Class should extend this if behavior and event required
 *
 * @package Calotes\Base
 */
class Component {
	/**
	 * Contains array of behaviors
	 *
	 * @var array
	 */
	protected $behaviors = array();

	/**
	 * Internal use only
	 *
	 * @var array
	 */
	protected $cached_object = array();

	/**
	 * Cache the annotations of properties
	 *
	 * @var array
	 */
	public $annotations = array();

	/**
	 * Store internal logging, mostly for debug.
	 *
	 * @var array
	 */
	protected $internal_logging = array();

	/**
	 * Attach a behavior to current class, a behavior is a mixins, which useful in case of pro/free version
	 *
	 * @param  string  $name
	 * @param  Behavior|string  $behavior
	 */
	public function attach_behavior( $name, $behavior ) {
		// make a fast init
		if ( ! $behavior instanceof Behavior ) {
			$behavior = new $behavior();
		}
		$behavior->owner          = $this;
		$this->behaviors[ $name ] = $behavior;
	}

	/**
	 * @param $property
	 *
	 * @return bool
	 * @throws \ReflectionException
	 */
	public function has_property( $property ) {
		$ref = new \ReflectionClass( $this );

		return $ref->hasProperty( $property );
	}

	/**
	 * @param $method
	 *
	 * @return bool
	 * @throws \ReflectionException
	 */
	public function has_method( $method ) {
		$ref_class = new \ReflectionClass( $this );
		if ( $ref_class->hasMethod( $method ) ) {
			return true;
		}

		foreach ( $this->behaviors as $key => $behavior ) {
			$ref_class = new \ReflectionClass( $behavior );
			if ( $ref_class->hasMethod( $method ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get( $name ) {
		// priority to current class properties
		if ( $this->has_property( $name ) ) {
			return $this->$name;
		}
		// check if behaviors already have
		foreach ( $this->behaviors as $key => $behavior ) {
			$ref_class = new \ReflectionClass( $behavior );

			if ( $ref_class->hasProperty( $name ) ) {
				return $ref_class->getProperty( $name )->getValue( $behavior );
			}
		}

		throw new \Exception( 'Getting unknown property: ' . get_class( $this ) . '::' . $name );
	}

	/**
	 * @param $name
	 * @param $arguments
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function __call( $name, $arguments ) {
		$ref_class = new \ReflectionClass( $this );
		if ( $ref_class->hasMethod( $name ) ) {
			$ref_method = new \ReflectionMethod( $this, $name );

			return $ref_method->invokeArgs( $this, $arguments );
		}
		foreach ( $this->behaviors as $key => $behavior ) {
			$ref_class = new \ReflectionClass( $behavior );
			if ( $ref_class->hasMethod( $name ) ) {
				$ref_method = new \ReflectionMethod( $behavior, $name );

				return $ref_method->invokeArgs( $behavior, $arguments );
			}
		}

		throw new \Exception( 'Getting unknown property: ' . get_class( $this ) . '::' . $name );
	}

	/**
	 * Do not call this directly, magic method for assign value to property, if property is not exist for this component, we will
	 * check its behavior
	 *
	 * @param $name
	 * @param $value
	 *
	 * @throws \Exception
	 */
	public function __set( $name, $value ) {
		$ref_class = new \ReflectionClass( $this );
		if ( $ref_class->hasProperty( $name ) ) {
			$ref_class->getProperty( $name )->setValue( $value );

			return;
		}

		foreach ( $this->behaviors as $key => $behavior ) {
			$ref_class = new \ReflectionClass( $behavior );
			if ( $ref_class->hasProperty( $name ) ) {
				$ref_class->getProperty( $name )->setValue( $behavior, $value );

				return;
			}
		}

		throw new \Exception( 'Setting unknown property: ' . get_class( $this ) . '::' . $name );
	}

	/**
	 * Parse the annotations of the class, and cache it. The list should be
	 * - type: for casting
	 * - sanitize_*: the list of sanitize_ functions, which should be run on this property
	 * - rule: the rule that we use for validation
	 */
	protected function parse_annotations() {
		$class      = new \ReflectionClass( static::class );
		$properties = $class->getProperties( \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED );
		foreach ( $properties as $property ) {
			$doc_block = $property->getDocComment();
			if ( ! stristr( $doc_block, '@defender_property' ) ) {
				continue;
			}
			$this->annotations[ $property->getName() ] = array(
				'type'     => $this->parse_annotations_var( $doc_block ),
				'sanitize' => $this->parse_annotation_sanitize( $doc_block ),
				'rule'     => $this->parse_annotation_rule( $doc_block ),
			);
		}
	}

	/**
	 * Get the variable type
	 *
	 * @param $docblock
	 *
	 * @return false|mixed
	 */
	private function parse_annotations_var( $docblock ) {
		$pattern = '/@var\s(.+)/';
		if ( preg_match( $pattern, $docblock, $matches ) ) {
			$type = trim( $matches[1] );

			// only allow right type
			if ( in_array(
				$type,
				array(
					'boolean',
					'bool',
					'integer',
					'int',
					'float',
					'double',
					'string',
					'array',
					'object',
				)
			) ) {
				return $type;
			}
		}

		return false;
	}

	/**
	 * Get the sanitize function
	 *
	 * @param $docblock
	 *
	 * @return false|mixed
	 */
	private function parse_annotation_sanitize( $docblock ) {
		$pattern = '/@(sanitize_.+)/';
		if ( preg_match( $pattern, $docblock, $matches ) ) {
			return trim( $matches[1] );
		}

		return false;
	}

	/**
	 * Get the validation rule
	 *
	 * @param $docblock
	 *
	 * @return false|mixed
	 */
	private function parse_annotation_rule( $docblock ) {
		$pattern = '/@(rule_.+)/';
		if ( preg_match( $pattern, $docblock, $matches ) ) {
			return trim( $matches[1] );
		}

		return false;
	}

	/**
	 * Add a log for internal use, mostly for debug.
	 *
	 * @param string $message
	 * @param string $category
	 */
	protected function log( $message, $category = '' ) {
		if ( ! is_string( $message ) || is_array( $message ) || is_object( $message ) ) {
			$message = print_r( $message, true );
		}

		$this->internal_logging[] = date( 'Y-m-d H:i:s' ) . ' ' . $message;
		//uncomment it for detailed logging on wp cli
//		 if ( 'cli' === PHP_SAPI ) {
//		    echo $message . PHP_EOL;
//		 }

		$message = '[' . date( 'c' ) . '] ' . $message . PHP_EOL;

		if ( $this->has_method( 'get_log_path' ) ) {
			if ( ! empty( $category ) && 0 === preg_match( '/\.log$/', $category ) ) {
				$category .= '.log';
			}

			$file_path = $this->get_log_path( $category );
			$dir_name  = pathinfo( $file_path, PATHINFO_DIRNAME );

			if ( ! is_dir( $dir_name ) ) {
				wp_mkdir_p( $dir_name );
			}

			if ( is_writable( $dir_name ) ) {
				file_put_contents( $file_path, $message, FILE_APPEND );
			}
		}
	}
}
