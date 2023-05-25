<?php
/**
 * Plugin Name: WE Blocks - Posts, Image, Testimonial And Logo Slider Gutenberg Blocks
 * Plugin URI: https://wordpress.org/plugins/we-blocks/
 * Description: The WE Blocks plugin is great combo of slider blocks. It includes Posts slider, Image slider, Testimonials slider and Client Logo slider block. 
 * Author: WORDPRESTEEM
 * Author URI: https://profiles.wordpress.org/wordpresteem
 * Version: 1.3.3
 * Text Domain: we-blocks
 * Contributors: WORDPRESTEEM
 * Requires at least: 5.1
 * Requires PHP: 5.6.3
 * Tested up to: 6.1.1
 * License: GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WE
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
require_once plugin_dir_path( __FILE__ ) . 'dist/we-start/we-start.php';

/**
 * Add a check for our plugin before redirecting
 */
function we_blocks_activate() {
    add_option( 'we_blocks_do_activation_redirect', true );
}
register_activation_hook( __FILE__, 'we_blocks_activate' );


/**
 * Redirect to the WE Blocks we start  page on single plugin activation
 */
function we_blocks_redirect() {
    if ( get_option( 'we_blocks_do_activation_redirect', false ) ) {
        delete_option( 'we_blocks_do_activation_redirect' );
        if( !isset( $_GET['activate-multi'] ) ) {
            wp_redirect( "admin.php?page=we-blocks" );
        }
    }
}
add_action( 'admin_init', 'we_blocks_redirect' );

/**
 * Create WE Blocks Categories
 */
add_filter( 'block_categories', function( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'we-blocks',
				'title' => __( 'WE Blocks', 'we-blocks' ),
			),
		)
	);
}, 10, 2 );