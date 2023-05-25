<?php
	// Do not allow direct access to the file.
	if( ! defined( 'ABSPATH' ) ) {
		exit;
	}
add_action( 'customize_register', 'revolution_press__header_top_customize_register' );
function revolution_press__header_top_customize_register( $wp_customize ) {
/***********************************************************************************
 * Back to top button Options
 ***********************************************************************************/
		$wp_customize->add_section( 'header_top' , array(
			'title'       => __( 'Header Top', 'revolution-press' ),
			'priority'   => 2,
		) );
		$wp_customize->add_setting( 'activate_header_top', array (
			'sanitize_callback' => 'revolution_press__sanitize_checkbox',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'activate_header_top', array(
			'priority'    => 1,
			'label'    => __( 'Deactivate Header Top', 'revolution-press' ),
			'section'  => 'header_top',
			'settings' => 'activate_header_top',
			'type' => 'checkbox',
		) ) );

 	    $wp_customize->add_setting( 'header_email', array (
		    'default' => 'email@myemail.com',	
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'header_email', array(
			'label'    => __( 'E-mail', 'revolution-press' ),
			'priority'    => 3,
			'section'  => 'header_top',
			'settings' => 'header_email',
			'type' => 'text',
		) ) );
 	    $wp_customize->add_setting( 'header_address', array (
			'default' => 'Str. 368',
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'header_address', array(
			'label'    => __( 'Address', 'revolution-press' ),
			'priority'    => 3,
			'section'  => 'header_top',
			'settings' => 'header_address',
			'type' => 'text',
		) ) );
 	    $wp_customize->add_setting( 'header_phone', array (
			'default' => '+01234567890',		
			'sanitize_callback' => 'sanitize_text_field',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'header_phone', array(
			'label'    => __( 'Phone', 'revolution-press' ),
			'priority'    => 3,
			'section'  => 'header_top',
			'settings' => 'header_phone',
			'type' => 'text',
		) ) );
		
		$wp_customize->add_setting( 'revolution_press_header_search', array (
            'default' => '',		
			'sanitize_callback' => 'revolution_press__sanitize_checkbox',
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'revolution_press_header_search', array(
			'label'    => __( 'Activate search', 'revolution-press' ),
			'section'  => 'header_top',
			'priority'    => 1,				
			'settings' => 'revolution_press_header_search',
			'type' => 'checkbox',
		) ) );
		
		if( class_exists( 'WooCommerce' ) ) {		
			$wp_customize->add_setting( 'revolution_press_header_cart', array (
				'default' => '',		
				'sanitize_callback' => 'revolution_press__sanitize_checkbox',
			) );
			$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'revolution_press_header_cart', array(
				'label'    => __( 'Activate WooCommerce Cart', 'revolution-press' ),
				'section'  => 'header_top',
				'priority'    => 1,				
				'settings' => 'revolution_press_header_cart',
				'type' => 'checkbox',
			) ) );
	
		}		
}	

/**
 * Search Top
 */
add_action( 'revolution_press_header_search_top', 'revolution_press_search_top' );
function revolution_press_search_top()
{
    if ( get_theme_mod( 'revolution_press_header_search' ) ) {
        echo  '<div class="s-search-top">
					<i onclick="fastSearch()" id="search-top-ico" class="dashicons dashicons-search"></i>
					<div id="big-search" style="display:none;">
						<form method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
							<div style="position: relative;">
							<button class="button-primary button-search"><span class="screen-reader-text">' . _x( 'Search for:', 'label', 'revolution-press' ) . '</span></button>
								<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'revolution-press' ) . '</span>
								<div class="s-search-show">
									<input id="s-search-field"  type="search" class="search-field"
									placeholder="' . esc_attr_x( 'Search ...', 'placeholder', 'revolution-press' ) . '"
									value="' . get_search_query() . '" name="s"
									title="' . esc_attr_x( 'Search for:', 'label', 'revolution-press' ) . '" />
									<input type="submit" id="stss" class="search-submit" value="' . esc_attr_x( 'Search', 'submit button', 'revolution-press' ) . '" />
									<div onclick="fastCloseSearch()" id="s-close">X</div>
								</div>	
							</div>
						</form>
					</div>	
			</div>' ;
    }
}


	function revolution_press__header () {
		
	?>
	<header class="site-header" itemscope="itemscope" itemtype="http://schema.org/WPHeader">
	<?php if ( get_theme_mod( 'revolution_press_active_social' ) ) { echo revolution_press_social_section ("social-top"); }  ?>
	<?php if ( !get_theme_mod( 'activate_header_top' ) ) { ?>
		<div class="header-top">
			<div id="top-contacts" class="before-header">
				<?php if (get_theme_mod('header_email', 'email@myemail.com')) { ?>
							<div class="h-email" itemprop="email"><a href="mailto:<?php echo esc_html( get_theme_mod( 'header_email', 'email@myemail.com') ); ?>"><span class="dashicons dashicons-email-alt"> </span> <?php echo esc_html( get_theme_mod( 'header_email', 'email@myemail.com') ); ?></a></div>
						<?php } ?>
						<?php if (get_theme_mod('header_address','Str. 368')) { ?>
							<div class="h-address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span class="dashicons dashicons-location"></span><?php echo esc_html( get_theme_mod( 'header_address','Str. 368') ); ?></div>
						<?php } ?>
						<?php if (get_theme_mod('header_phone','+01234567890')) { ?>
							<div class="h-phone" itemprop="telephone"><a href="tel:<?php echo esc_html( get_theme_mod( 'header_phone','+01234567890') ); ?>"><span class="dashicons dashicons-phone"> </span> <?php echo esc_html( get_theme_mod( 'header_phone','+01234567890') ); ?></a></div>
						<?php } ?>
				<div class="nav-top-detiles">
					<?php
					do_action( 'revolution_press_header_search_top' );
					do_action( 'revolution_press_header_woo_cart' );
					?>	
				</div>			
			</div>
			<?php do_action( 'revolution_press_login' ); ?>					
		</div>
		<?php
		}	
	?>
	
	
	<div style="position: relative;">
		<?php if( !get_theme_mod( 'hide_menu' ) ) { ?>
			<div id="menu-top" class="menu-cont">
				<div class="grid-menu">
					<div id="grid-top" class="grid-top">
						<!-- Site Navigation  -->
						<div class="header-right" itemprop="logo" itemscope="itemscope" itemtype="http://schema.org/Brand">
							<?php the_custom_logo(); ?>
						</div>	
						<button id="s-button-menu" class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><img alt="mobile" src="<?php echo esc_url(get_template_directory_uri() ) . '/images/mobile.jpg'; ?>"/></button>
						<div class="mobile-cont">
							<div class="mobile-logo" itemprop="logo" itemscope="itemscope" itemtype="http://schema.org/Brand">
								<?php the_custom_logo(); ?>
							</div>
						</div>
						
						<nav id="site-navigation" class="main-navigation">
							
							<button class="menu-toggle"><?php esc_html_e( 'Menu', 'revolution-press' ); ?></button>
							<?php wp_nav_menu( array( 
								'theme_location' => 'primary',
								'menu_id' => 'primary-menu'	
							) ); ?>
						</nav><!-- #site-navigation -->
						
					</div>
				</div>
			</div>
		<?php } ?>
		<?php if( get_theme_mod( 'activate_dark_mode', 1) ) { ?>	
			<div class="dark-mode-button">
				<div class="dark-mode-button-inner-left"></div>
				<div class="dark-mode-button-inner"></div>
			</div>
		<?php } ?>	
		<!-- Header Image  -->
		
	</div>	
		<?php if( has_header_image() and  (is_front_page() or is_home() ) and get_theme_mod( 'header_image_show', "home" ) == 'home' ) { ?>	
			<div class="all-header">
				<div class="s-shadow"></div>
				<div class="dotted"></div>
				<div class="s-hidden">
					<?php if (get_theme_mod( 'header_image_position' ) == 'default' ) { ?>
						<img id="masthead" style="<?php revolution_press__heade_image_zoom_speed (); ?>" class="header-image" src='<?php echo esc_url(get_template_directory_uri() ) . '/images/header.webp'; ?>' alt="<?php esc_attr_e( 'header image','revolution-press' ); ?>"/>	
					<?php } ?>
					<?php if (get_theme_mod( 'header_image_position' ) == 'real' ) { ?>
						<img id="masthead" style="<?php revolution_press__heade_image_zoom_speed (); ?>" class="header-image" src='<?php if ( !is_home() and has_post_thumbnail() and get_post_meta( get_the_ID(), 'revolution_press__value_header_image', true ) ) { the_post_thumbnail_url(); } else { header_image(); } ?>' alt="<?php esc_attr_e( 'header image','revolution-press' ); ?>"/>	
						<?php } else { ?>
						<div id="masthead" class="header-image" style="<?php revolution_press__heade_image_zoom_speed (); ?> background-image: url( '<?php if (  !is_home() and has_post_thumbnail() and get_post_meta( get_the_ID(), 'revolution_press__value_header_image', true ) ) { the_post_thumbnail_url(); } else { header_image(); } ?>' );"></div>
					<?php } ?>
				</div>
				<div class="site-branding">
					<?php if ( display_header_text() == true ) { ?>
						<?php
							
						
						if ( is_front_page() && is_home() ) :
						?>
						<h1 id="site-title" class="site-title" itemscope itemtype="http://schema.org/Brand"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><span class="ml2"><?php bloginfo( 'name' ); ?></span></a></h1>
						<?php
							else :
						?>
						<p id="site-title" class="site-title" itemscope itemtype="http://schema.org/Brand"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><span class="ml2"><?php bloginfo( 'name' ); ?></span></a></p>
						<?php
							endif;
							$revolution_press__description = esc_html(get_bloginfo( 'description', 'display' ) );
							if ( $revolution_press__description || is_customize_preview() ) :
						?>    
						<p class="site-description" itemprop="headline">
							<span class="word"><?php echo esc_html($revolution_press__description); ?></span>
						</p>
						<?php endif; ?>	
						<?php }
						
					do_action('revolution_press_buttons_header'); ?>	
				</div>
				<!-- .site-branding -->
			</div>
		<?php } ?>
		
		<?php if (has_header_image() and get_theme_mod( 'header_image_show', "home"  ) == 'all' ) { ?>	
			<div class="all-header">
				<div class="s-shadow"></div>
				<div class="dotted"></div>
				<div class="s-hidden">
					<?php if (get_theme_mod( 'header_image_position' ) == 'default' ) { ?>
						<img id="masthead" style="<?php revolution_press__heade_image_zoom_speed (); ?>" class="header-image" src='<?php echo esc_url(get_template_directory_uri() ) . '/images/header.webp'; ?>' alt="<?php esc_attr_e( 'header image','revolution-press' ); ?>"/>	
					<?php } ?>
					<?php if (get_theme_mod( 'header_image_position' ) == 'real' ) { ?>
						<img id="masthead" style="<?php revolution_press__heade_image_zoom_speed (); ?>" class="header-image" src='<?php if ( !is_home() and has_post_thumbnail() and get_post_meta( get_the_ID(), 'revolution_press__value_header_image', true ) ) { the_post_thumbnail_url(); } else { header_image(); } ?>' alt="<?php esc_attr_e( 'header image','revolution-press' ); ?>"/>	
						<?php } else { ?>
						<div id="masthead" class="header-image" style="<?php revolution_press__heade_image_zoom_speed (); ?> background-image: url( '<?php if (  !is_home() and has_post_thumbnail() and get_post_meta( get_the_ID(), 'revolution_press__value_header_image', true ) ) { the_post_thumbnail_url(); } else { header_image(); } ?>' );"></div>
					<?php } ?>
				</div>
				<div class="site-branding">
					<?php if ( display_header_text() == true ) { ?>
						<?php
							
							
							if ( is_front_page() && is_home() ) :
						?>
						<h1 id="site-title" class="site-title" itemscope itemtype="http://schema.org/Brand"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><span class="ml2"><?php bloginfo( 'name' ); ?></span></a></h1>
						<?php
							else :
						?>
						<p id="site-title" class="site-title" itemscope itemtype="http://schema.org/Brand"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><span class="ml2"><?php bloginfo( 'name' ); ?></span></a></p>
						<?php
							endif;
							$revolution_press__description = esc_html(get_bloginfo( 'description', 'display' ) );
							if ( $revolution_press__description || is_customize_preview() ) :
						?>    
						<p class="site-description" itemprop="headline">
							<span class="word"><?php echo esc_html($revolution_press__description); ?></span>
						</p>
						<?php endif; ?>	
						<?php }
						
					do_action('revolution_press_buttons_header'); ?>	
				</div>
				<!-- .site-branding -->
			</div>
		<?php } ?>	
		
		
	</header>
<?php }					