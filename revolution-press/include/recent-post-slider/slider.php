<?php if( ! defined( 'ABSPATH' ) ) exit;

/**
 * Enqueue scripts and styles.
 */
function revolution_press_recent_post_slider_scripts() {	
	wp_enqueue_style( 'hot-slick-css', get_template_directory_uri() . '/include/recent-post-slider/slick.css' );
	wp_enqueue_script( 'hot-slick-js', get_template_directory_uri() . '/include/recent-post-slider/slick.js', array(), '', false );
}
add_action( 'wp_enqueue_scripts', 'revolution_press_recent_post_slider_scripts' );


function revolution_press_recent_post_slider (){ ?>

<div class="container-slider">
	<?php if(get_theme_mod('revolution_press_recent_post_title')) { ?>
		<h4 class="sca-slider-title"><?php echo esc_html(get_theme_mod('revolution_press_recent_post_title')); ?></h4>
		<div class="seos-border1"></div>
	<?php } ?>		
		<div class="autoplay1">
		
			
<?php

/* Get all sticky posts */
$sticky = get_option( 'sticky_posts' );
 
/* Sort the stickies with the newest ones at the top */
rsort( $sticky );
 
/* Get the 5 newest stickies (change 5 for a different number) */
$sticky = array_slice( $sticky, 0, 50 );
 
/* Query sticky posts */
if (get_theme_mod('revolution_press_recent_post_activate') == "sticky") {
    $the_query = new WP_Query( array( 'post__in' => $sticky, 'ignore_sticky_posts' => 1, 'posts_per_page' => 50  ) );
}
elseif (get_theme_mod('revolution_press_recent_post_activate', 'all') == "all") {
    $the_query = new WP_Query( array(  'ignore_sticky_posts' => 1, 'posts_per_page' => 50  ) );	
}

if(get_theme_mod('revolution_press_recent_post_activate', 'all') ) {
// The Loop
if ( $the_query->have_posts() ) { ?>
  
    <?php while ( $the_query->have_posts() ) {
        $the_query->the_post(); ?>
		
		<div class="sca-slide">
			<?php
			global $post; ?>
			<?php if ( has_post_thumbnail() ) { 
			?>      <a href="<?php echo esc_url(get_permalink()); ?>"> <?php
						the_post_thumbnail( 'custom-size' );
					?></a> <?php
					} 
					else {
						?>
							<a href="<?php echo esc_url(get_permalink()); ?>"><img src="<?php echo esc_url(get_template_directory_uri()); ?>/images/no-sticky.jpg"/></a>
						<?php } ?>
						<h3><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
						<div class="seos-border"></div>
						<div class="foo-detiles">
						    <?php echo esc_html( get_the_date() ); ?> /
						    <?php echo esc_html( get_the_author() ); ?>
						</div>
		</div>
					<?php
    }
}
/* Restore original Post Data */
wp_reset_postdata();
}
//return $return; 
?>
	</div>

</div>

<script>
jQuery('.autoplay1').not('.slick-initialized').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  autoplay: true,
  autoplaySpeed: 3000,
});
</script>
<?php }
	
function revolution_press_recent_post_customize_register( $wp_customize ) {

/***********************************************************************************/

		function revolution_press_sanitize_recent_post( $input ) {
			$valid = array(
  			    'all' => esc_attr__( 'Show All Posts', 'revolution-press' ),
				'sticky' => esc_attr__( 'Show Only Sticky Posts', 'revolution-press' ),
			);

			if ( array_key_exists( $input, $valid ) ) {
				return $input;
			} else {
				return '';
			}
		}

		$wp_customize->selective_refresh->add_partial( 'revolution_press_recent_post_activate', array(
			'selector' => '.sca-slide',
			'render_callback' => 'revolution_press_customize_partial_revolution_press_recent_post_activate',
		) );
		
		$wp_customize->selective_refresh->add_partial( 'revolution_press_recent_post_title', array(
			'selector' => '.sca-slider-title',
			'render_callback' => 'revolution_press_customize_partial_revolution_press_recent_post_title',
		) );			
/***************** Slider General *********************/
		
		$wp_customize->add_section( 'revolution_press_recent_post_options' , array(
			'title'       => __( 'Recent Posts Slider', 'revolution-press' ),
			'priority'   => 25,
		) );
						
		$wp_customize->add_setting( 'revolution_press_recent_post_activate', array (
			'sanitize_callback' => 'revolution_press_sanitize_recent_post',
			'default' => 'all',
		) );
		
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'revolution_press_recent_post_activate', array(
			'label'    => __( 'Recent Posts Slider', 'revolution-press' ),
			'section'  => 'revolution_press_recent_post_options',		
			'settings' => 'revolution_press_recent_post_activate',
			'type'     =>  'select',		
            'choices'  => array(
  			    'all' => esc_attr__( 'Show All Posts', 'revolution-press' ),
				'sticky' => esc_attr__( 'Show Only Sticky Posts', 'revolution-press' ),
            )
		) ) );
		
		$wp_customize->add_setting( 'revolution_press_recent_post_home_header', array (
			'sanitize_callback' => 'revolution_press__sanitize_checkbox',
			'default' => true
		) );
		
		
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'revolution_press_recent_post_home_header', array(
			'label'    => __( 'Activate on Homepage Header', 'revolution-press' ),
			'section'  => 'revolution_press_recent_post_options',
			'settings' => 'revolution_press_recent_post_home_header',
			'type' => 'checkbox',
		) ) );
		
		$wp_customize->add_setting( 'revolution_press_recent_post_home', array (
			'sanitize_callback' => 'revolution_press__sanitize_checkbox',
		) );
		
		
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'revolution_press_recent_post_home', array(
			'label'    => __( 'Activate on Homepage Footer', 'revolution-press' ),
			'section'  => 'revolution_press_recent_post_options',
			'settings' => 'revolution_press_recent_post_home',
			'type' => 'checkbox',
		) ) );
		$wp_customize->add_setting( 'revolution_press_recent_post_title', array (
			'sanitize_callback' => 'sanitize_text_field',
		) );
		
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'revolution_press_recent_post_title', array(
			'label'    => __( 'Section Title', 'revolution-press' ),		
			'section'  => 'revolution_press_recent_post_options',
			'settings' => 'revolution_press_recent_post_title',
			'type'     =>  'text'				
		) ) );		
		
}
add_action( 'customize_register', 'revolution_press_recent_post_customize_register' );