<?php // Do not allow direct access to the file.
	if( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	function revolution_press_dark_mode($classes) {
		$revolution_press_night_mode = isset($_COOKIE['revolution_pressNightMode']) ? $_COOKIE['revolution_pressNightMode'] : '';
		//if the cookie is stored..
		if ($revolution_press_night_mode !== '') {
			// Add 'dark-mode' body class
			return array_merge($classes, array('dark-mode'));
		}
		return $classes;
	}
	add_filter('body_class', 'revolution_press_dark_mode');
	
	
	/**
		* Enqueue scripts and styles.
	*/
	
	add_action( 'wp_enqueue_scripts', 'revolution_press_dark_mode_scripts' );
	
	function revolution_press_dark_mode_scripts() {	
		if( get_theme_mod( 'activate_dark_mode', 1 ) ) {
			wp_enqueue_style( 'dark_mode-style', get_template_directory_uri() . '/include/dark-mode/dark-mode.css' );
			wp_enqueue_script( 'dark_mode-js', get_template_directory_uri() . '/include/dark-mode/dark-mode.js', array(), '', false );
		}
	}
	
	
	add_action( 'customize_register', 'revolution_press_dark_mode_customize' );
	function revolution_press_dark_mode_customize( $wp_customize ) {
		
		
		$wp_customize->add_section( 'section_dark_mode' , array(
		'title'       => __( 'Button Dark Mode', 'revolution-press' ),
		'priority'    => 1,
		) );
		
		$wp_customize->add_setting( 'activate_dark_mode', array (
		'default' => 1,		
		'sanitize_callback' => 'revolution_press__sanitize_checkbox',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'activate_dark_mode', array(
		'label'    => __( 'Activate Button Dark Mode', 'revolution-press' ),
		'section'  => 'section_dark_mode',
		'priority'    => 3,				
		'type' => 'checkbox',
		) ) );	
		
	}
