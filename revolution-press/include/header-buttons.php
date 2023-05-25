<?php if( ! defined( 'ABSPATH' ) ) exit;
function revolution_press_customize_register_header_buttons( $wp_customize ) {

		$wp_customize->selective_refresh->add_partial( 'button_1', array(
			'selector' => '.h-button-1 ',
			'render_callback' => 'revolution_press_customize_partial_button_1',
		) );	
		
		$wp_customize->selective_refresh->add_partial( 'button_2', array(
			'selector' => '.h-button-2 ',
			'render_callback' => 'revolution_press_customize_partial_button_2',
		) );	
		
		
		$wp_customize->add_section( 'seos_header_buttons_section' , array(
			'title'       => __( 'Header Buttons', 'revolution-press' ),
			'priority'    => 26,	
			//'description' => __( 'Social media buttons', 'seos-white' ),
		) );
		
		$wp_customize->add_setting( 'button_1', array (
            'default' => '',		
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'button_1', array(
			'label'    => __( 'Button 1 Text', 'revolution-press' ),
			'description'    => __( 'The button will be activated if you insert text', 'revolution-press' ),			
			'section'  => 'seos_header_buttons_section',			
			'settings' => 'button_1',
			'type' => 'text',
		) ) );
		
		$wp_customize->add_setting( 'button_1_link', array (
			'sanitize_callback' => 'esc_url_raw',
		) );
		
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'button_1_link', array(
			'label'    => __( 'Button 1 URL', 'revolution-press' ),		
			'section'  => 'seos_header_buttons_section',
			'settings' => 'button_1_link',
		) ) );

/************************************
* Animation Articles
************************************/

		$wp_customize->add_setting( 'revolution_press_button_1_animation', array (
			'sanitize_callback' => 'revolution_press_sanitize_menu_animations',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'revolution_press_button_1_animation', array(
			'label'    => __( 'Button 1 Animation', 'revolution-press' ),
			'section'  => 'seos_header_buttons_section',
			'settings' => 'revolution_press_button_1_animation',
			'type'     =>  'select',
            'choices'  => revolution_press_animations_menu(),		
		) ) );		

		$wp_customize->add_setting( 'button_2', array (
            'default' => '',		
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'button_2', array(
			'label'    => __( 'Button 2 Text', 'revolution-press' ),
			'description'    => __( 'The button will be activated if you insert text', 'revolution-press' ),			
			'section'  => 'seos_header_buttons_section',			
			'settings' => 'button_2',
			'type' => 'text',
		) ) );
		
		$wp_customize->add_setting( 'button_2_link', array (
			'sanitize_callback' => 'esc_url_raw',
		) );
		
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'button_2_link', array(
			'label'    => __( 'Button 2 URL', 'revolution-press' ),		
			'section'  => 'seos_header_buttons_section',
			'settings' => 'button_2_link',
		) ) );
		
		$wp_customize->add_setting( 'revolution_press_button_2_animation', array (
			'sanitize_callback' => 'revolution_press_sanitize_menu_animations',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'revolution_press_button_2_animation', array(
			'label'    => __( 'Button 2 Animation', 'revolution-press' ),
			'section'  => 'seos_header_buttons_section',
			'settings' => 'revolution_press_button_2_animation',
			'type'     =>  'select',
            'choices'  => revolution_press_animations_menu(),		
		) ) );					
}
add_action( 'customize_register', 'revolution_press_customize_register_header_buttons' );



function revolution_press_buttons () {
	$button1 =  get_theme_mod( 'button_1' );
	$button2 = get_theme_mod( 'button_2' );
	$button_1_link = get_theme_mod( 'button_1_link' );
	$button_2_link = get_theme_mod( 'button_2_link' );
	$button_1_animation = get_theme_mod( 'revolution_press_button_1_animation' );
	$button_2_animation = get_theme_mod( 'revolution_press_button_2_animation' );

	?>
	<div>
	<?php if($button1) { ?>	
	<div class='h-button-1 animated <?php if($button_1_animation) { echo esc_html( $button_1_animation ); } else { echo "fadeInLeft"; } ?>'>	
		<a href='<?php echo esc_url( $button_1_link ); ?>'><?php echo esc_html( $button1 ); ?></a>
	</div>
	<?php } ?>
	<?php if($button2) { ?>	
	<div class='h-button-2 animated <?php if($button_2_animation) { echo esc_html( $button_2_animation ); } else { echo "fadeInRight"; } ?>'>	
		<a href='<?php echo esc_url( $button_2_link ); ?>'><?php echo esc_html( $button2 ); ?></a>	
	</div>
	<?php } ?>
	</div>
<?php
}
add_action('revolution_press_buttons_header', 'revolution_press_buttons');

	//Menu Animations
function revolution_press_animations_menu(){
	$array = array(
				'none' => esc_attr__( 'Deactivate Animation', 'revolution-press' ),
				'fadeInUp' => esc_attr__( 'Default', 'revolution-press' ),				
				'fadeIn' => esc_attr__( 'fadeIn', 'revolution-press' ),		
				'bounce' => esc_attr__( 'bounce', 'revolution-press' ),
				'bounceIn' => esc_attr__( 'bounceIn', 'revolution-press' ),
				'bounceInDown' => esc_attr__( 'bounceInDown', 'revolution-press' ),
				'bounceInLeft' => esc_attr__( 'bounceInLeft', 'revolution-press' ),
				'bounceInRight' => esc_attr__( 'bounceInRight', 'revolution-press' ),
				'bounceInUp' => esc_attr__( 'bounceInUp', 'revolution-press' ),
				'fadeInDownBig' => esc_attr__( 'fadeInDownBig', 'revolution-press' ),
				'fadeInLeft' => esc_attr__( 'fadeInLeft', 'revolution-press' ),
				'fadeInLeftBig' => esc_attr__( 'fadeInLeftBig', 'revolution-press' ),
				'fadeInRight' => esc_attr__( 'fadeInRight', 'revolution-press' ),
				'fadeInRightBig' => esc_attr__( 'fadeInRightBig', 'revolution-press' ),
				'fadeInUp' => esc_attr__( 'fadeInUp', 'revolution-press' ),
				'fadeInUpBig' => esc_attr__( 'fadeInUpBig', 'revolution-press' ),
				'flash' => esc_attr__( 'flash', 'revolution-press' ),
				'lightSpeedIn' => esc_attr__( 'lightSpeedIn', 'revolution-press' ),
				'pulse' => esc_attr__( 'pulse', 'revolution-press' ),
				'rollIn' => esc_attr__( 'rollIn', 'revolution-press' ),
				'rotateIn' => esc_attr__( 'rotateIn', 'revolution-press' ),
				'rotateInDownLeft' => esc_attr__( 'rotateInDownLeft', 'revolution-press' ),
				'rotateInDownRight' => esc_attr__( 'rotateInDownRight', 'revolution-press' ),
				'rotateInUpLeft' => esc_attr__( 'rotateInUpLeft', 'revolution-press' ),
				'rotateInUpRight' => esc_attr__( 'rotateInUpRight', 'revolution-press' ),
				'shake' => esc_attr__( 'shake', 'revolution-press' ),
				'slideInDown' => esc_attr__( 'slideInDown', 'revolution-press' ),
				'slideInLeft' => esc_attr__( 'slideInLeft', 'revolution-press' ),
				'slideInRight' => esc_attr__( 'slideInRight', 'revolution-press' ),
				'slideInUp' => esc_attr__( 'slideInUp', 'revolution-press' ),
				'swing' => esc_attr__( 'swing', 'revolution-press' ),
				'tada' => esc_attr__( 'tada', 'revolution-press' ),
				'wobble' => esc_attr__( 'wobble', 'revolution-press' ),
				'zoomInDown' => esc_attr__( 'zoomInDown', 'revolution-press' ),
				'zoomInLeft' => esc_attr__( 'zoomInLeft', 'revolution-press' ),
				'zoomInRight' => esc_attr__( 'zoomInRight', 'revolution-press' ),
				'zoomInUp' => esc_attr__( 'zoomInUp', 'revolution-press' ),
				);
	return $array;
}
		function revolution_press_sanitize_menu_animations( $input ) {
			$valid = revolution_press_animations_menu();
			if ( array_key_exists( $input, $valid ) ) {
				return $input;
			} else {
				return '';
			}
		}