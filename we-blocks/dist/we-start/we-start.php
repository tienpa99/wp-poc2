<?php
/**
 * WE Blocks page
 *
 * @package WE Blocks
 */

/**
 * Load WE Blocks styles in the admin
 *
 * since 1.0.0
 */
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function we_blocks_start_load_admin_scripts( $hook ) {

	$postfix = ( SCRIPT_DEBUG == true ) ? '' : '.min';

	/**
	 * Load scripts and styles
	 *
	 * @since 1.0
	 */

	// WE Blocks javascript
	wp_enqueue_script( 'we-blocks-we-start', plugins_url( 'we-start/we-start.js', dirname( __FILE__ ) ), array( 'jquery' ), '1.0.0', true );

	// WE Blocks styles
	wp_register_style( 'we-blocks-we-start', plugins_url( 'we-start/we-start.css', dirname( __FILE__ ) ), false, '1.0.0' );
	wp_enqueue_style( 'we-blocks-we-start' );

	// FontAwesome
	wp_register_style( 'we-blocks-fontawesome', plugins_url( '../assets/fontawesome/css/all' . $postfix . '.css', dirname( __FILE__ ) ), false, '1.0.0' );
	wp_enqueue_style( 'we-blocks-fontawesome' );
}
add_action( 'admin_enqueue_scripts', 'we_blocks_start_load_admin_scripts' );


/**
 * Adds a menu item for the WE Blocks page.
 *
 * since 1.0.0
 */
function we_blocks_getting_started_menu() {

	add_menu_page(
		__( 'WE Blocks', 'we-blocks' ),
		__( 'WE Blocks', 'we-blocks' ),
		'manage_options',
		'we-blocks',
		'we_blocks_we_start_page',
		'dashicons-screenoptions'
	);

}
add_action( 'admin_menu', 'we_blocks_getting_started_menu' );


/**
 * Outputs the markup used on the WE Blocks
 *
 * since 1.0.0
 */
function we_blocks_we_start_page() {
?>
	<div class="wrap ab-we-start">
		<div class="intro-wrap">
			<div class="intro">
				<a href="<?php echo esc_url('https://wordpresteem.com'); ?>"><img class="we-logo" src="<?php echo esc_url( plugins_url( 'logo.png', __FILE__ ) ); ?>" alt="<?php esc_html_e( 'Visit we Blocks', 'we-blocks' ); ?>" /></a>
				<h3><?php printf( esc_html__( 'WE Blocks By', 'we-blocks' ) ); ?> <strong><?php printf( esc_html__( 'WORDPRESTEEM.COM', 'we-blocks' ) ); ?></strong></h3>
			</div>

			<ul class="inline-list">
				<li class="current"><a id="we-blocks-panel" href="#"><i class="fa fa-check"></i> <?php esc_html_e( 'WE Blocks', 'we-blocks' ); ?></a></li>
				<!--<li><a id="plugin-help" href="#"><i class="fa fa-plug"></i> <?php esc_html_e( 'Plugin Help File', 'we-blocks' ); ?></a></li>-->
			</ul>
		</div>

		<div class="panels">
			<div id="panel" class="panel">
				<div id="we-blocks-panel" class="panel-left visible">
					<div class="ab-block-split clearfix">
						<div class="ab-block-split-left">
							<div class="ab-titles">
								<h2><?php esc_html_e( 'The WE Blocks plugin is great combo of slider blocks!', 'we-blocks' ); ?></h2>
								<p><?php esc_html_e( 'The WE Blocks plugin is great combo of slider blocks. It includes Posts slider, Image slider, Testimonials slider and Client Logo slider block. Blocks are an awesome new way of creating rich content in WordPress and this plugin will further ease your life to add sliders on your site. It makes it easier to create responsive, customizable Posts/Image/Testimonial/Logo sliders in the WordPress Gutenberg Editor.', 'we-blocks' ); ?></p>
							</div>
						</div>
						<div class="ab-block-split-right">
							<div class="ab-block-theme">
								<img src="<?php echo esc_url( plugins_url( 'banner.png', __FILE__ ) ) ?>" alt="<?php esc_html_e( 'we Blocks Theme', 'we-blocks' ); ?>" />
							</div>
						</div>
					</div>

					<div class="ab-block-feature-wrap clear">
						<i class="fas fa-cube"></i>
						<h2><?php esc_html_e( 'Available WE Blocks', 'we-blocks' ); ?></h2>
						<p><?php esc_html_e( 'The following blocks are available in WE Blocks. More blocks are on the way so stay tuned!', 'we-blocks' ); ?></p>

						<div class="ab-block-features" style="text-align: center;display: inline-block;">
							
							<div class="ab-block-feature" style="float: unset;margin: 0% 0 4% 0;width: 80%;">
								<div class="ab-block-feature-icon"><img src="<?php echo esc_url( plugins_url( 'post-slider.png', __FILE__ ) ) ?>" alt="Testimonials slider/list" /></div>
								<div class="ab-block-feature-text">
									<h3><?php esc_html_e( 'Posts Slider', 'we-blocks' ); ?></h3>
									<p><?php esc_html_e( 'This plugins contains slider posts blocks which will allows you to show your wordpress posts as slider (Gutenberg). It has all the necessary option that will help you to change slider style.', 'we-blocks' ); ?></p>

									<a class="tablinks" onclick="openTab(event, 'posts_slider')">#More Styles</a>	

								</div>
							</div>

						</div><!-- .ab-block-features -->


						<div class="ab-block-features">
							<div class="ab-block-feature">
								<div class="ab-block-feature-icon"><img src="<?php echo esc_url( plugins_url( 'slider.png', __FILE__ ) ) ?>" alt="Image slider" /></div>
								<div class="ab-block-feature-text">
									<h3><?php esc_html_e( 'Image Slider', 'we-blocks' ); ?></h3>
									<p><?php esc_html_e( 'Just add multiple images and it will appear as a slider.', 'we-blocks' ); ?></p>
								</div>
							</div>

							<div class="ab-block-feature">
								<div class="ab-block-feature-icon"><img src="<?php echo esc_url( plugins_url( 'testimonials.png', __FILE__ ) ) ?>" alt="Testimonials slider/list" /></div>
								<div class="ab-block-feature-text">
									<h3><?php esc_html_e( 'Testimonials Slider/List', 'we-blocks' ); ?></h3>
									<p><?php esc_html_e( 'Insert testimonial,  Add profile picture, author name, author position into the Testimonials Slider.  You can also decide number of Testimonials to appear in slider.', 'we-blocks' ); ?></p>

									<a class="tablinks" onclick="openTab(event, 'testimonial')">#More Styles</a>	

								</div>
							</div>

							<div class="ab-block-feature">
								<div class="ab-block-feature-icon"><img src="<?php echo esc_url( plugins_url( 'logo-slider.png', __FILE__ ) ) ?>" alt="<?php esc_html_e( 'Logo slider/grid', 'we-blocks' ); ?>" /></div>
								<div class="ab-block-feature-text">
									<h3><?php esc_html_e( 'Logo Slider/Grid', 'we-blocks' ); ?></h3>
									<p><?php esc_html_e( 'Just add multiple logos and  it will appear as a slider. You can also decide number of logos to appear in slider.', 'we-blocks' ); ?></p>

									<a class="tablinks" onclick="openTab(event, 'logo')">#More Styles</a>

								</div>
							</div>

							<div id="posts_slider" class="tabcontent">
								<ul>
									<li>
										<img src="<?php echo esc_url( plugins_url( 'ps1.png', __FILE__ ) ) ?>">
										<h4>Style 1</h4>
									</li>
									<li>
										<img src="<?php echo esc_url( plugins_url( 'ps2.png', __FILE__ ) ) ?>">
										<h4>Style 2</h4>
									</li>
								</ul>                          
							</div>

							<div id="testimonial" class="tabcontent">
								<ul>
									<li>
										<img src="<?php echo esc_url( plugins_url( 't-01.png', __FILE__ ) ) ?>">
										<h4>Style 1</h4>
									</li>
									<li>
										<img src="<?php echo esc_url( plugins_url( 't-02.png', __FILE__ ) ) ?>">
										<h4>Style 2</h4>
									</li>
									<li>
										<img src="<?php echo esc_url( plugins_url( 't-03.png', __FILE__ ) ) ?>">
										<h4>Style 3</h4>
									</li>
								</ul>                          
							</div>

							<div id="logo" class="tabcontent">
								<ul>
									<li>
										<img src="<?php echo esc_url( plugins_url( 'l-01.png', __FILE__ ) ) ?>">
										<h4>Slider</h4>
									</li>
									<li>
										<img src="<?php echo esc_url( plugins_url( 'l-02.png', __FILE__ ) ) ?>">
										<h4>Grid</h4>
									</li>
								</ul>                          
							</div>

							
						</div><!-- .ab-block-features -->
					</div><!-- .ab-block-feature-wrap -->
				</div><!-- .panel-left -->

				<!-- Plugin help file panel -->
				<div id="plugin-help" class="panel-left">
					<!-- Grab feed of help file -->
					<?php
						$plugin_help = __( 'This help file feed seems to be temporarily down. You can always view the help file on the we Blocks site in the meantime.', 'we-blocks' );
						
						echo $plugin_help;
					?>
				</div>

				
				<div class="panel-right">
					<div class="panel-aside panel-ab-plugin panel-club ab-quick-start">
						<div class="panel-club-inside">
							<div class="cell panel-title">
								<h3><i class="fa fa-check"></i> <?php esc_html_e( 'Quick Start Checklist', 'we-blocks' ); ?></h3>
							</div>

							<ul>
								<li class="cell <?php if( function_exists( 'gutenberg_init' ) ) { echo 'step-complete'; } ?>">
									<strong><?php esc_html_e( '1. Install the Gutenberg plugin.', 'we-blocks' ); ?></strong>
									<p><?php esc_html_e( 'Gutenberg adds the new block-based editor to WordPress. You will need this to work with the WE Blocks plugin.', 'we-blocks' ); ?></p>
								</li>

								<li class="cell <?php if( function_exists( 'we_blocks_loader' ) ) { echo 'step-complete'; } ?>">
									<strong><?php esc_html_e( '2. Install the we Blocks plugin.', 'we-blocks' ); ?></strong>
									<p><?php esc_html_e( 'WE Blocks adds several handy content blocks to the Gutenberg block editor.', 'we-blocks' ); ?></p>
								</li>
							</ul>
						</div>
					</div>
				</div><!-- .panel-right -->

				<div class="footer-wrap">
					<h2 class="visit-title"><?php esc_html_e( 'Free Blocks and Resources', 'we-blocks' ); ?></h2>

					<div class="ab-block-footer">
						<!--<div class="ab-block-footer-column">
							<i class="far fa-envelope"></i>
							<h3><?php esc_html_e( 'Blocks In Your Inbox', 'we-blocks' ); ?></h3>
							<p><?php esc_html_e( 'Join the newsletter to receive emails when we add new blocks, release plugin and theme updates, send out free resources, and more!', 'we-blocks' ); ?></p>
							<a class="button-primary" href="#"><?php esc_html_e( 'Subscribe Today', 'we-blocks' ); ?></a>
						</div>-->

						<div class="ab-block-footer-column">
							<i class="far fa-edit"></i>
							<h3><?php esc_html_e( 'Articles & Tutorials', 'we-blocks' ); ?></h3>
							<p><?php esc_html_e( 'Check out the we Blocks site to find block editor tutorials, free blocks and updates about the we Blocks plugin and theme!', 'we-blocks' ); ?></p>
							<a class="button-primary" href="https://www.wordpresteem.com/blog/"><?php esc_html_e( 'Visit the Blog', 'we-blocks' ); ?></a>
						</div>

						<div class="ab-block-footer-column">
							<i class="far fa-newspaper"></i>
							<h3><?php esc_html_e( 'Gutenberg News', 'we-blocks' ); ?></h3>
							<p><?php esc_html_e( 'Stay up to date with the new WordPress editor. Gutenberg News curates Gutenberg articles, tutorials, videos and more free resources.', 'we-blocks' ); ?></p>
							<a class="button-primary" href="http://gutenberg.news/?utm_source=AB%20Theme%20GS%20Page%20Footer%20Gnews"><?php esc_html_e( 'Visit Gutenberg News', 'we-blocks' ); ?></a>
						</div>
					</div>

					<div class="ab-footer">
						<p><?php echo sprintf( esc_html__( 'Made by the %1$s.', 'we-blocks' ), '<a href=" ' . esc_url( 'https:/wordpresteem.com/' ) . ' ">WORDPRESTEEM.COM</a>' ); ?></p>
						<div class="ab-footer-links">
							<!--<a href="https:/wordpresteem.com/"><?php esc_html_e( 'WORDPRESTEEM.COM', 'we-blocks' ); ?></a>-->
						</div>
					</div>
				</div><!-- .footer-wrap -->
			</div><!-- .panel -->
		</div><!-- .panels -->
	</div><!-- .we-start -->
<?php
}
