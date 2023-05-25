<?php if( ! defined( 'ABSPATH' ) ) exit;

/*******************************
* Enqueue scripts and styles.
********************************/
 
function revolution_press_anima_scripts() {
	if(!get_theme_mod('revolution_press__header_animation')) {
		wp_enqueue_style( 'revolution_press-anima-css', get_template_directory_uri() . '/include/letters/anime.css');
		wp_enqueue_script( 'revolution_press-anima-js', get_template_directory_uri() . '/include/letters/anime.min.js', array( 'jquery' ), true);
		wp_enqueue_script( 'revolution_press-anime-custom-js', get_template_directory_uri() . '/include/letters/anime-custom.js', array( 'jquery' ), '', true);
	}
		
}

add_action( 'wp_enqueue_scripts', 'revolution_press_anima_scripts' );


