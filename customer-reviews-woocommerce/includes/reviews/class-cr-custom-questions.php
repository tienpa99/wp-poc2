<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CR_Custom_Questions' ) ) :

	class CR_Custom_Questions {
		private $questions = array();
		public static $meta_id = 'ivole_c_questions';
		public static $onsite_prefix = 'cr_onsite_';
		public static $type_label_prefix = 'cr_typ_lab_';

		public function __construct() {
		}

		public function parse_shop_questions( $order ) {
			if( isset( $order->shop_questions ) && is_array( $order->shop_questions ) ) {
				$this->parse_questions( $order->shop_questions );
			}
		}

		public function parse_product_questions( $item ) {
			if( isset( $item->item_questions ) && is_array( $item->item_questions ) ) {
				$this->parse_questions( $item->item_questions );
			}
		}

		public function parse_questions( $input ) {
			$num_questions = count( $input );
			for( $i = 0; $i < $num_questions; $i++ ) {
				if( $input[$i]->type ) {
					switch( $input[$i]->type ) {
						case 'radio':
							if( isset( $input[$i]->title ) && isset( $input[$i]->value ) ) {
								$question = new CR_Custom_Question();
								$question->type = 'radio';
								$question->title = sanitize_text_field( $input[$i]->title );
								$question->value = sanitize_text_field( $input[$i]->value );
								$this->questions[] = $question;
							}
							break;
						case 'checkbox':
							if( isset( $input[$i]->title ) &&
									isset( $input[$i]->value ) && is_array( $input[$i]->value ) ) {
										$question = new CR_Custom_Question();
										$question->type = 'checkbox';
										$question->title = sanitize_text_field( $input[$i]->title );
										$count_values = count( $input[$i]->value );
										for( $j = 0; $j < $count_values; $j++ ) {
											$question->values[] = sanitize_text_field( $input[$i]->value[$j] );
										}
										$this->questions[] = $question;
									}
							break;
						case 'rating':
							if( isset( $input[$i]->title ) && isset( $input[$i]->value ) ) {
								$question = new CR_Custom_Question();
								$question->type = 'rating';
								$question->title = sanitize_text_field( $input[$i]->title );
								$question->value = intval( $input[$i]->value );
								$this->questions[] = $question;
							}
							break;
						case 'comment':
							if( isset( $input[$i]->title ) && isset( $input[$i]->value ) ) {
								$question = new CR_Custom_Question();
								$question->type = 'comment';
								$question->title = sanitize_text_field( $input[$i]->title );
								$question->value = sanitize_text_field( $input[$i]->value );
								$this->questions[] = $question;
							}
							break;
						default:
							break;
					}
				}
			}
		}

		public function has_questions() {
			if( count( $this->questions ) > 0 ) {
				return true;
			} else {
				return false;
			}
		}

		public function save_questions( $review_id ) {
			if( count( $this->questions ) > 0 ) {
				update_comment_meta( $review_id, self::$meta_id, $this->questions );
			}
		}

		public function read_questions( $review_id ) {
			$meta = get_comment_meta( $review_id, self::$meta_id, true );
			if( $meta && is_array( $meta ) ) {
				$count_meta = count( $meta );
				for( $i = 0; $i < $count_meta; $i++ ) {
					if( $meta[$i] instanceof CR_Custom_Question ) {
						$this->questions[] = $meta[$i];
					}
				}
			}
		}

		public function output_questions( $f = false, $hr = true ) {
			$fr = '';
			if( $f ) {
				$fr = 'f';
			}
			$count_questions = count( $this->questions );
			$output = '';
			for( $i = 0; $i < $count_questions; $i++ ) {
				if (
					isset( $this->questions[$i]->type ) &&
					isset( $this->questions[$i]->title )
				) {
					$title = ( isset( $this->questions[$i]->label ) && $this->questions[$i]->label ) ? $this->questions[$i]->label : $this->questions[$i]->title;
					switch( $this->questions[$i]->type ) {
						case 'radio':
							if( isset( $this->questions[$i]->value ) ) {
								$output .= '<p class="iv' . $fr . '-custom-question-p"><span class="iv' . $fr . '-custom-question-radio">' . $this->questions[$i]->title .
									'</span> : ' . $this->questions[$i]->value . '</p>';
							}
							break;
						case 'checkbox':
							if( isset( $this->questions[$i]->values ) &&
						 			is_array( $this->questions[$i]->values ) ) {
								$count_values = count( $this->questions[$i]->values );
								$output_temp = '';
								for( $j = 0; $j < $count_values; $j++ ) {
									$output_temp .= '<li>' . $this->questions[$i]->values[$j] . '</li>';
								}
								if( $count_values > 0 ) {
									$output .= '<p class="iv' . $fr . '-custom-question-checkbox">' . $this->questions[$i]->title . ' : </p>';
									$output .= '<ul class="iv' . $fr . '-custom-question-ul">' . $output_temp . '</ul>';
								}
							}
							break;
						case 'rating':
							if( isset( $this->questions[$i]->value ) ) {
								if( $this->questions[$i]->value > 0 ) {
									if( $f ) {
										$output .= '<div class="cr' . $fr . '-custom-question-rating-cont"><div class="cr' . $fr . '-custom-question-rating">' . $this->questions[$i]->title . ' :</div>';
										$output .= wc_get_rating_html( $this->questions[$i]->value ) . '</div>';
									} else {
										$output .= '<div class="cr' . $fr . '-custom-question-rating-cont"><span class="cr' . $fr . '-custom-question-rating">' . $this->questions[$i]->title . ' :</span>';
										$output .= '<span class="iv' . $fr . '-star-rating">';
										for ( $j = 1; $j < 6; $j++ ) {
											$class = ( $j <= $this->questions[$i]->value ) ? 'filled' : 'empty';
											$output .= '<span class="dashicons dashicons-star-' . $class . '"></span>';
										}
										$output .= '</span></div>';
									}
								}
							}
							break;
						case 'comment':
							if( isset( $this->questions[$i]->value ) ) {
								$output .= '<p class="iv' . $fr . '-custom-question-p"><span class="iv' . $fr . '-custom-question-radio">' . $this->questions[$i]->title .
									'</span> : ' . $this->questions[$i]->value . '</p>';
							}
							break;
						case 'number':
						case 'text':
							if( isset( $this->questions[$i]->value ) ) {
								$output .= '<p class="iv' . $fr . '-custom-question-p"><span class="iv' . $fr . '-custom-question-radio">' . $title .
									'</span> : ' . $this->questions[$i]->value . '</p>';
							}
							break;
						default:
							break;
					}
				}
			}
			if( strlen( $output ) > 0 ) {
				if( $f ) {
					$output = '<hr class="iv' . $fr . '-custom-question-hr">' . $output . '<hr class="iv' . $fr . '-custom-question-hr">';
				} else {
					if( $hr ) {
						$output = '<hr class="iv' . $fr . '-custom-question-hr">' . $output;
					}
				}
				echo apply_filters( 'cr_custom_questions', $output );
			}
		}

		public function delete_questions( $review_id ) {
			delete_comment_meta( $review_id, self::$meta_id );
		}

		public static function review_form_questions( $comment_form ) {
			$onsite_form = CR_Forms_Settings::get_default_form_settings();
			$qs = '';
			if (
				$onsite_form &&
				is_array( $onsite_form ) &&
				isset( $onsite_form['cus_atts'] ) &&
				is_array( $onsite_form['cus_atts'] )
			) {
				$index = 0;
				$hash = random_int( 0, 99 ) . '_';
				$max_atts = CR_Forms_Settings::get_max_cus_atts();
				foreach ( $onsite_form['cus_atts'] as $q ) {
					if ( $index >= $max_atts ) {
						break;
					}
					if (
						isset( $q['attribute'] ) &&
						$q['attribute']
					) {
						$required = '';
						if ( isset( $q['required'] ) && $q['required'] ) {
							$required = '<span class="required">*</span>';
						}
						$label = ( isset( $q['label'] ) && $q['label'] ? $q['label'] : $q['attribute'] );
						$type_label = array(
							'title' => $q['attribute'],
							'type' => $q['type'],
							'label' => $label
						);
						switch ( $q['type'] ) {
							case 'text':
								$qs .= '<div class="cr-onsite-question cr-full-width">';
								$qs .= '<label for="' . esc_attr( self::$onsite_prefix . $hash . $index ) . '">' . esc_html( $q['attribute'] ) . $required . '</label>';
								$qs .= '<input id="' . esc_attr( self::$onsite_prefix . $hash . $index ) . '" name="' . esc_attr( self::$onsite_prefix . $hash . $index ) . '" type="' . esc_attr( $q['type'] ) . '">';
								$qs .= '<input name="' . esc_attr( self::$type_label_prefix . $hash . $index ) . '" type="hidden" value="' . esc_attr( json_encode( $type_label ) ) . '">';
								$qs .= '</div>';
								$index++;
								break;
							case 'number':
								$qs .= '<div class="cr-onsite-question">';
								$qs .= '<label for="' . esc_attr( self::$onsite_prefix . $hash . $index ) . '">' . esc_html( $q['attribute'] ) . $required . '</label>';
								$qs .= '<input id="' . esc_attr( self::$onsite_prefix . $hash . $index ) . '" name="' . esc_attr( self::$onsite_prefix . $hash . $index ) . '" type="' . esc_attr( $q['type'] ) . '">';
								$qs .= '<input name="' . esc_attr( self::$type_label_prefix . $hash . $index ) . '" type="hidden" value="' . esc_attr( json_encode( $type_label ) ) . '">';
								$qs .= '</div>';
								$index++;
								break;
							default:
								break;
						}
					}
				}
			}
			if ( $qs ) {
				$comment_form .= '<div class="cr-onsite-questions">' . $qs . '</div>';
			}
			return $comment_form;
		}

		public static function submit_onsite_questions( $comment_id ) {
			if ( $_POST && is_array( $_POST ) ) {
				$onsite_questions = array_filter(
					$_POST,
					function( $k ) {
						return ( strpos( $k, self::$onsite_prefix ) === 0 );
					},
					ARRAY_FILTER_USE_KEY
				);
				if ( $onsite_questions && is_array( $onsite_questions ) ) {
					$cus_questions = array();
					foreach ( $onsite_questions as $key => $response ) {
						$type_label = self::$type_label_prefix . substr( $key, strlen( self::$onsite_prefix ) );
						if (
							isset( $_POST[$type_label] ) &&
							$_POST[$type_label]
						) {
							$type_label = json_decode( stripslashes( $_POST[$type_label] ), true );
							if (
								$type_label &&
								is_array( $type_label ) &&
								isset( $type_label['title'] ) &&
								isset( $type_label['type'] ) &&
								isset( $type_label['label'] )
							) {
								$question = new CR_Custom_Question();
								$question->type = $type_label['type'];
								$question->title = sanitize_text_field( $type_label['title'] );
								$question->label = sanitize_text_field( $type_label['label'] );
								$question->value = sanitize_text_field( $response );
								$cus_questions[] = $question;
							}
						}
					}
					if ( $cus_questions ) {
						update_comment_meta( $comment_id, self::$meta_id, $cus_questions );
					}
				}
			}
		}

	}

endif;

if ( ! class_exists( 'CR_Custom_Question' ) ) :
	class CR_Custom_Question {
		public $type;
		public $title;
		public $label;
		public $value;
		public $values = array();
	}
endif;
