<?php
/**
 * Server-side rendering of the `premium/instagram-feed` block.
 *
 * @package WordPress
 */

 /**
  * Render instagram feed block.
  *
  * @param array  $attributes The block attributes.
  * @param string $content The block content.
  * @return string
  */
function render_block_pbg_instagram_feed( $attributes, $content ) {
	return $content;
}

/**
 * Register the Instagram Feed block.
 *
 * @uses render_block_pbg_instagram_feed()
 * @throws WP_Error An WP_Error exception parsing the block definition.
 */
function register_block_pbg_instagram_feed() {
	if ( ! function_exists( 'register_block_type' ) ) {
		return;
	}
	register_block_type(
		PREMIUM_BLOCKS_PATH . 'blocks-config/instagram-feed',
		array(
			'render_callback' => 'render_block_pbg_instagram_feed',
		)
	);
}

register_block_pbg_instagram_feed();