<?php
namespace ycd;

class WooManager {
	private $post;
	public function __construct($post) {
		$this->post = $post;
	}

	public function isWoo() {
		if (!$this->post) return  false;
		return $this->post->post_type === 'product';
	}
}