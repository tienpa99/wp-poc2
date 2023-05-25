<?php
	/*
		Plugin name: Rich-Web Tabs
		Plugin URI: https://rich-web.org/wp-tab-accordion/
		Description: Tabs plugin is fully responsive. Tabs plugin for the creating responsive tabbed panels with unlimited options and transition animations support.
		Version: 1.3.9
		Author: richteam
		Author URI: https://rich-web.org/
		License: http://www.gnu.org/licenses/gpl-2.0.html
	*/
	
	define('RW_TABS_PLUGIN_URL',plugin_dir_url(__FILE__));
	define('RW_TABS_PLUGIN_DIR_URL',dirname(__FILE__));
	require_once(RW_TABS_PLUGIN_DIR_URL . '/Tabs-Rich-Web-Widget.php');
	require_once(RW_TABS_PLUGIN_DIR_URL . '/Tabs-Rich-Web-Shortcode.php');
	require_once(RW_TABS_PLUGIN_DIR_URL . '/Tabs-Rich-Web-Ajax.php');

	add_action('admin_init','RW_Tabs_Admin_Init_Ajax');
	function RW_Tabs_Admin_Init_Ajax(){
		if(current_user_can('manage_options')){
			add_action( 'wp_ajax_RW_Tabs_Man_Copy_Opt', 'RW_Tabs_Man_Copy_Opt_Callback' );
			add_action( 'wp_ajax_RW_Tabs_Man_Delete_Opt', 'RW_Tabs_Man_Delete_Opt_Callback' );
			add_action( 'wp_ajax_RW_Tabs_Save_Data', 'RW_Tabs_Save_Data_Callback' );	
		}
	}
	add_action("admin_menu", 'Rich_Web_Tabs_Admin_Menu' );
	function Rich_Web_Tabs_Admin_Menu() 
	{
		add_menu_page('RW_Tabs_All','Tabs','manage_options','RW_Tabs_Menu','Manage_Rich_Web_Tabs_Flex', RW_TABS_PLUGIN_URL . '/Images/admin.png');
 		add_submenu_page( 'RW_Tabs_Menu', 'RW Tabs All', 'All Tabs', 'manage_options', 'RW_Tabs_Menu', 'Manage_Rich_Web_Tabs_Flex');
 		add_submenu_page( 'RW_Tabs_Menu', 'RW Tabs Builder', 'Create Tab', 'manage_options', 'RW_Tabs_Create', 'RW_Tabs_Create_New');
		add_submenu_page( 'RW_Tabs_Menu', 'RW Products', 'Our Products', 'manage_options', 'Rich-Web-Products', 'Manage_Rich_Web_Tabs_Products');
		add_submenu_page( 'RW_Tabs_Menu', 'RW Go Pro', '<span id="RWTabs_Upgrade" style="font-weight:800;color:#6ecae9;"><span class="rich_web rich_web-unlock-alt" style="font-size: 16px;margin-right:5px;"></span>Go Pro</span>', 'manage_options', 'Rich_WebTabsPro', 'Manage_Rich_Web_Tabs_Pro');
	}
	//Widget Scripts/Styles 
	add_action('wp_enqueue_scripts','Rich_Web_Tabs_Style');
	function Rich_Web_Tabs_Style()
	{
		wp_register_style('Rich_Web_Tabs', RW_TABS_PLUGIN_URL . '/Style/Tabs-Rich-Web-Widget.css');
		wp_enqueue_style('Rich_Web_Tabs');
		wp_register_script('Rich_Web_Tabs',RW_TABS_PLUGIN_URL . '/Scripts/Tabs-Rich-Web-Widget.js',array('jquery','jquery-ui-core','jquery-ui-resizable', 'jquery-effects-blind', 'jquery-effects-bounce', 'jquery-effects-clip', 'jquery-effects-drop', 'jquery-effects-explode', 'jquery-effects-fade', 'jquery-effects-fold', 'jquery-effects-highlight', 'jquery-effects-pulsate', 'jquery-effects-scale', 'jquery-effects-shake', 'jquery-effects-slide', 'jquery-effects-size', 'jquery-effects-puff'));
		wp_localize_script('Rich_Web_Tabs', 'rwtabs_object', array('ajaxurl' => admin_url('admin-ajax.php')));
		wp_enqueue_script('Rich_Web_Tabs');
		wp_register_style( 'rwtabs_fontawesome-css', RW_TABS_PLUGIN_URL . '/Style/richwebicons.css'); 
   		wp_enqueue_style( 'rwtabs_fontawesome-css' );
	}

	function Manage_Rich_Web_Tabs_Flex()
	{
		require_once(RW_TABS_PLUGIN_DIR_URL . '/Tabs-Rich-Web-Flex.php');
		require_once(RW_TABS_PLUGIN_DIR_URL . '/Scripts/Tabs-Rich-Web-Flex.js.php');
		wp_register_script('Rich_Web_Tabs',RW_TABS_PLUGIN_URL . '/Scripts/Tabs-Rich-Web-Widget.js',array('jquery','jquery-ui-core','jquery-ui-resizable', 'jquery-effects-blind', 'jquery-effects-bounce', 'jquery-effects-clip', 'jquery-effects-drop', 'jquery-effects-explode', 'jquery-effects-fade', 'jquery-effects-fold', 'jquery-effects-highlight', 'jquery-effects-pulsate', 'jquery-effects-scale', 'jquery-effects-shake', 'jquery-effects-slide', 'jquery-effects-size', 'jquery-effects-puff'));
		wp_localize_script('Rich_Web_Tabs', 'rwtabs_object', array('ajaxurl' => admin_url('admin-ajax.php'),'rw_tabs_ajax_wp_nonce' => wp_create_nonce('rw-tabs-ajax-nonce')));
		wp_enqueue_script('Rich_Web_Tabs');
	}

	function RW_Tabs_Create_New()
	{
		require_once(RW_TABS_PLUGIN_DIR_URL . '/Tabs-Rich-Web-Builder.php');
		wp_register_style('Rich_Web_Tabs', RW_TABS_PLUGIN_URL . '/Style/Tabs-Rich-Web-Widget.css');
		wp_enqueue_style('Rich_Web_Tabs');
		wp_register_script('Rich_Web_Tabs',RW_TABS_PLUGIN_URL . '/Scripts/Tabs-Rich-Web-Widget.js',array('jquery','jquery-ui-core','jquery-ui-resizable', 'jquery-effects-blind', 'jquery-effects-bounce', 'jquery-effects-clip', 'jquery-effects-drop', 'jquery-effects-explode', 'jquery-effects-fade', 'jquery-effects-fold', 'jquery-effects-highlight', 'jquery-effects-pulsate', 'jquery-effects-scale', 'jquery-effects-shake', 'jquery-effects-slide', 'jquery-effects-size', 'jquery-effects-puff'));
		wp_localize_script('Rich_Web_Tabs', 'rwtabs_object', array('ajaxurl' => admin_url('admin-ajax.php'),'rw_tabs_ajax_wp_nonce' => wp_create_nonce('rw-tabs-ajax-nonce')));
		wp_enqueue_script('Rich_Web_Tabs');
	}
	function Manage_Rich_Web_Tabs_Products()
	{
		require_once(RW_TABS_PLUGIN_DIR_URL . '/Rich-Web-Products.php');
	}
	add_action('admin_enqueue_scripts', 'Rich_Web_Tabs_Admin_Enqueue');
	function Rich_Web_Tabs_Admin_Enqueue(){
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui');
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script('jquery-ui-mouse');
		wp_enqueue_script('jquery-ui-resizable');	
		wp_register_script('RW_Tabs_Pickr',RW_TABS_PLUGIN_URL . 'Scripts/pickr.js');
		wp_register_script('RW_Tabs_fonticonpicker',RW_TABS_PLUGIN_URL . 'Scripts/jquery.fonticonpicker.min.js');
		wp_register_script('RW_Tabs_notifIt',RW_TABS_PLUGIN_URL . 'Scripts/notifIt.min.js');
		wp_enqueue_script('RW_Tabs_Pickr');
		wp_enqueue_script('RW_Tabs_fonticonpicker');
		wp_enqueue_script('RW_Tabs_notifIt');
		wp_enqueue_style('RW_Tabs_Pickr_CSS',RW_TABS_PLUGIN_URL . 'Style/monolith.min.css',[]);
		wp_enqueue_style('RW_Tabs_fonticonpicker_css',RW_TABS_PLUGIN_URL . 'Style/jquery.fonticonpicker.min.css',[]);
		wp_enqueue_style('RW_Tabs_fonticonpicker_grey_css',RW_TABS_PLUGIN_URL . 'Style/jquery.fonticonpicker.grey.min.css',[]);
		wp_enqueue_style('RW_Tabs_notifIt_Css',RW_TABS_PLUGIN_URL . 'Style/notifIt.min.css',[]);
		wp_register_style( 'rwtabs_fontawesome-css', RW_TABS_PLUGIN_URL . 'Style/richwebicons.css'); 
		wp_enqueue_style( 'rwtabs_fontawesome-css' );
	}

	if( isset($_GET['rw_tabs_preview']))
	{
		add_filter('the_content', 'RW_Tabs_Preview_Content');
		add_filter('template_include', 'RW_Tabs_Preview_Locate');
		function RW_Tabs_Preview_Content()
		{
			global $wpdb;
			$RWTabs_Manager_Table = $wpdb->prefix . "rich_web_tabs_manager_data";
			if (is_int(filter_var(sanitize_text_field($_GET['rw_tabs_preview']), FILTER_VALIDATE_INT)) && current_user_can('manage_options')) {
				$RW_Tabs_Build_ID = filter_var(sanitize_text_field($_GET['rw_tabs_preview']), FILTER_VALIDATE_INT);
				$RW_Tabs_Build_Check = $wpdb->get_row($wpdb->prepare("SELECT * FROM $RWTabs_Manager_Table WHERE id = %d", $RW_Tabs_Build_ID));
				if ($RW_Tabs_Build_Check) {
					ob_start();
						echo do_shortcode('[Rich_Web_Tabs id="'.sanitize_text_field($_GET['rw_tabs_preview']).'"]');
						$cont[] = ob_get_contents();
					ob_end_clean();
					return $cont[0];
				}else{
					$RW_Tabs_Notice = 'Tab is not defined.';
					return $RW_Tabs_Notice;
				}
			}else {
				$RW_Tabs_Notice = 'Security Error.';
				return $RW_Tabs_Notice;
			}
		}
		function RW_Tabs_Preview_Locate()
		{
			return locate_template(array('page.php', 'single.php', 'index.php'));
		}
	}


	function RW_Tabs_Media_Button() {
		$img = RW_TABS_PLUGIN_URL . 'Images/admin.png';
		$container_id = 'RWTabs';
		$title = 'Select Rich Web Tabs to insert into post';
		$button_text = 'RW Tabs';
		if(current_user_can('manage_options'))
		{
			echo sprintf('<a class="button thickbox" title="%s"	href="%s">
						<span class="wp-media-buttons-icon" style="background: url(%s); background-repeat: no-repeat; background-position: left bottom;background-size: 18px 18px;"></span>%s</a>',
						esc_attr($title),
						"#TB_inline&inlineId=".esc_attr($container_id)."&width=400&height=240",
						esc_url($img),
						esc_html($button_text)
						);
		}
	}
	
	add_filter( 'RW_Tabs_Old_Used_Filter', 'RW_Tabs_Old_Used_Function', 10 );
	function RW_Tabs_Old_Used_Function($RWTabs_Boolean){
		if ($RWTabs_Boolean == true) {
			$RW_Tabs_Old_Used_Tab_Acd = ['/RW_Tabs_Tab_Styles/rw_tabs_tab_14','/RW_Tabs_Tab_Styles/rw_tabs_tab_13','/RW_Tabs_Tab_Styles/rw_tabs_tab_15','/RW_Tabs_Tab_Styles/rw_tabs_tab_25','/RW_Tabs_Acd_Styles/rw_tabs_acd_12','/RW_Tabs_Acd_Styles/rw_tabs_acd_14','/RW_Tabs_Acd_Styles/rw_tabs_acd_15','/RW_Tabs_Acd_Styles/rw_tabs_acd_30','/RW_Tabs_Acd_Styles/rw_tabs_acd_31'];
			foreach ($RW_Tabs_Old_Used_Tab_Acd as $key => $value) {
				$filename = RW_TABS_PLUGIN_DIR_URL.'/Style'.$value.'.css.php';
				if (file_exists($filename)) {
					unlink($filename);
				}
			}
		}
	}

	add_action( 'wp_loaded', 'Manage_Rich_Web_Tabs_Pro' );
	function Manage_Rich_Web_Tabs_Pro(){
		$get;
		isset( $_GET['page'] ) ? $get = sanitize_text_field($_GET['page']) : $get = "";
		if ( $get != 'Rich_WebTabsPro' ) {
			return;
		}
		$url = 'https://rich-web.org/wp-tab-accordion/';
		wp_redirect( $url );
		exit;
	}	
	add_action( 'admin_footer', 'Manage_Rich_Web_Tabs_Pro_Link' );
	function Manage_Rich_Web_Tabs_Pro_Link() {
		?>
		<script type="text/javascript">
		  jQuery(document).ready(function () {
			jQuery('#RWTabs_Upgrade').parent().attr('target', '_blank');
		  });
		</script>
		<?php
	}
	function RW_Tabs_Media_Button_Content()
	{
		require_once(RW_TABS_PLUGIN_DIR_URL . '/Rich_Web_Tabs-Media.php');
	}
	add_action( 'media_buttons', 'RW_Tabs_Media_Button');
	add_action( 'admin_footer', 'RW_Tabs_Media_Button_Content');
	add_action('widgets_init', 'Rich_Web_Tabs_Reg_Widget');
	function Rich_Web_Tabs_Reg_Widget(){
	 	register_widget('Rich_Web_Tabs');
	}

	function RW_Tabs_Sanitize_Range_Num($Range_Number,$min,$max){
		if (ctype_digit($Range_Number) && (int) $Range_Number >= $min && (int) $Range_Number <= $max) {
			return true;
		}else {
			return false;
		}
	}
?>