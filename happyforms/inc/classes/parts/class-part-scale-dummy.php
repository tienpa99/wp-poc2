<?php

class HappyForms_Part_Scale_Dummy extends HappyForms_Form_Part {

	public $type = 'scale_dummy';

	public function __construct() {
		$this->label = __( 'Slider', 'happyforms' );
		$this->description = __( 'For collecting an approximate value or range along a horizontal slider.', 'happyforms' );
	}

}
