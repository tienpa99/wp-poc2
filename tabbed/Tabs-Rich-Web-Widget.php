<?php
class Rich_Web_Tabs extends WP_Widget
{
	function __construct()
	{
		$params = array('name' => 'Rich-Web Tabs', 'description' => 'This is the widget of Rich-Web Tabs plugin');
		parent::__construct('Rich_Web_Tabs', '', $params);
	}
	function Tab($instance)
	{
		$defaults = array('Rich_Web_Tabs' => '');
		$instance = wp_parse_args((array)$instance, $defaults);
		$Rich_Web_Tabs = $instance['Rich_Web_Tabs'];
	?>
		<div>
			<p>
				Slider Title:
				<select name="<?php echo $this->get_field_name('Rich_Web_Tabs'); ?>" class="widefat">
					<?php
					global $wpdb;
					$RWTabs_Manager_Table  = $wpdb->prefix . "rich_web_tabs_manager_data";
					$Rich_Web_Tabs = $wpdb->get_results($wpdb->prepare("SELECT * FROM $RWTabs_Manager_Table WHERE id > %d", 0));
					foreach ($Rich_Web_Tabs as $Rich_Web_Tabs1) { ?>
						<option value="<?php echo esc_attr($Rich_Web_Tabs1->id); ?>">
						 <?php echo esc_html($Rich_Web_Tabs1->Tabs_Name); ?>
						</option>  
					<?php } ?>
				</select>
			</p>
		</div>
	<?php
	}
	function widget($args, $instance)
	{
		extract($args);
		$Rich_Web_Tabs = empty($instance['Rich_Web_Tabs']) ? '' : $instance['Rich_Web_Tabs'];
		global $wpdb;
		$RWTabs_Manager_Table = $wpdb->prefix . "rich_web_tabs_manager_data";
		$RWTabs_Themes_Table  = $wpdb->prefix . "rich_web_tabs_themes_data";
		$RW_Tabs_New_Tab = false;
		$Rich_Web_Tabs_Manager = $wpdb->get_results($wpdb->prepare("SELECT * FROM $RWTabs_Manager_Table WHERE id=%d", $Rich_Web_Tabs));
		$Rich_Web_Tabs_Fields = '';
		$Rich_Web_Tabs_Fields = json_decode($Rich_Web_Tabs_Manager[0]->Tabs_Fields);
		$RW_Tabs_Divide = explode("_", $Rich_Web_Tabs_Manager[0]->Tabs_TNS);
		$RW_Tabs_Style_Number = end($RW_Tabs_Divide);
		$Rich_Web_Tabs_Theme_Prop = json_decode($Rich_Web_Tabs_Manager[0]->Tabs_Props);
		$RW_Tabs_Theme_Check  = $wpdb->get_results($wpdb->prepare("SELECT `Rich_Web_Tabs_T_Ty` FROM $RWTabs_Themes_Table WHERE id = %d", $Rich_Web_Tabs_Manager[0]->Tabs_Theme));
		$RW_Tabs_Type = $RW_Tabs_Theme_Check[0]->Rich_Web_Tabs_T_Ty;
		$RWTabs_Theme_T_N_S = $Rich_Web_Tabs_Manager[0]->Tabs_TNS;
		$Rich_Web_Tabs = mt_rand();
		echo $before_widget;
	?>
		<input type="text" style="display: none;" class="id_rw_tab" value="<?php echo esc_html($Rich_Web_Tabs); ?>">
		<?php
		if ($RW_Tabs_Type == 'Rich_Tabs_1') {  ?>
			<?php 
			$array = [];
			for ($i = 0; $i < count($Rich_Web_Tabs_Fields); $i++) {
				$rand_id = mt_rand(100000, 999999);
				$array[$rand_id] = '';
			}
			$array_fliped_key =  array_keys($array); 
			$RW_Tabs_T_NavMode = $Rich_Web_Tabs_Theme_Prop->RWTabs_Type;
			$RW_Tabs_T_Align = $Rich_Web_Tabs_Theme_Prop->RWTabs_Align;
			?>
			<style type="text/css" id="RW_Tabs_Style">
				body{
					position: relative !important;
				}
				:root{
					--rw_tabs_section_align-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Align), FILTER_SANITIZE_STRING);?>;
					--rw_tabs_div_width-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_Width), FILTER_VALIDATE_INT); ?>%;
					--rw_tabs_menu_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_M_Bgc), FILTER_SANITIZE_STRING);?>;
					<?php 
					if ($RW_Tabs_T_NavMode == 'horizontal') { ?>
						--rw_tabs_menu_wrap-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_M_Wrap), FILTER_SANITIZE_STRING);?>;
						--rw_tabs_menu_pos-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_M_Pos), FILTER_SANITIZE_STRING);?>;
						--rw_tabs_menu_item_height-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_M_Height), FILTER_VALIDATE_INT); ?>px;
						--rw_tabs_menu_gap-<?php echo esc_html($Rich_Web_Tabs); ?>: <?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_M_Gap), FILTER_VALIDATE_INT); ?>px ;
					<?php } else { ?>
						--rw_tabs_v_menu_pos-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_M_Pos), FILTER_SANITIZE_STRING);?>;
						--rw_tabs_vertical_width-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_M_Width), FILTER_VALIDATE_INT); ?>px;
						--rw_tabs_menu_gap-<?php echo esc_html($Rich_Web_Tabs); ?>: '5px' ;
				    <?php } 
						for ($i = 0; $i < count($Rich_Web_Tabs_Fields); $i++) {
							if ($Rich_Web_Tabs_Fields[$i]->Tabs_Special_Color == "on" ) { ?>
								--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>_<?php echo esc_html($i + 1);?>: <?php echo filter_var(esc_html($Rich_Web_Tabs_Fields[$i]->Tabs_Special_Color_B), FILTER_SANITIZE_STRING);?>; 	
								--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>_<?php echo esc_html($i + 1);?>: <?php echo filter_var(esc_html($Rich_Web_Tabs_Fields[$i]->Tabs_Special_Color_H), FILTER_SANITIZE_STRING);?>;
								--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>_<?php echo esc_html($i + 1);?>: <?php echo filter_var(esc_html($Rich_Web_Tabs_Fields[$i]->Tabs_Special_Color_A), FILTER_SANITIZE_STRING);?>;
						<?php	} } ?>
					--rw_tabs_vertical_container-<?php echo esc_html($Rich_Web_Tabs); ?>: calc(100% - var(--rw_tabs_vertical_width-<?php echo esc_html($Rich_Web_Tabs); ?>));
					--rw_tabs_vertical_max_width-<?php echo esc_html($Rich_Web_Tabs); ?>: calc(var(--rw_tabs_vertical_width-<?php echo esc_html($Rich_Web_Tabs); ?>) - 10px);
					--rw_tabs_menu_bc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_M_BC), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Bgc_B), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Col_B), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Bgc_H), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-a-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Bgc_A), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Col_H), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Col_A), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item_text_max-<?php echo esc_html($Rich_Web_Tabs); ?>:calc( calc(2 * var(--rw_tabs_menu_item_height-<?php echo esc_html($Rich_Web_Tabs); ?>)) - 20px);

					--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_FontSize), FILTER_VALIDATE_INT); ?>px;
					--rw_tabs_text_font_family-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_FontFamily), FILTER_SANITIZE_STRING);?>;
					--rw_tabs_text_icon_size-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_IconSize), FILTER_VALIDATE_INT); ?>px;
					--rw_tabs_text_max_height-<?php echo esc_html($Rich_Web_Tabs); ?>:calc(var(--rw_tabs_menu_item_height-<?php echo esc_html($Rich_Web_Tabs); ?>) - 10px);
					<?php if ($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Type == 'color') { ?>
						--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>: <?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Col_F), FILTER_SANITIZE_STRING);?>;
					<?php } else if($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Type == 'gradient'){ ?>
						--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>: <?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Col_F), FILTER_SANITIZE_STRING);?>;
						--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Col_S), FILTER_SANITIZE_STRING);?>;
				    <?php } ?>
					--rw_tabs_content_br-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_C_BR), FILTER_VALIDATE_INT); ?>px;
					--rw_tabs_content_bw-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_C_BW), FILTER_VALIDATE_INT); ?>px;
					--rw_tabs_content_bc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RW_Tabs_C_BC), FILTER_SANITIZE_STRING);?>;
				}

			/* For Horizontal Classes and CSS */
			section#RW_Tabs_Section_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>{
				width: 100% !important;
				display: -ms-flexbox;
   				display: -webkit-flex;
   				display: flex;
				-webkit-flex-direction: row;
    			-ms-flex-direction: row;
    			flex-direction: row;
    			-webkit-flex-wrap: nowrap;
    			-ms-flex-wrap: nowrap;
    			flex-wrap: nowrap;
				-webkit-justify-content: var(--rw_tabs_section_align-<?php echo esc_html($Rich_Web_Tabs); ?>);
    			-ms-flex-pack: var(--rw_tabs_section_align-<?php echo esc_html($Rich_Web_Tabs); ?>);
    			justify-content: var(--rw_tabs_section_align-<?php echo esc_html($Rich_Web_Tabs); ?>);
			} 
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>{
				display: -ms-flexbox;
   				display: -webkit-flex;
   				display: flex;
			}
			ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?>{
				display: -ms-flexbox;
   				display: -webkit-flex;
   				display: flex;
			}
			<?php require( 'Style/RW_Tabs_Tab_Styles/rw_tabs_tab_'.$RW_Tabs_Style_Number.'.css.php' );  ?>
			@media screen and (min-width: 554px) {
			/* Horizontal  */
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"]{
				-webkit-flex-direction: column;
    			-ms-flex-direction: column;
    			flex-direction: column;
			}
			/* Vertical */
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"]{
				-webkit-flex-direction: var(--rw_tabs_v_menu_pos-<?php echo esc_html($Rich_Web_Tabs); ?>);
    			-ms-flex-direction: var(--rw_tabs_v_menu_pos-<?php echo esc_html($Rich_Web_Tabs); ?>);
    			flex-direction: var(--rw_tabs_v_menu_pos-<?php echo esc_html($Rich_Web_Tabs); ?>);
			}
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>{
				width: var(--rw_tabs_div_width-<?php echo esc_html($Rich_Web_Tabs); ?>);
			}
			ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?>{
				background-color:var(--rw_tabs_menu_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) ;
			}
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] > ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?>{
				-webkit-flex-direction: column;
    			-ms-flex-direction: column;
    			flex-direction: column;
				width: var(--rw_tabs_vertical_width-<?php echo esc_html($Rich_Web_Tabs); ?>);
			}
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] > div#Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>{
				width: var(--rw_tabs_vertical_container-<?php echo esc_html($Rich_Web_Tabs); ?>);
			}
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] > ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?>{
				-webkit-flex-direction: row;
    			-ms-flex-direction: row;
    			flex-direction: row;
				-webkit-justify-content: var(--rw_tabs_menu_pos-<?php echo esc_html($Rich_Web_Tabs); ?>);
				-ms-flex-pack: var(--rw_tabs_menu_pos-<?php echo esc_html($Rich_Web_Tabs); ?>);
				justify-content: var(--rw_tabs_menu_pos-<?php echo esc_html($Rich_Web_Tabs); ?>);
				gap:var(--rw_tabs_menu_gap-<?php echo esc_html($Rich_Web_Tabs); ?>);
				-webkit-flex-wrap: var(--rw_tabs_menu_wrap-<?php echo esc_html($Rich_Web_Tabs); ?>);
   				-ms-flex-wrap: var(--rw_tabs_menu_wrap-<?php echo esc_html($Rich_Web_Tabs); ?>);
   				flex-wrap: var(--rw_tabs_menu_wrap-<?php echo esc_html($Rich_Web_Tabs); ?>);
			}
			ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > li.RW_Tabs_T_Item-H > span > i{
				max-height: var(--rw_tabs_text_max_height-<?php echo esc_html($Rich_Web_Tabs); ?>);
    			overflow: hidden;
				padding: 7px;
			}
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] > ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > li:not(.RW_Tabs_Img_Opt) > span > i{
				max-width: var(--rw_tabs_menu_item_text_max-<?php echo esc_html($Rich_Web_Tabs); ?>);
			}
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] > ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > li > span > i{
				max-width: var(--rw_tabs_vertical_max_width-<?php echo esc_html($Rich_Web_Tabs); ?>);
			}
			ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H{
					align-items: center;
					text-align: center;
					display: inline-flex;
    				justify-content: center;
    				vertical-align: middle;
					position: relative;
					overflow: hidden;
			}
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="horizontal"] > ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H{
				height: var(--rw_tabs_menu_item_height-<?php echo esc_html($Rich_Web_Tabs); ?>);	
			}
			div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-desctop="vertical"] > ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H{
				width: 100% ;
			}
			<?php 
			$RW_Tabs_Data_Sort = 1; 
			foreach ($array as $key => $value) :  ?>
			ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H[data-id="<?php echo esc_html($key);?>"]{
				background: var(--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
				color: var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>_<?php echo esc_html($RW_Tabs_Data_Sort);?>,var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>));
			} 
			ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H[data-id="<?php echo esc_html($key);?>"]:hover{
				background: var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
				color: var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>_<?php echo esc_html($RW_Tabs_Data_Sort);?>,var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>));
			}
			ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H[data-id="<?php echo esc_html($key);?>"].active{
				background: var(--rw_tabs_menu_item-a-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
				color: var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>_<?php echo esc_html($RW_Tabs_Data_Sort);?>,var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>));
			}
			<?php $RW_Tabs_Data_Sort = $RW_Tabs_Data_Sort + 1 ;
			endforeach;?>
			}
			ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > li.RW_Tabs_Img_Opt {
				background-size: 100% 100% !important;
    			background-repeat: no-repeat;
			}
			ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H > span > i > span {
				font-size: var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>);
				font-family: var(--rw_tabs_text_font_family-<?php echo esc_html($Rich_Web_Tabs); ?>);
				word-break: break-word;
			}
			ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H > span > i {
				font-size: var(--rw_tabs_text_icon_size-<?php echo esc_html($Rich_Web_Tabs); ?>);
			}
			#Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-cont="empty"]{
				display:none !important;
			}
			#Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-cont="full"]{
				display:block !important;
			}
			.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> li div.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?> 
			{
				width: 100%;
			}
			.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> li div.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="color"] 
			{
				background: var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
			}
			.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> li div.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="transparent"] 
			{
				background: transparent !important;
			}
			.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> li div.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="gradient"] 
			{
				background: -webkit-linear-gradient(var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>)) !important;
				background: -o-linear-gradient(var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>)) !important;
				background: -moz-linear-gradient(var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>)) !important;
				background: linear-gradient(var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>)) !important;
			}
			.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> div.Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>
			{
				border-radius:var(--rw_tabs_content_br-<?php echo esc_html($Rich_Web_Tabs); ?>) ; 
				-webkit-border-radius: var(--rw_tabs_content_br-<?php echo esc_html($Rich_Web_Tabs); ?>) ;
				-moz-border-radius: var(--rw_tabs_content_br-<?php echo esc_html($Rich_Web_Tabs); ?>) ;  
				border: var(--rw_tabs_content_bw-<?php echo esc_html($Rich_Web_Tabs); ?>) solid var(--rw_tabs_content_bc-<?php echo esc_html($Rich_Web_Tabs); ?>); 
			}
			.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> div.Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="color"]
			{
				background: var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
			}				
			.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> div.Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="transparent"]
			{
				background: transparent;
			}				
			.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?> div.Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="gradient"]
			{
			    background: -webkit-linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
			    background: -o-linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
			    background: -moz-linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
			    background: linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
			}
			@media screen and (max-width: 553px) {
				div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>{
					-webkit-flex-direction: column;
    				-ms-flex-direction: column;
    				flex-direction: column;
				}	
				div#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>{
					width: 100%;
				}
				ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?>{
					-webkit-flex-direction: column;
    				-ms-flex-direction: column;
    				flex-direction: column;
				}
				ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > li.RW_Tabs_T_Item-H > span > i{
    				overflow: hidden;
				}
				ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > li.RW_Tabs_Img_Opt {
					background-size: 100% 100% !important;
    				background-repeat: no-repeat !important;
				}
				ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > li > .RW_Tabs_Menu_Title{
					max-width: calc(100% - 25px);
					padding:15px 0px;
				}
				ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H{
						width:100%;
						align-items: center;
						text-align: center;
						display: inline-flex;
    					justify-content: center;
    					vertical-align: middle;
						position: relative;
						overflow: hidden;
				}
			div.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="color"]
			{
				background: var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
			}				
			div.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="transparent"]
			{
				background: transparent;
			}				
			div.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="gradient"]
			{
			    background: -webkit-linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
			    background: -o-linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
			    background: -moz-linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
			    background: linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
			}
					<?php 
					$RW_Tabs_Data_Sort = 1; 
					foreach ($array as $key => $value) :  ?>
					ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H[data-id="<?php echo esc_html($key);?>"]{
						background: var(--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
						color: var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>_<?php echo esc_html($RW_Tabs_Data_Sort);?>,var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>));
					} 
					ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H[data-id="<?php echo esc_html($key);?>"]:hover{
						background: var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
						color: var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>_<?php echo esc_html($RW_Tabs_Data_Sort);?>,var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>));
					}
					ul#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > .RW_Tabs_T_Item-H[data-id="<?php echo esc_html($key);?>"].active{
						background: var(--rw_tabs_menu_item-a-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
						color: var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>_<?php echo esc_html($RW_Tabs_Data_Sort);?>,var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>));
					}
					<?php $RW_Tabs_Data_Sort = $RW_Tabs_Data_Sort + 1 ;
					endforeach;?>
				}
			</style>
			<section id="RW_Tabs_Section_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>">
				<div class="Rich_Web_Tabs_Tab Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>" id="RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>" data-rw-theme="<?php echo  esc_attr($RWTabs_Theme_T_N_S); ?>" data-rw-desctop="<?php echo esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_Type); ?>" >
				<script type="text/javascript">
					jQuery('.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>').css('display', 'none');
				</script>
				<!-- - - - - - Tab navigation - - - - - - -->
				<ul id="RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?>" class="<?php echo  esc_attr($RWTabs_Theme_T_N_S); ?> Rich_Web_Tabs_tt_tabs Rich_Web_Tabs_tt_tabs<?php echo esc_html($Rich_Web_Tabs); ?>" data-rw-mobile="<?php echo esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_Mobile); ?>" >
					<?php for ($i = 0; $i < count($Rich_Web_Tabs_Fields); $i++) : ?>
							<li  class="RW_Tabs_T_Item-H RW_Tabs_Non_Img_Opt <?php if ($i == 0) { echo 'active'; } ?> " data-id="<?php echo  esc_attr($array_fliped_key[$i]); ?>" data-sort="<?php echo  esc_attr($i + 1); ?>">
									<span class="rich_web_tab_li_span RW_Tabs_Menu_Title">
										<i class='rich_web rich_web-<?php echo  esc_attr($Rich_Web_Tabs_Fields[$i]->Tabs_Icon); ?>'>
												<span> <?php echo  html_entity_decode(esc_html($Rich_Web_Tabs_Fields[$i]->Tabs_Subtitle)); ?></span>
										</i>
									</span>
								</li>
					<?php endfor;  ?>
				</ul>
				<!-- - - - - Tab Content - - - - - -->
				<div id="Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>" class="Rich_Web_Tabs_tt_container Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>" data-style="<?php echo esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Type); ?>" >
					<?php for ($i = 0; $i < count($Rich_Web_Tabs_Fields); $i++) { ?>
							<div data-style="<?php echo esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Type); ?>" data-parent="<?php echo  esc_attr($array_fliped_key[$i]); ?>" class="Rich_Web_Tabs_tt_tab Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?> <?php if ($i == 0) { echo 'active'; } ?>"> <?php echo  do_shortcode(html_entity_decode(esc_html($Rich_Web_Tabs_Fields[$i]->Tabs_Content))); ?></div>
					<?php }  ?>
				</div><!-- .container<?php echo esc_html($Rich_Web_Tabs); ?> -->
				</div><!-- #myTab -->
			</section>	
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery('.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>').turbotabs<?php echo esc_html($Rich_Web_Tabs); ?>({
						mode: '<?php echo  esc_js($Rich_Web_Tabs_Theme_Prop->RWTabs_Type); ?>',
						animation: '<?php echo  esc_js($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Anim); ?>',
						width: '<?php echo  esc_js($Rich_Web_Tabs_Theme_Prop->RWTabs_Width); ?>%',
					});
					jQuery('.Rich_Web_Tabs_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>').css('display', '');
					jQuery('.Rich_Web_Tabs_tt_tab').css('display', '');
				});
			</script>
		<script>
			var timer<?php echo esc_html($Rich_Web_Tabs); ?> = 340;
			var animation = '',
			animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = '',
			animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = '';
			function return_animation<?php echo esc_html($Rich_Web_Tabs); ?>(animation) {
						if ('Scale' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'zoomIn';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'zoomOut';
						} else if ('FadeUp' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeInUp';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeOutDown';
						} else if ('FadeDown' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeInDown';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeOutUp';
						} else if ('FadeLeft' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeInLeft';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeOutLeft';
						} else if ('FadeRight' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeInRight';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeOutRight';
						} else if ('SlideUp' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'slideInUp';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'slideOutUp';
							timer<?php echo esc_html($Rich_Web_Tabs); ?> = 80;
						} else if ('SlideDown' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'slideInDown';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'slideOutDown';
							timer<?php echo esc_html($Rich_Web_Tabs); ?> = 80;
						} else if ('SlideLeft' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'slideInLeft';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'slideOutLeft';
							timer<?php echo esc_html($Rich_Web_Tabs); ?> = 80;
						} else if ('SlideRight' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'slideInRight';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'slideOutRight';
							timer<?php echo esc_html($Rich_Web_Tabs); ?> = 80;
						} else if ('ScrollDown' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeInUp';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeOutUp';
						} else if ('ScrollUp' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeInDown';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeOutDown';
						} else if ('ScrollRight' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeInLeft';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeOutRight';
						} else if ('ScrollLeft' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeInRight';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'fadeOutLeft';
						} else if ('Bounce' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'bounceIn';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'bounceOut';
						} else if ('BounceLeft' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'bounceInLeft';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'bounceOutLeft';
						} else if ('BounceRight' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'bounceInRight';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'bounceOutRight';
						} else if ('BounceDown' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'bounceInDown';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'bounceOutDown';
						} else if ('BounceUp' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'bounceInUp';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'bounceOutUp';
						} else if ('HorizontalFlip' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'flipInX';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'flipOutX';
						} else if ('VerticalFlip' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'flipInY';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'flipOutY';
						} else if ('RotateDownLeft' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'rotateInDownLeft';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'rotateOutDownLeft';
						} else if ('RotateDownRight' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'rotateInDownRight';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'rotateOutDownRight';
						} else if ('RotateUpLeft' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'rotateInUpLeft';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'rotateOutUpLeft';
						} else if ('RotateUpRight' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'rotateInUpRight';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'rotateOutUpRight';
						} else if ('TopZoom' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'zoomInUp';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'zoomOutUp';
						} else if ('BottomZoom' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'zoomInDown';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'zoomOutDown';
						} else if ('LeftZoom' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'zoomInLeft';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'zoomOutLeft';
						} else if ('RightZoom' === animation) {
							animationIn<?php echo esc_html($Rich_Web_Tabs); ?> = 'zoomInRight';
							animationOut<?php echo esc_html($Rich_Web_Tabs); ?> = 'zoomOutRight';
						}
			}
			(function($) {
				$.fn.turbotabs<?php echo esc_html($Rich_Web_Tabs); ?> = function(options) {
					var settings<?php echo esc_html($Rich_Web_Tabs); ?> = $.extend({
						mode: 'horizontal',
						width: '100%',
						animation: 'Scale',
						deinitialize:'false',
						cb_after_animation: function() {},
					}, options);
					if (settings<?php echo esc_html($Rich_Web_Tabs); ?>.deinitialize == 'true') {  return false ; }
					var random<?php echo esc_html($Rich_Web_Tabs); ?> = 'tab<?php echo esc_html($Rich_Web_Tabs); ?>-' + Math.floor(Math.random() * 100);  
					animation = settings<?php echo esc_html($Rich_Web_Tabs); ?>.animation; //Animatiayi type
					var myRW_Tabs_Window<?php echo esc_js($Rich_Web_Tabs);?> = '';
					var RW_Tabs_Data_RW_Theme = $('#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>').attr('data-rw-theme');
					//Return Animations
					return_animation<?php echo esc_html($Rich_Web_Tabs); ?>(animation);
					//Click Functioni jamanak gorcoxutyunnery
					$(document).on("click", "#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > li.RW_Tabs_T_Item-H" , function() {
						var ClickedElement = $(this);
						if (parseInt($(window).outerWidth())  <= 553) {
							if ($('#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?>').attr('data-rw-mobile') == 'accordion') {
								if (!ClickedElement.hasClass("active")) {
									$("#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?> > li.active").removeClass("active");
									$('.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>.active').slideUp(500);
									setTimeout(() => {
										$('.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>.active').removeClass("active");
										ClickedElement.next(".Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>").addClass("active").slideDown(500);
									}, 100);
									ClickedElement.addClass('active');
								}else {
									ClickedElement.removeClass('active');
									$('.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>.active').slideUp(500);
									setTimeout(() => {
										$('.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>.active').removeClass("active")
									}, 100);
								}
							}else{
								RW_Tabs_MyClick_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>(ClickedElement,true);
							}
						}else {
							RW_Tabs_MyClick_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>(ClickedElement,false);
						}
					});
					function RW_Tabs_MyClick_Tab_<?php echo esc_html($Rich_Web_Tabs); ?>(ClickedElement,RW_Tabs_RMode) {
						if (!$(ClickedElement).hasClass("active")) {
							if ('random<?php echo esc_html($Rich_Web_Tabs); ?>' === animation) {
									var animations_array<?php echo esc_html($Rich_Web_Tabs); ?> = Array("Scale", "Bounce", "FadeUp", "FadeDown", "FadeLeft", "FadeRight", "SlideUp", "SlideDown", "SlideLeft", "SlideRight", "ScrollUp", "ScrollDown", "ScrollLeft", "ScrollRight", "BounceUp", "BounceDown", "BounceLeft", "BounceRight", "HorizontalFlip", "VerticalFlip", "RotateDownLeft", "RotateDownRight", "RotateUpLeft", "RotateUpRight", "TopZoom", "BottomZoom", "LeftZoom", "RightZoom");
									var rand_animation<?php echo esc_html($Rich_Web_Tabs); ?> = animations_array<?php echo esc_html($Rich_Web_Tabs); ?>[Math.floor(Math.random() * animations_array<?php echo esc_html($Rich_Web_Tabs); ?>.length)];
									return_animation<?php echo esc_html($Rich_Web_Tabs); ?>(rand_animation<?php echo esc_html($Rich_Web_Tabs); ?>);
							}
							var index<?php echo esc_html($Rich_Web_Tabs); ?> = jQuery(ClickedElement).index();
							var indexod<?php echo esc_html($Rich_Web_Tabs); ?> = jQuery(ClickedElement).attr('data-id');
							$('#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?>').children("li.active").removeClass("active");
							$(ClickedElement).addClass("active");
							$("div.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>.active").attr('class', 'Rich_Web_Tabs_tt_tab Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?> Rich_Web_Tabs_animated ' + animationOut<?php echo esc_html($Rich_Web_Tabs); ?>);
							$("div.Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?>[data-parent='"+indexod<?php echo esc_html($Rich_Web_Tabs); ?>+"']").attr('class', 'Rich_Web_Tabs_tt_tab Rich_Web_Tabs_tt_tab<?php echo esc_html($Rich_Web_Tabs); ?> active Rich_Web_Tabs_animated ' + animationIn<?php echo esc_html($Rich_Web_Tabs); ?>);
							if (RW_Tabs_RMode == true) {
								document.getElementById('Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>').scrollIntoView({behavior: "smooth", block: "end", inline: 'center'});
							}
						} 
						settings<?php echo esc_html($Rich_Web_Tabs); ?>.cb_after_animation();
					}
					function RW_Tabs_Fill_Container() {
						if ($('#Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>').attr('data-rw-cont') == 'empty') {
								jQuery('.Rich_Web_Tabs_tt_tab<?php echo esc_js($Rich_Web_Tabs);?>').each(function () {
									jQuery(this).appendTo($('#Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>'));
								})
								$('#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>').find('.Rich_Web_Tabs_tt_tabs').addClass(`${$('#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>').attr('data-rw-theme')}`)
							}
							$('#Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>').attr('data-rw-cont','full');
							$('.Rich_Web_Tabs_tt_tab').css('display', '');
					}
					$(window).on("resize", function() {
						myRW_Tabs_Window<?php echo esc_js($Rich_Web_Tabs);?> = parseInt($(this).outerWidth());
						if (myRW_Tabs_Window<?php echo esc_js($Rich_Web_Tabs);?> > 553) {
							RW_Tabs_Fill_Container();
						} else if(myRW_Tabs_Window<?php echo esc_js($Rich_Web_Tabs);?> <= 553) {
							if ($('#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?>').attr('data-rw-mobile') == "accordion") {
							  $('#RW_Tabs_T_Menu-H-<?php echo esc_js($Rich_Web_Tabs);?> > .RW_Tabs_T_Item-H').each(function(){
							  	$('.Rich_Web_Tabs_tt_tab<?php echo esc_js($Rich_Web_Tabs);?>[data-parent="'+$(this).attr('data-id')+'"]').removeClass('Rich_Web_Tabs_animated').insertAfter(jQuery(this));
							  });
							$('#Rich_Web_Tabs_tt_container<?php echo esc_html($Rich_Web_Tabs); ?>').attr('data-rw-cont','empty');
							$('#RW_Tabs_T_Menu-H-<?php echo esc_html($Rich_Web_Tabs); ?>').removeClass(`${$('#RW_Tabs_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>').attr('data-rw-theme')}`)
							} else {
								RW_Tabs_Fill_Container();
							}
						}
					})
					$(window).trigger('resize');
					return this;
				}; 
			}(jQuery));
		</script>
				<?php 
		}else if($RW_Tabs_Type == 'Rich_Tabs_2'){ ?>
		  <style id="RW_Tabs_Style">
			  	:root{
					/* Menu Sizes */
					--rw_tabs_section_align-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Align), FILTER_SANITIZE_STRING);?>;
					--rw_tabs_div_width-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_Width), FILTER_VALIDATE_INT); ?>%;
					--rw_tabs_menu_gap-<?php echo esc_html($Rich_Web_Tabs); ?>: <?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_M_Gap), FILTER_VALIDATE_INT); ?>px ;
					--rw_tabs_menu_content_gap-<?php echo esc_html($Rich_Web_Tabs); ?>: <?php echo filter_var(esc_attr($Rich_Web_Tabs_Theme_Prop->RW_Tabs_M_C_Gap), FILTER_VALIDATE_INT); ?>px ;


					/* Acd Menu BGC & Colors */
					--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Bgc_B), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Col_B), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Bgc_H), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-a-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Bgc_A), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Col_H), FILTER_SANITIZE_STRING);?>;          
					--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Col_A), FILTER_SANITIZE_STRING);?>;   


					/* h3 borders */
					--rw_tabs_menu_item-br-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_BR),FILTER_VALIDATE_INT); ?>px;
					--rw_tabs_menu_item-bw-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_BW),FILTER_VALIDATE_INT); ?>px;
					--rw_tabs_menu_item-bs-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_BS),FILTER_SANITIZE_STRING); ?>;
					--rw_tabs_menu_item-bc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_BC_B),FILTER_SANITIZE_STRING); ?>;
					--rw_tabs_menu_item-bc_h-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_BC_H),FILTER_SANITIZE_STRING); ?>;
					--rw_tabs_menu_item-bc_a-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_BC_A),FILTER_SANITIZE_STRING); ?>;
					/* h3 box shadows */
					--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Box_B),FILTER_SANITIZE_STRING); ?>;
					--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Box_H),FILTER_SANITIZE_STRING); ?>;
					--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Box_A),FILTER_SANITIZE_STRING); ?>;
					/* Right Icon  */
					--rw_tabs_menu_item-ri_c-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_RI_C_B),FILTER_SANITIZE_STRING); ?>;
					--rw_tabs_menu_item-ri_c_h-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_RI_C_H),FILTER_SANITIZE_STRING); ?>;
					--rw_tabs_menu_item-ri_c_a-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_RI_C_A),FILTER_SANITIZE_STRING); ?>;
                    --rw_tabs_menu_item-ri_s-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_RI_S),FILTER_VALIDATE_INT); ?>px;
					/* Left Icon  */
                    --rw_tabs_menu_item-li_c-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_LI_C_B),FILTER_SANITIZE_STRING); ?>;
					--rw_tabs_menu_item-li_c_h-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_LI_C_H),FILTER_SANITIZE_STRING); ?>;
					--rw_tabs_menu_item-li_c_a-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_LI_C_A),FILTER_SANITIZE_STRING); ?>;
                    --rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_LI_S),FILTER_VALIDATE_INT); ?>px;
					/* Text Sizes */
					--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_FontSize),FILTER_VALIDATE_INT); ?>px;
					--rw_tabs_text_font_family-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_FontFamily),FILTER_SANITIZE_STRING); ?>;
					/* Content Styles */
					<?php if ($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Type == 'color') { ?>
						--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>: <?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Col_F), FILTER_SANITIZE_STRING);?>;
					<?php } else if($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Type == 'gradient' || $Rich_Web_Tabs_Theme_Prop->RWTabs_C_Type == 'gradient-top'){ ?>
						--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>: <?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Col_F), FILTER_SANITIZE_STRING);?>;
						--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Col_S), FILTER_SANITIZE_STRING);?>;
				    <?php } ?>

					--rw_tabs_content_br-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_C_BR),FILTER_VALIDATE_INT);?>px;
					--rw_tabs_content_bw-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RWTabs_C_BW),FILTER_VALIDATE_INT);?>px;
					--rw_tabs_content_bc-<?php echo esc_html($Rich_Web_Tabs); ?>:<?php echo  filter_var(esc_html($Rich_Web_Tabs_Theme_Prop->RW_Tabs_C_BC),FILTER_SANITIZE_STRING);?>;
				}

				section#RW_Tabs_Section_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>{
					width: 100% !important;
					display: -ms-flexbox;
   					display: -webkit-flex;
   					display: flex;
					-webkit-flex-direction: row;
    				-ms-flex-direction: row;
    				flex-direction: row;
    				-webkit-flex-wrap: nowrap;
    				-ms-flex-wrap: nowrap;
    				flex-wrap: nowrap;
					-webkit-justify-content: var(--rw_tabs_section_align-<?php echo esc_html($Rich_Web_Tabs); ?>);
    				-ms-flex-pack: var(--rw_tabs_section_align-<?php echo esc_html($Rich_Web_Tabs); ?>);
    				justify-content: var(--rw_tabs_section_align-<?php echo esc_html($Rich_Web_Tabs); ?>);
				} 
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?> {
					height:0;
					overflow:hidden;
					margin-top:20px;
					margin-bottom:20px;
					width: var(--rw_tabs_div_width-<?php echo esc_html($Rich_Web_Tabs); ?>);
					position: relative;
					z-index: 1;
				}
				.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>  {
					position: relative;
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_1"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					background: linear-gradient(to right, var(--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) 50%, var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) 50%);
					background-size: 200% 100%;
					background-position:left bottom;
					transition:all 1s ease;
					-webkit-transition:all 1s ease;
					-moz-transition:all 1s ease;
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_2"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					background: linear-gradient(to left, var(--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) 50%, var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) 50%);
					background-size: 200% 100%;
					background-position:right bottom;
					transition:all 1s ease;
					-webkit-transition:all 1s ease;
					-moz-transition:all 1s ease;
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_3"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					background-size: 100% 200%;
					background-image: linear-gradient(to bottom, var(--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) 50%, var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) 50%);
					transition: background-position 1s;
					-webkit-transition: background-position 1s;
					-moz-transition: background-position 1s;
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_4"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					background-size: 100% 200%;
					background-image: linear-gradient(to top, var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) 50%, var(--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) 50%);
					transition: background-position 1s;
					-webkit-transition: background-position 1s;
					-moz-transition: background-position 1s;
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_5"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					background: var(--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
					-webkit-transition: all ease 0.8s;
					-moz-transition: all ease 0.8s;
					transition: all ease 0.8s;	
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_6"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					background: var(--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_none"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					background: var(--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
				}
				.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					color: var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?> )!important;
					border-radius: var(--rw_tabs_menu_item-br-<?php echo esc_html($Rich_Web_Tabs); ?>);
					padding: 16px 12px;
					cursor: pointer;
					margin-top: 0 !important;
					margin-bottom: var(--rw_tabs_menu_gap-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
					padding-right: 40px;
					position: relative;
					transition: all 0.4s;
					-webkit-transition: all 0.4s;
					-moz-transition: all 0.4s;
					border:  var(--rw_tabs_menu_item-bw-<?php echo esc_html($Rich_Web_Tabs); ?>) var(--rw_tabs_menu_item-bs-<?php echo esc_html($Rich_Web_Tabs); ?>) var(--rw_tabs_menu_item-bc-<?php echo esc_html($Rich_Web_Tabs); ?>);
					line-height: 0 !important;
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_1"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					box-shadow: 4px -4px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_2"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					box-shadow: 5px 5px 3px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_3"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					box-shadow: 2px 2px white, 4px 4px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_4"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					box-shadow:  inset 0 0 18px 7px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_5"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					box-shadow:  inset 8px 8px 18px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_6"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					box-shadow:5px 5px 5px #a5aaab, 9px 9px 5px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_9"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					box-shadow: 0 8px 6px -6px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
				}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_15"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
					box-shadow: 0 0 10px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
				}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_7"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
						box-shadow: none;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_7"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:before, .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_15"] > h3:after {
					  position:absolute;
					  content:"";
					  bottom:25px;
					  left:15px;
					   height: 5px;
					  top:45%;
					  width:45%;
					  background:none;
					  z-index: -1;
					  box-shadow: 0 22px 14px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
					  -webkit-transform: rotate(-5deg);
					  -moz-transform: rotate(-5deg);
					  transform: rotate(-5deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_7"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:after{
					  -webkit-transform: rotate(5deg);
					  -moz-transform: rotate(5deg);
					  transform: rotate(5deg);
					  right: 15px;
					  left: auto;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_7"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:before, .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_15"] > h3:hover:after {
						  position:absolute;
						  content:"";
						  bottom:25px;
						  left:15px;
						   height: 5px;
						  top:45%;
						  width:45%;
						  background:none;
						  z-index: -1;
						  box-shadow: 0 22px 14px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  -webkit-transform: rotate(-5deg);
						  -moz-transform: rotate(-5deg);
						  transform: rotate(-5deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_7"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:after{
					  -webkit-transform: rotate(5deg);
					  -moz-transform: rotate(5deg);
					  transform: rotate(5deg);
					  right: 15px;
					  left: auto;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_7"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:before, .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_15"] > h3.active:after {
						  position:absolute;
						  content:"";
						  left:15px;
						  bottom: calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 5px);
						  bottom: -webkit-calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 5px);
						  top:45%;
						  width:45%;
						  height: 5px;
						  background:none;
						  z-index: -1;
						  box-shadow: 0 22px 14px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  -webkit-transform: rotate(-5deg);
						  -moz-transform: rotate(-5deg);
						  transform: rotate(-5deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_7"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:after{
					  -webkit-transform: rotate(5deg);
					  -moz-transform: rotate(5deg);
					  transform: rotate(5deg);
					  right: 15px;
					  left: auto;
					}
					/* Bsh 7 end */
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:before, .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_8"] > h3:after {
					  position:absolute;
					  content:"";
					  top:36px;
					  bottom: 143px;
					   height: 5px;
					  width:50%;
					  right: 91px !important;
					  z-index:-1;
					  -webkit-transform: rotate(-5deg);
					  -moz-transform: rotate(-5deg);
					  transform: rotate(-5deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:after {
						bottom: calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 22px) !important;
						bottom: -webkit-calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 22px) !important;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:before{
					  box-shadow:85px -11px 13px 2px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:after{
					  -webkit-transform: rotate(5deg);
					  -moz-transform: rotate(5deg);
					  transform: rotate(5deg);
					  bottom: 11px;
					  top: auto;
					  box-shadow:84px -14px 13px 2px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:before {
						 box-shadow:85px -11px 13px 2px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  height: 5px;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:after {
						 box-shadow:84px -14px 13px 2px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  height: 5px;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:before {
						 box-shadow:85px -11px 13px 2px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  height: 5px;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:after {
						box-shadow:84px -14px 13px 2px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
						 height: 5px;
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_10"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 {
						box-shadow: none;
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_10"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:before,.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_10"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:after {
					  position:absolute;
					  content:"";
					  bottom: 30px;
					  left: 8px;
					  top: 35%;
					  width: 45%;
					  background:none;
					  z-index: -1;
					  box-shadow: 0 22px 10px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
					  -webkit-transform: rotate(-3deg);
					  -moz-transform: rotate(-3deg);
					  transform: rotate(-3deg);
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_10"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:after{
					  -webkit-transform: rotate(3deg);
					  -moz-transform: rotate(3deg);
					  transform: rotate(3deg);
					  right: 8px;
					  left: auto;
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_10"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:before,.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_10"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:after {
						  position:absolute;
						  content:"";
						  bottom: 30px;
						  left: 8px;
						  top: 35%;
						  width: 45%;
						  background:none;
						  z-index: -1;
						  box-shadow: 0 22px 10px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  -webkit-transform: rotate(-3deg);
						  -moz-transform: rotate(-3deg);
						  transform: rotate(-3deg);
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_10"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:after{
					  -webkit-transform: rotate(3deg);
					  -moz-transform: rotate(3deg);
					  transform: rotate(3deg);
					  right: 8px;
					  left: auto;
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_10"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:before,.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_10"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:after {
						  position:absolute;
						  content:"";
						  bottom: calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 0px);
						  bottom: -webkit-calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 0px);
						  left: 8px;
						  top: 0;
						  width: 45%;
						  background:none;
						  z-index: -1;
						  box-shadow: 0 25px 10px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  -webkit-transform: rotate(-3deg);
						  -moz-transform: rotate(-3deg);
						  transform: rotate(-3deg);
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_10"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:after{
					  -webkit-transform: rotate(3deg);
					  -moz-transform: rotate(3deg);
					  transform: rotate(3deg);
					  right: 8px;
					  left: auto;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_11"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:before {
					  position:absolute;
					  content:"";
					  bottom: 25px;
					  left: 5px;
					   height: 5px;
					  width: 48%;
					  background:none;
					  z-index: -1;
					  box-shadow: 0px 20px 14px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
					  -webkit-transform: rotate(-3deg);
					  -moz-transform: rotate(-3deg);
					  transform: rotate(-3deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_11"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:before {
						  position:absolute;
						  content:"";
						  bottom: 25px;
					      left: 5px;
					       height: 5px;
					      width: 48%;
						  background:none;
						  z-index: -1;
						  box-shadow: 0px 20px 14px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  -webkit-transform: rotate(-3deg);
						  -moz-transform: rotate(-3deg);
						  transform: rotate(-3deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_11"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:before {
						  position:absolute;
						  content:"";
						  bottom: calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 7px);
						  bottom: -webkit-calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 7px);
					      left: 5px;
					       height: 5px;
					      width: 48%;
						  background:none;
						  z-index: -1;
						  box-shadow: 0px 20px 14px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  -webkit-transform: rotate(-3deg);
						  -moz-transform: rotate(-3deg);
						  transform: rotate(-3deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_12"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:before {
					  position:absolute;
					  content:"";
					  bottom: 25px;
					  right: 5px;
					   height: 5px;
					  width: 48%;
					  z-index: -1;
					  box-shadow: 0 19px 10px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
					  -webkit-transform: rotate(3deg);
					  -moz-transform: rotate(3deg);
					  transform: rotate(3deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_12"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:before {
						position:absolute;
						content:<?php echo esc_html($Rich_Web_Tabs); ?>;
						 height: 5px;
						bottom: 25px;
					    right: 5px;
					    width: 48%;
						z-index: -1;
						box-shadow: 0 19px 10px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
						-webkit-transform: rotate(3deg);
						-moz-transform: rotate(3deg);
						transform: rotate(3deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_12"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:before {
						position:absolute;
						content:"";
						bottom: calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 7px);
						bottom: -webkit-calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 7px);
					    right: 5px;
					     height: 5px;
					    width: 48%;
						z-index: -1;
						box-shadow: 0 19px 10px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
						-webkit-transform: rotate(3deg);
						-moz-transform: rotate(3deg);
						transform: rotate(3deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_13"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:before {
					  position:absolute;
					  content:"";
					  bottom: 11px;
					  left: 5%;
					  top: 35%;
					  width: 90%;
					  background:none;
					  z-index: -1;
					  box-shadow: 0 17px 20px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
					  -webkit-transform: rotate(0deg);
					  -moz-transform: rotate(0deg);
					  transform: rotate(0deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_13"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:before {
						  position:absolute;
						  content:"";
						  bottom: 11px;
					      left: 5%;
					      top: 35%;
					      width: 90%;
						  background:none;
						  z-index: -1;
						  box-shadow: 0 17px 20px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  -webkit-transform: rotate(0deg);
						  -moz-transform: rotate(0deg);
						  transform: rotate(0deg);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_13"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:before {
						  position:absolute;
						  content:"";
						  bottom: calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 20px);
						  bottom: -webkit-calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 20px);
					      left: 5%;
					      top: 0;
					      width: 90%;
						  background:none;
						  z-index: -1;
						  box-shadow: 0 17px 20px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
						  -webkit-transform: rotate(0deg);
						  -moz-transform: rotate(0deg);
						  transform: rotate(0deg);
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_14"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:before,.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_14"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:after {
					    position:absolute;
					    content:"";
					    bottom: 0px;
					    right: 0px;
					    top: 0;
					    width: 98%;
					    background:none;
					    z-index: -1;
					    box-shadow: 0 0px 31px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
					    -webkit-transform: rotate(0deg);
					    -moz-transform: rotate(0deg);
					    transform: rotate(0deg);
					    border-radius: 50%;
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_14"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:before,.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_14"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:after {
					    position:absolute;
					    content:"";
					    bottom: 0px;
					    right: 0px;
					    top: 0;
					    width: 98%;
					    background:none;
					    z-index: -1;
					    box-shadow: 0 0px 31px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					    -webkit-transform: rotate(0deg);
					    -moz-transform: rotate(0deg);
					    transform: rotate(0deg);
					    border-radius: 50%;
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_14"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover:after{
					    position:absolute;
					    content:"";
					    bottom: 0px;
					    left: 0px;
					    top: 0;
					    width: 98%;
					    background:none;
					    z-index: -1;
					    box-shadow: 0 0px 31px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					    -webkit-transform: rotate(0deg);
					    -moz-transform: rotate(0deg);
					    transform: rotate(0deg);
					    border-radius: 50%;
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_14"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:after{
					    position:absolute;
					    content:"";
					    bottom: 0px;
					    left: 0px;
					    top: 0;
					    width: 98%;
					    background:none;
					    z-index: -1;
					    box-shadow: 0 0px 31px var(--rw_tabs_menu_bsh_b-<?php echo esc_html($Rich_Web_Tabs); ?>);
					    -webkit-transform: rotate(0deg);
					    -moz-transform: rotate(0deg);
					    transform: rotate(0deg);
					    border-radius: 50%;
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_14"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:before {
						position:absolute;
					    content:"";
					    bottom: calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 31px);
					    bottom: -webkit-calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 31px);
					    right: 0px;
					    top: 0;
					    width: 98%;
					    background:none;
					    z-index: -1;
					    box-shadow: 0 0px 31px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					    transform: rotate(0deg);
					    -webkit-transform: rotate(0deg);
					    -moz-transform: rotate(0deg);
					    border-radius: 50%;
					}
				.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_14"] >	.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active:after {
						position:absolute;
					    content:"";
					    bottom: calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 31px);
					    bottom: -webkit-calc(100% - var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) - 31px);
					    left: 0px;
					    top: 0;
					    width: 98%;
					    background:none;
					    z-index: -1;
					    box-shadow: 0 0px 31px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					    -webkit-transform: rotate(0deg);
					    -moz-transform: rotate(0deg);
					    transform: rotate(0deg);
					    border-radius: 50%;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_1"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						background-position:right bottom;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_2"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						background-position:left bottom;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_3"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						background-position: 0 -100%;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_4"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						background-position: 100% 100%;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_5"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						box-shadow: inset 200px 100px 0 0 var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_6"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						background-image: linear-gradient(-75deg, rgba(0,0,0,.1) 30%, var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) 50%, rgba(0,0,0,.1) 70%);
					    background-size: 200%;
					    animation: shine 1.5s infinite;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_7"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
					   background-image: linear-gradient(-8deg, rgba(0,0,0,.6) 30%, var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) 50%, rgba(0,0,0,.6) 70%);
					   background-size: 200%;
					   animation: shine 1.5s infinite;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						background: var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="none"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						background: var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_1"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						box-shadow: 7px -5px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_2"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						box-shadow: 5px 5px 6px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_3"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						box-shadow: 3px 3px white, 5px 5px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_4"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						box-shadow:  inset 0 0 5px 5px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_5"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						box-shadow:  inset 10px 10px 26px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_6"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						box-shadow:5px 5px 5px #a5aaab, 9px 9px 5px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_9"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						box-shadow: 0 8px 6px -6px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_15"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						box-shadow: 0 0 10px var(--rw_tabs_menu_bsh_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						box-shadow: none;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover { 
						color: var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>);
						border:  var(--rw_tabs_menu_item-bw-<?php echo esc_html($Rich_Web_Tabs); ?>) var(--rw_tabs_menu_item-bs-<?php echo esc_html($Rich_Web_Tabs); ?>) var(--rw_tabs_menu_item-bc_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover i#rich-web-acd-icon<?php echo esc_html($Rich_Web_Tabs); ?> {
						color: var(--rw_tabs_menu_item-li_c-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover div.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> i {
						color: var(--rw_tabs_menu_item-ri_c_h);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_1"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active { 
						box-shadow: 7px -5px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);		
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_2"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active { 
						box-shadow: 5px 5px 6px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_3"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active { 
						box-shadow: 3px 3px white, 5px 5px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_4"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active { 
						box-shadow:  inset 0 0 5px 5px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_5"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active { 
						box-shadow:  inset 10px 10px 26px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_6"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active { 
						box-shadow:5px 5px 5px #a5aaab, 9px 9px 5px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_9"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active { 
						box-shadow: 0 8px 6px -6px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-box="style_bsh_15"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active { 
						box-shadow: 0 0 10px var(--rw_tabs_menu_bsh_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active {
						background: var(--rw_tabs_menu_item-a-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
						color: var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
						border:  var(--rw_tabs_menu_item-bw-<?php echo esc_html($Rich_Web_Tabs); ?>) var(--rw_tabs_menu_item-bs-<?php echo esc_html($Rich_Web_Tabs); ?>) var(--rw_tabs_menu_item-bc_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
						box-shadow: none;
						transition: none ;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_1"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 span{
						text-shadow: 0 0 4px var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
					    color: transparent;
					    transition: all 1s !important;
					    -webkit-transition: all 1s !important;
					    -moz-transition: all 1s !important;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_2"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 span{
						text-shadow: 1px 1px 0  var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>),
		                1px -1px 0  var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>),
		                -1px 1px 0  var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>),
		                -1px -1px 0  var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
		  			    color: white !important;
		    			transition: all 1s;
		    			-webkit-transition: all 1s;
		    			-moz-transition: all 1s;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_3"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 span{
						text-shadow: 1px 1px 0 var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>),
		                1px 1px 0 #aaaaaa,
		                2px 2px 0 #dbdbdb,
		                3px 3px 0 #eaeaea !important;
		    			color:var(--rw_tabs_menu_item-c-<?php echo esc_html($Rich_Web_Tabs); ?>);
		   				transition: all 1s;
		   				-webkit-transition: all 1s;
		    			-moz-transition: all 1s;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_4"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 span{
						text-shadow: 2px 2px !important;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_5"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 span{
						text-shadow: 0px 1px 0px #999, 0px 2px 0px #888, 0px 3px 0px #777,  0px 5px 7px #001135 !important;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover .arrowDown<?php echo esc_html($Rich_Web_Tabs); ?> {
						border-color: var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) transparent transparent transparent;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_1"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover span {
						text-shadow: 0 0 0 var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
					}					
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_2"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover span {
						text-shadow: 1px 1px 0 var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>), 1px -1px 0 yellowgreen, -1px 1px 0 var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>), -1px -1px 0 var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
		    			color: white;
					}					
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_3"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover span {
						text-shadow: 1px 1px 0 var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>),
		                1px -1px 0 #aaaaaa,
		                2px -2px 0 #dbdbdb,
		                3px -3px 0 #eaeaea !important;
		    			color:var(--rw_tabs_menu_item-h-c-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_5"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:hover .arrowDown<?php echo esc_html($Rich_Web_Tabs); ?> {
						border-color: var(--rw_tabs_menu_item-h-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) transparent transparent transparent !important;
					}
					@-webkit-keyframes shine {
						from {
						    background-position: 150%;
						}
						to {
						    background-position: -50%;
						}
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3:after {
						content: '';
						position: absolute;
					    left: 0;
					    display: inline-block;
					    height: 1em;
					    width: 100%;
					    border-bottom: 1px solid;
					    margin-top: 10px;
					    opacity: 0;
						-webkit-transition: opacity 0.35s,
						-webkit-transform: 0.35s;
						transition: opacity 0.35s, transform 0.35s;
						-moz-transition: opacity 0.35s, transform 0.35s;
						-webkit-transform: scale(0,1);
						transform: scale(0,1);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-bgc="style_bg_8"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> h3:hover:after {
						opacity: 1;
						-webkit-transform: scale(1);
						-moz-transform: scale(1);
						transform: scale(1);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active span {
						text-shadow: none;
						color: var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_2"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active span {
						text-shadow: 1px 1px 0 var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>), 1px -1px 0 var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>), -1px 1px 0 var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>), -1px -1px 0 var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
		    			color: white;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_3"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active span {
						text-shadow: 1px 1px 0 var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>),
		                1px 1px 0 #aaaaaa,
		                2px 2px 0 #dbdbdb,
		                3px 3px 0 #eaeaea !important;
		    			color:var(--rw_tabs_menu_item-a-c-<?php echo esc_html($Rich_Web_Tabs); ?>);
		   				transition: all 1s;
		   				-webkit-transition: all 1s;
		   				-moz-transition: all 1s;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-rw-text="style_ti_5"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active span {
						text-shadow: 0px 1px 0px #999, 0px 2px 0px #888, 0px 3px 0px #777, 0px 5px 7px #001135 !important;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active i#rich-web-acd-icon<?php echo esc_html($Rich_Web_Tabs); ?> {
						color: var(--rw_tabs_menu_item-li_c_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active div.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> {
						color: var(--rw_tabs_menu_item-ri_c_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 div.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> i {
						font-style: normal !important;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.alignLeft {
						padding-left: 35px;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 i#rich-web-acd-icon<?php echo esc_html($Rich_Web_Tabs); ?> {
						color: var(--rw_tabs_menu_item-ri_c-<?php echo esc_html($Rich_Web_Tabs); ?>);
						font-size: var( --rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>);
						width: var( --rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>);
						position: absolute;
						top: 50%;
						left:12px;
						z-index: 9;
						transform: translate(0, -50%);
						-webkit-transform: translate(0, -50%);
						-moz-transform: translate(0, -50%);
						margin-right: 8px;
						font-style: normal !important;
						box-sizing: border-box !important;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 i#rich-web-acd-icon<?php echo esc_html($Rich_Web_Tabs); ?>:hover {
						color: var(--rw_tabs_menu_item-li_c_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 i::before {
						display: block;
						text-align: center;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>  > h3 i:hover {
						color: var(--rw_tabs_menu_item-ri_c-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 div.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> i {
						color: var(--rw_tabs_menu_item-ri_c-<?php echo esc_html($Rich_Web_Tabs); ?>);
						font-size: var( --rw_tabs_menu_item-ri_s-<?php echo esc_html($Rich_Web_Tabs); ?>);
						width: var( --rw_tabs_menu_item-ri_s-<?php echo esc_html($Rich_Web_Tabs); ?>);
						position: relative;
						z-index: 9;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3.active div.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> i {
						color: var(--rw_tabs_menu_item-ri_c_a-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 div.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> i::before {
						display: block;
						text-align: center;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 div.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> i:hover {
						color: var(--rw_tabs_menu_item-ri_c_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="color"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > div {
						background: var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="transparent"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > div {
						background: transparent;
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="gradient"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > div {
						background: var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>);
						background: -webkit-linear-gradient(left, var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
						background: -o-linear-gradient(right, var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
						background: -moz-linear-gradient(right, var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
						background: linear-gradient(to right, var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>));
					}
					.Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>[data-style="gradient-top"] > .Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > div {
						background: var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
						background: -webkit-linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>)) !important;
						background: -o-linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>)) !important;
						background: -moz-linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>)) !important;
						background: linear-gradient( var(--rw_tabs_content_bgc-<?php echo esc_html($Rich_Web_Tabs); ?>), var(--rw_tabs_content_bgc2-<?php echo esc_html($Rich_Web_Tabs); ?>)) !important;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > div {
						display:block;
						border-radius: var(--rw_tabs_content_br-<?php echo esc_html($Rich_Web_Tabs); ?>);
						border: var(--rw_tabs_content_bw-<?php echo esc_html($Rich_Web_Tabs); ?>) solid var(--rw_tabs_content_bc-<?php echo esc_html($Rich_Web_Tabs); ?>);
						text-align: left;
						padding: 20px;
						margin: var(--rw_tabs_menu_content_gap-<?php echo esc_html($Rich_Web_Tabs); ?>) 0 10px 0;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> .arrowDown<?php echo esc_html($Rich_Web_Tabs); ?> {
						width: 0;
						height: 0;
						border-style: solid;
						border-width: 13.0px 7.5px 0 7.5px;
						border-color: var(--rw_tabs_menu_item-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) transparent transparent transparent;
						position: absolute;
						bottom: 0;
						left: 40px;
						opacity: 0;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> .active .arrowDown<?php echo esc_html($Rich_Web_Tabs); ?> {
						bottom: -13px;
						border-color: var(--rw_tabs_menu_item-a-bgc-<?php echo esc_html($Rich_Web_Tabs); ?>) transparent transparent transparent;
						opacity: 1;
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> .collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> {
						position: absolute;
						color:var(--rw_tabs_menu_item-ri_c-<?php echo esc_html($Rich_Web_Tabs); ?>);
						font-size: var(--rw_tabs_menu_item-ri_s-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
						right: 0;
						top: 50%;
						font-size: 25px;
						font-weight: 300;
						-ms-transform: translate(0, -50%);
						transform: translate(0, -50%);
						-webkit-transform: translate(0, -50%);
						margin-right: 10px;
						z-index: 2;
					}
					@media screen and (max-width: 570px) {
						.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> .collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> {
							background: transparent !important;
						}
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> .collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?>:hover {
						color:var(--rw_tabs_menu_item-ri_c_h-<?php echo esc_html($Rich_Web_Tabs); ?>);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> .collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?>.alignLeft {
						right: initial;
						left: 20px;
					}
					<?php require( 'Style/RW_Tabs_Acd_Styles/rw_tabs_acd_'.$RW_Tabs_Style_Number.'.css.php' ); ?>				
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3 span.rw_tabs_act_st_title {
						font-family: var(--rw_tabs_text_font_family-<?php echo esc_html($Rich_Web_Tabs); ?>);
						font-size: var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
						position: relative;
						z-index: 2;
						display: inline-block;
						left: calc(var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) + 10px);
						left: -webkit-calc(var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) + 10px);
						line-height: var(--rw_tabs_text_font_size-<?php echo esc_html($Rich_Web_Tabs); ?>) !important;
						margin-right: 70px;
						margin-left: calc(var(--rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>) - 5px);
						margin-left: -webkit-calc(var(--rw_tabs_menu_item-li_s-<?php echo esc_html($Rich_Web_Tabs); ?>) - 5px);
					}
					.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> .rw_tabs_acd_cont<?php echo esc_html($Rich_Web_Tabs); ?> p {
						margin: 0 !important;
					}
		  </style>
		  <section id="RW_Tabs_Section_T_H_<?php echo esc_html($Rich_Web_Tabs); ?>">
					<div class="Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>" id="RW_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>"  data-style="<?php echo  esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_C_Type); ?>"  data-rw-box="<?php echo  esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Box_S); ?>" data-rw-bgc="<?php echo  esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Bgc_S); ?>" data-rw-text="<?php echo  esc_attr($Rich_Web_Tabs_Theme_Prop->RWTabs_Item_Text_S); ?>">
					<?php for ($i = 0; $i < count($Rich_Web_Tabs_Fields); $i++) { ?>
				<div class="Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>" data-sort="<?php echo  esc_attr($i + 1 );?>" >
					<h3 class="Rich_Web_Tabs_h3_<?php echo esc_html($Rich_Web_Tabs); ?>">
						<i id="rich-web-acd-icon<?php echo esc_html($Rich_Web_Tabs); ?>" class='rich_web rich_web-<?php echo esc_attr($Rich_Web_Tabs_Fields[$i]->Tabs_Icon); ?>'></i>
						<div class="rw_tabs_act_st_div_l<?php echo esc_html($Rich_Web_Tabs); ?>"><span class="rw_tabs_act_st_l<?php echo esc_html($Rich_Web_Tabs); ?>"></span></div>
						<span class="rw_tabs_act_st_r<?php echo esc_html($Rich_Web_Tabs); ?>"></span>
						<span class="rw_tabs_act_st_title"> <?php echo  esc_html($Rich_Web_Tabs_Fields[$i]->Tabs_Subtitle); ?></span>
					</h3>
					<div class="rw_tabs_acd_cont<?php echo esc_html($Rich_Web_Tabs); ?>" >
						<div id="rw_tabs_acd_cont<?php echo esc_html($Rich_Web_Tabs); ?>">
						<?php echo do_shortcode(html_entity_decode(esc_html($Rich_Web_Tabs_Fields[$i]->Tabs_Content))); ?>
						</div>
					</div>
				</div>
 		<?php  } ?>
			</div>
			</section>
				<script type="text/javascript">
				var rw_t_acd_up,rw_t_acd_down;
				var RW_Tabs_Return_Right_Icon = function(iconType) {
					switch (iconType) {
						case 'sort-desc':
							rw_t_acd_up = 'sort-desc';
							rw_t_acd_down = 'sort-asc';
							break;
						case 'circle':
							rw_t_acd_up = 'circle';
							rw_t_acd_down = 'circle-o';
							break;
						case 'angle-double-up':
							rw_t_acd_up = 'angle-double-down';
							rw_t_acd_down = 'angle-double-up';						
							break;
						case 'arrow-circle-up':
							rw_t_acd_up = 'arrow-circle-down';
							rw_t_acd_down = 'arrow-circle-up';
							break;
						case 'angle-up':
							rw_t_acd_up = 'angle-down';
							rw_t_acd_down = 'angle-up';
							break;
						case 'plus':
							rw_t_acd_up = 'plus';
							rw_t_acd_down = 'minus';
							break;							
					}
				}
				var RW_Tabs_Cont_Anim = '<?php echo  esc_js($Rich_Web_Tabs_Theme_Prop->RWTabs_Cont_Anim); ?>';
				RW_Tabs_Return_Right_Icon('<?php echo  esc_js($Rich_Web_Tabs_Theme_Prop->RWTabs_RightIcon); ?>');
				jQuery(".Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > div").animate({
					"display": "block",
				}, 500, function() {
					jQuery(".Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > div").slideUp(500, function() {
						jQuery(".Rich_Web_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>").css("height", "auto")
					});
				})
				jQuery(document).ready(function() {
					(function(jQuery) {
						var settings<?php echo esc_html($Rich_Web_Tabs); ?>;
						var y<?php echo esc_html($Rich_Web_Tabs); ?> = 0;
						jQuery.fn.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> = function(actionOrSettings<?php echo esc_html($Rich_Web_Tabs); ?>, parameter<?php echo esc_html($Rich_Web_Tabs); ?>) {
							if (typeof actionOrSettings<?php echo esc_html($Rich_Web_Tabs); ?> === 'object' || actionOrSettings<?php echo esc_html($Rich_Web_Tabs); ?> === undefined) {
								settings<?php echo esc_html($Rich_Web_Tabs); ?> = jQuery.extend({
									headline<?php echo esc_html($Rich_Web_Tabs); ?>: 'h3',
									prefix<?php echo esc_html($Rich_Web_Tabs); ?>: false,
									highlander<?php echo esc_html($Rich_Web_Tabs); ?>: true,
									collapsible<?php echo esc_html($Rich_Web_Tabs); ?>: false,
									arrow<?php echo esc_html($Rich_Web_Tabs); ?>: true,
									collapseIcons<?php echo esc_html($Rich_Web_Tabs); ?>: {
										opened<?php echo esc_html($Rich_Web_Tabs); ?>: `<i class="rich_web rich_web-${rw_t_acd_down}"></i>`,
										closed<?php echo esc_html($Rich_Web_Tabs); ?>: `<i class="rich_web rich_web-${rw_t_acd_up}"></i>`
									},
									collapseIconsAlign<?php echo esc_html($Rich_Web_Tabs); ?>: 'right',
									scroll<?php echo esc_html($Rich_Web_Tabs); ?>: true
								}, actionOrSettings<?php echo esc_html($Rich_Web_Tabs); ?>);
							}
							if (actionOrSettings<?php echo esc_html($Rich_Web_Tabs); ?> == "open<?php echo esc_html($Rich_Web_Tabs); ?>") {
								if (settings<?php echo esc_html($Rich_Web_Tabs); ?>.highlander<?php echo esc_html($Rich_Web_Tabs); ?>) {
									jQuery(this).Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>('forceCloseAll<?php echo esc_html($Rich_Web_Tabs); ?>');
								}
								var ogThis<?php echo esc_html($Rich_Web_Tabs); ?> = jQuery(this);
								if (settings<?php echo esc_html($Rich_Web_Tabs); ?>.collapseIcons<?php echo esc_html($Rich_Web_Tabs); ?>) {
									jQuery('.ic_<?php echo esc_html($Rich_Web_Tabs); ?>', ogThis<?php echo esc_html($Rich_Web_Tabs); ?>).html(settings<?php echo esc_html($Rich_Web_Tabs); ?>.collapseIcons<?php echo esc_html($Rich_Web_Tabs); ?>.opened<?php echo esc_html($Rich_Web_Tabs); ?>);
								}
								jQuery(this).parent().addClass('active');
								jQuery(this).addClass('active').next('div.rw_tabs_acd_cont<?php echo esc_html($Rich_Web_Tabs); ?>').slideDown(300, function() {
									if (parameter<?php echo esc_html($Rich_Web_Tabs); ?> !== false) {
										smoothScrollTo<?php echo esc_html($Rich_Web_Tabs); ?>(jQuery(this).prev(settings<?php echo esc_html($Rich_Web_Tabs); ?>.collapseIcons<?php echo esc_html($Rich_Web_Tabs); ?>));
									}
								});
								if (RW_Tabs_Cont_Anim  != 'none') {
									jQuery(".active").next('div.rw_tabs_acd_cont<?php echo esc_html($Rich_Web_Tabs); ?>').find(jQuery("div #rw_tabs_acd_cont<?php echo esc_html($Rich_Web_Tabs); ?>")).hide();
									jQuery(".active").next('div.rw_tabs_acd_cont<?php echo esc_html($Rich_Web_Tabs); ?>').find(jQuery("div #rw_tabs_acd_cont<?php echo esc_html($Rich_Web_Tabs); ?>")).show(RW_Tabs_Cont_Anim, 1000);
								}
								return this;
							} else if (actionOrSettings<?php echo esc_html($Rich_Web_Tabs); ?> == "close<?php echo esc_html($Rich_Web_Tabs); ?>" || actionOrSettings<?php echo esc_html($Rich_Web_Tabs); ?> == "forceClose<?php echo esc_html($Rich_Web_Tabs); ?>") {
								if (actionOrSettings<?php echo esc_html($Rich_Web_Tabs); ?> == "close<?php echo esc_html($Rich_Web_Tabs); ?>" && !settings<?php echo esc_html($Rich_Web_Tabs); ?>.collapsible<?php echo esc_html($Rich_Web_Tabs); ?> && jQuery(".Rich_Web_Tabs_h3_<?php echo esc_html($Rich_Web_Tabs); ?>" + '[class="active"]').length == 1) {
									return this;
								}
								var ogThis<?php echo esc_html($Rich_Web_Tabs); ?> = jQuery(this);
								if (settings<?php echo esc_html($Rich_Web_Tabs); ?>.collapseIcons<?php echo esc_html($Rich_Web_Tabs); ?>) {
									jQuery('.ic_<?php echo esc_html($Rich_Web_Tabs); ?>', ogThis<?php echo esc_html($Rich_Web_Tabs); ?>).html(settings<?php echo esc_html($Rich_Web_Tabs); ?>.collapseIcons<?php echo esc_html($Rich_Web_Tabs); ?>.closed<?php echo esc_html($Rich_Web_Tabs); ?>);
								}
								// y<?php echo esc_html($Rich_Web_Tabs); ?>--
								jQuery(this).removeClass('active').next('div.rw_tabs_acd_cont<?php echo esc_html($Rich_Web_Tabs); ?>').slideUp(200, function() {
									jQuery(this).parent().removeClass('active');
								});
								return this;
							} else if (actionOrSettings<?php echo esc_html($Rich_Web_Tabs); ?> == "closeAll") {
								jQuery(".Rich_Web_Tabs_h3_<?php echo esc_html($Rich_Web_Tabs); ?>").Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>('close<?php echo esc_html($Rich_Web_Tabs); ?>');
							} else if (actionOrSettings<?php echo esc_html($Rich_Web_Tabs); ?> == "forceCloseAll<?php echo esc_html($Rich_Web_Tabs); ?>") {
								jQuery(".Rich_Web_Tabs_h3_<?php echo esc_html($Rich_Web_Tabs); ?>").Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>('forceClose<?php echo esc_html($Rich_Web_Tabs); ?>');
							}
							if (settings<?php echo esc_html($Rich_Web_Tabs); ?>.prefix<?php echo esc_html($Rich_Web_Tabs); ?>) {
								jQuery(".Rich_Web_Tabs_h3_<?php echo esc_html($Rich_Web_Tabs); ?>", this).attr('data-prefix<?php echo esc_html($Rich_Web_Tabs); ?>', settings<?php echo esc_html($Rich_Web_Tabs); ?>.prefix<?php echo esc_html($Rich_Web_Tabs); ?>);
							}
							if (settings<?php echo esc_html($Rich_Web_Tabs); ?>.arrow<?php echo esc_html($Rich_Web_Tabs); ?>) {
								jQuery(".Rich_Web_Tabs_h3_<?php echo esc_html($Rich_Web_Tabs); ?>", this).append('<div class="arrowDown<?php echo esc_html($Rich_Web_Tabs); ?>"></div>');
							}
							if (settings<?php echo esc_html($Rich_Web_Tabs); ?>.collapseIcons<?php echo esc_html($Rich_Web_Tabs); ?>) {
								jQuery(".Rich_Web_Tabs_h3_<?php echo esc_html($Rich_Web_Tabs); ?>", this).each(function(index, el) {
									if (jQuery(this).hasClass('active')) {
										jQuery(this).append('<div class="collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> ic_<?php echo esc_html($Rich_Web_Tabs); ?>">' + settings<?php echo esc_html($Rich_Web_Tabs); ?>.collapseIcons<?php echo esc_html($Rich_Web_Tabs); ?>.opened<?php echo esc_html($Rich_Web_Tabs); ?> + '</div>');
									} else {
										jQuery(this).append('<div class="collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?> ic_<?php echo esc_html($Rich_Web_Tabs); ?>">' + settings<?php echo esc_html($Rich_Web_Tabs); ?>.collapseIcons<?php echo esc_html($Rich_Web_Tabs); ?>.closed<?php echo esc_html($Rich_Web_Tabs); ?> + '</div>');
									}
								});
							}
							if (settings<?php echo esc_html($Rich_Web_Tabs); ?>.collapseIconsAlign<?php echo esc_html($Rich_Web_Tabs); ?> == 'left') {
								jQuery('.collapseIcon<?php echo esc_html($Rich_Web_Tabs); ?>, ' + settings<?php echo esc_html($Rich_Web_Tabs); ?>.headline<?php echo esc_html($Rich_Web_Tabs); ?>).addClass('alignLeft');
							}
							jQuery(".Rich_Web_Tabs_h3_<?php echo esc_html($Rich_Web_Tabs); ?>",this).click(function () {
								if (jQuery(this).hasClass('active')) {
									jQuery(this).Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>('close<?php echo esc_html($Rich_Web_Tabs); ?>');
								}else{
									jQuery(this).Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>('open<?php echo esc_html($Rich_Web_Tabs); ?>', settings<?php echo esc_html($Rich_Web_Tabs); ?>.scroll<?php echo esc_html($Rich_Web_Tabs); ?>);
								}
   								});
						};
					}(jQuery));
					function smoothScrollTo<?php echo esc_html($Rich_Web_Tabs); ?>(element, callback) {
						var time = 400;
						jQuery('html').animate({
						}, time, callback);
					}
				});
				jQuery(document).ready(function() {
					var d_height = jQuery('.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?> > h3').height();
					jQuery('.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>').Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>({
						collapsible<?php echo esc_html($Rich_Web_Tabs); ?>: true,
					});
				});
				function RW_Tabs_Refresh_Acd() {
					jQuery('#RW_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>').html(jQuery('#RW_Tabs_Accordion_<?php echo esc_html($Rich_Web_Tabs); ?>').html());
					jQuery('.Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>').Rich_Web_Tabs_Accordion_Content_<?php echo esc_html($Rich_Web_Tabs); ?>({
						collapsible<?php echo esc_html($Rich_Web_Tabs); ?>: true,
					});
				}
			</script>
<?php
} 
		echo $after_widget;
	}
}
?>