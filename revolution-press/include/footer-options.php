<?php 
// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
    exit;
}
function revolution_press_footer_customize_register( $wp_customize ) {
			$wp_customize->add_section( 'revolution_press_footer_section' , array(
				'title'       => __( 'Footer Options', 'revolution-press' ),
				'priority'   => 54,
			) );
			$wp_customize->add_setting( 'revolution_press_copyright', array (
				'sanitize_callback' => 'wp_kses_post',
			) );
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'revolution_press_copyright', array(
			    'label'    => __( 'Custom Copyright ', 'revolution-press' ),
				'section'  => 'revolution_press_footer_section',
				'settings' => 'revolution_press_copyright',
				'type' => 'textarea',
			) ) );
			
		$wp_customize->add_setting( 'revolution_press_footer_image', array (
			'default' => get_template_directory_uri() . '/images/footer.webp',				
			'sanitize_callback' => 'esc_url_raw',
		) );
		
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'revolution_press_footer_image', array(
			'label'    => __( 'Footer Image', 'revolution-press' ),		
			'section'  => 'revolution_press_footer_section',
			'settings' => 'revolution_press_footer_image',			
		) ) );			
			
   	    $wp_customize->add_setting( 'color_footer', array (
			'sanitize_callback' => 'sanitize_hex_color',
		) );
 		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'color_footer', array(
			'label'    => __( 'Background Color Footer', 'revolution-press' ),
			'section'  => 'revolution_press_footer_section',
			'settings' => 'color_footer',
		) ) );
					
}
add_action( 'customize_register', 'revolution_press_footer_customize_register' );



/********************************************
* Content Styles
*********************************************/ 	
function revolution_press_footer () {

        $footer_image = esc_html(get_theme_mod( 'revolution_press_footer_image' ) );
        $color_footer = esc_html(get_theme_mod( 'color_footer' ) );


		if( $footer_image ) { $footer_image_style = "body .site-info { background-image: url({$footer_image});}";} else {$footer_image_style ="";}
		if( $color_footer ) { $footer_color_style = "body .site-info { background-color: {$color_footer};}";} else {$footer_color_style ="";}
		
		wp_add_inline_style( 'custom-style-css', 
		    $footer_image_style.$footer_color_style
		);
}
add_action( 'wp_enqueue_scripts', 'revolution_press_footer' );