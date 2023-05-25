<?php if( ! defined( 'ABSPATH' ) ) exit;
	
	function revolution_press_how_to_scripts() {
		wp_enqueue_style( 'how-to-use', get_template_directory_uri() . '/include/pro/pro.css' );
	}
	
	add_action( 'admin_enqueue_scripts', 'revolution_press_how_to_scripts' );	
	
	// create custom plugin settings menu
	add_action('admin_menu', 'revolution_press__create_menu');
	
	function revolution_press__create_menu() {
		
		//create new top-level menu
		global $revolution_press__settings_page;
		
		$revolution_press__settings_page = add_theme_page('Revolution Press', 'Revolution Press', 'edit_theme_options',  'revolution_press-unique-identifier', 'revolution_press__settings_page');
		
		//call register settings function
		add_action( 'admin_init', 'register_mysettings' );
	}
	
	function register_mysettings() {
		//register our settings
		register_setting( 'seos-settings-group', 'adsense' );
	}
	
	function revolution_press__settings_page() {	
	$path_img = get_template_directory_uri()."/include/pro/"; ?>
	<div id="cont-pro">
		<h1><?php esc_html_e('Revolution Press WordPress Theme', 'revolution-press'); ?></h1>	
		<div class="pro-links">	
		<p><?php esc_html_e('We create free themes and have helped thousands of users to build their sites. You can also support us using the Revolution Press Pro theme with many new features and extensions.', 'revolution-press'); ?></p>
			<a class="button button-primary" target="_blank" href="https://seosthemes.com/themes/revolution-press-wordpress-theme/"><?php esc_html_e('Theme Demo', 'revolution-press'); ?></a>
			<a style="background: #A83625;" class="reds button button-primary" target="_blank" href="https://seosthemes.com/revolution-press"><?php esc_html_e('Upgrade to PRO', 'revolution-press'); ?></a>
		</div>	
		<table id="table-colors" class="free-wp-theme">
			<tbody>
				<tr>
					<th><?php esc_html_e('Revolution Press WordPress Theme', 'revolution-press'); ?></th>
					<th><?php esc_html_e('Free WP Theme','revolution-press'); ?></th>
					<th><?php esc_html_e('Premium WP Theme','revolution-press'); ?></th>
				</tr>			
				<tr class="s-white">
					<td><strong><?php esc_html_e('About US', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>				
				<tr>
					<td><strong><?php esc_html_e('Sidebar Position', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Counter', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>								
				<tr>
					<td><strong><?php esc_html_e('Portfolio Filter', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Recent Posts Slider', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Popular Posts', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>							
				<tr class="s-white">
					<td><strong><?php esc_html_e('Page Right Sidebar', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>				
				<tr>
					<td><strong><?php esc_html_e('Page Left Sidebar', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>					
				<tr class="s-white">
					<td><strong><?php esc_html_e('Blog Page', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Blog Page Right Sidebar', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Blog Page Full Width', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Blog Page Three Columns', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Blog Page Two Columns', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>					
				<tr>
					<td><strong><?php esc_html_e('Camera Slider', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Title Position', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('One Click Demo Import', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>					
				<tr class="s-white">
					<td><strong><?php esc_html_e('Post Options', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('WooCommerce My Account Icon', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Multiple Gallery', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Animations of all elements', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Header Options', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Hide Single Page Title', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Featured Image', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('WooCommerce Product Zoom', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('WooCommerce Cart Option', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('WooCommerce Pagination', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>

				<tr class="s-white">
					<td><strong><?php esc_html_e('Google Fonts', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Shortcode', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Color of All Elements', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Full Width Page', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>	
				<tr class="s-white">
					<td><strong><?php esc_html_e('Social Media Icons', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Custom Footer Copyright', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Microdata', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Translation Ready', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr class="s-white">
					<td><strong><?php esc_html_e('Header Logo', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>
				<tr>
					<td><strong><?php esc_html_e('Homepage Widgets', 'revolution-press'); ?></strong></td>
					<td><img src="<?php echo esc_url($path_img); ?>NO.ico" alt="free-wp-theme" /></td>
					<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
				</tr>				
				
				<tr>
					<tr class="s-white">
						<td><strong><?php esc_html_e('Header Image', 'revolution-press'); ?></strong></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e('Background Image', 'revolution-press'); ?></strong></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					</tr>
					<tr class="s-white">
						<td><strong><?php esc_html_e('404 Page Template', 'revolution-press'); ?></strong></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e('Footer Widgets', 'revolution-press'); ?></strong></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					</tr>
					<tr class="s-white">
						<td><strong><?php esc_html_e('WooCommerce Plugin Support', 'revolution-press'); ?></strong></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					</tr>
					<tr>
						<td><strong><?php esc_html_e('Back to top button', 'revolution-press'); ?></strong></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
						<td><img src="<?php echo esc_url($path_img); ?>YES.ico" alt="free-wp-theme" /></td>
					</tr>
					<tr>
						
						<td><a class="button button-primary" target="_blank" href="https://seosthemes.com/themes/revolution-press-wordpress-theme/"><?php esc_html_e('Theme Demo', 'revolution-press'); ?></a></td>
						<td> </td>
						<td style=" text-align:center;"><a style="background: #A83625;" class="reds button button-primary" target="_blank" href="https://seosthemes.com/revolution-press"><?php esc_html_e('Upgrade to PRO', 'revolution-press'); ?></a></td>
					</tr>					
				</tbody>
			</table>
		</div>
		<?php	
		}		