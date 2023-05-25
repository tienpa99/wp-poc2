<?php
/**
 * Plugin Name: Alert Box Block
 * Description: Provide contextual feedback messages with alert box block
 * Version: 1.0.8
 * Author: bPlugins LLC
 * Author URI: http://bplugins.com
 * License: GPLv3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain: alert-box-block
 */

// ABS PATH
if ( !defined( 'ABSPATH' ) ) { exit; }

// Constant
define( 'ABB_PLUGIN_VERSION', isset( $_SERVER['HTTP_HOST'] ) && 'localhost' === $_SERVER['HTTP_HOST'] ? time() : '1.0.8' );
define( 'ABB_ASSETS_DIR', plugin_dir_url( __FILE__ ) . 'assets/' );

// Alert Box Block
class ABBAlertBoxBlock{
	function __construct(){
		add_action( 'enqueue_block_assets', [$this, 'enqueueBlockAssets'] );
		add_action( 'init', [$this, 'onInit'] );
	}

	function enqueueBlockAssets(){ wp_enqueue_style( 'fontAwesome', ABB_ASSETS_DIR . 'css/fontAwesome.min.css', [], '5.15.4' ); }

	function onInit() {
		wp_register_style( 'abb-alert-box-editor-style', plugins_url( 'dist/editor.css', __FILE__ ), [ 'abb-alert-box-style' ], ABB_PLUGIN_VERSION ); // Backend Style
		wp_register_style( 'abb-alert-box-style', plugins_url( 'dist/style.css', __FILE__ ), [], ABB_PLUGIN_VERSION ); // Style

		register_block_type( __DIR__, [
			'editor_style'		=> 'abb-alert-box-editor-style',
			'style'				=> 'abb-alert-box-style',
			'render_callback'	=> [$this, 'render']
		] ); // Register Block

		wp_set_script_translations( 'abb-alert-box-editor-script', 'alert-box-block', plugin_dir_path( __FILE__ ) . 'languages' ); // Translate
	}

	function render( $attributes ){
		extract( $attributes );

		$className = $className ?? '';
		$blockClassName = 'wp-block-abb-alert-box ' . $className . ' align' . $align;

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='abbAlertBox-<?php echo esc_attr( $cId ); ?>' data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'></div>

		<?php return ob_get_clean();
	} // Render
}
new ABBAlertBoxBlock;