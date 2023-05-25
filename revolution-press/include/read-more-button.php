<?php if( ! defined( 'ABSPATH' ) ) exit;
/**
 * Read More Button
 */
	function revolution_press__excerpt_more( $more ) {
		if ( is_admin() ) {
			return $more;
		}
        return '<p class="link-more"><a class="myButt " href="'. esc_url(get_permalink( get_the_ID() ) ) . '">' . revolution_press__return_read_more_text (). '</a></p>';
	}
	add_filter( 'excerpt_more', 'revolution_press__excerpt_more' );
	
	function revolution_press__excerpt_length( $length ) {
			if ( is_admin() ) {
				return $length;
			}
			return 22;
	}
	add_filter( 'excerpt_length', 'revolution_press__excerpt_length', 999 );
	
	function revolution_press__return_read_more_text () {
		if( get_theme_mod( 'revolution_press__return_read_more_text' ) ) {
			return esc_html( get_theme_mod( 'revolution_press__return_read_more_text' ) );
		} else {
		return __( 'Read More','revolution-press');
		}
	}

add_action( 'customize_register', 'revolution_press_read_more_customize_register' );
function revolution_press_read_more_customize_register( $wp_customize ) {
/***********************************************************************************
 * Back to top button Options
***********************************************************************************/

		$wp_customize->selective_refresh->add_partial( 'revolution_press__return_read_more_text', array(
			'selector'        => '.myButt',
			'render_callback' => 'revolution_press__customize_partial_revolution_press__return_read_more_text',
		) );
		
		$wp_customize->add_section( 'revolution_press_read_more' , array(
			'title'       => __( 'Read More Button - Custom Text', 'revolution-press' ),
			'priority'   => 34,
		) );
		$wp_customize->add_setting( 'revolution_press__return_read_more_text', array (
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'revolution_press__return_read_more_text', array(
			'priority'    => 1,
			'label'    => __( 'Read More Text', 'revolution-press' ),
			'section'  => 'revolution_press_read_more',
			'settings' => 'revolution_press__return_read_more_text',
			'type' => 'text',
		) ) );
}