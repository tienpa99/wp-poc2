<?php
	if(!defined('ABSPATH')) exit;
	if(!current_user_can('manage_options'))
	{
		die('Access Denied');
	}
?>
<style type="text/css">
	#wpcontent{ padding-left: 0 !important; }
	#Rich_Web_Header_Main {background: linear-gradient( 135deg , #6ecae9, #312a6c);;text-align: left;display: flex;flex-direction: row;flex-wrap: wrap;align-content: flex-start;padding: 20px 20px 10px 20px;gap: 25px;     box-shadow: 0px 7px 10px 0px #c3c4c7;position: relative;    justify-content: space-between;}
	#Rich_Web_Header_Main img { width: 26%; }
	#RW_Flex_Section {display: flex;flex-direction: column;align-content: flex-end;justify-content: space-between;align-items: flex-end;}
	#Rich_Web_Header_Pro_Option{    margin-bottom: 15px;}
	#Rich_Web_Header_Pro_Option > a:focus,#Rich_Web_Header_Pro_Option > a:active { outline:none; text-decoration:none;}
	#Rich_Web_Header_Links { font-size: 16px; padding: 15px; margin-left: auto;margin-top: auto; z-index: 2;}
	#Rich_Web_Header_Links a { text-decoration: none; color: #fff; margin-left: 10px; padding: 6px 15px;  background-color: #6ecae9;   box-shadow: -3px 5px 10px 0px #30a9d1;   border-radius:5px 30px; transition:all 0.3s ease 0s;}
	#Rich_Web_Header_Links a:hover { background-color: #30a9d1; color: #ffffff; box-shadow:3px 5px 10px 0px #30a9d1; border-radius:30px 5px;}
	#Rich_Web_Header_Pro_Option { position: relative;  text-align:center; font-size: 18px;  }
	@media screen and (max-width: 767px) { #Rich_Web_Header_Main {display: flex;flex-direction: column;padding: 20px 20px 10px 20px;gap: 15px;box-shadow: 0px 7px 10px 0px #c3c4c7;position: relative;align-content: center;align-items: center;justify-content: space-between;} }
</style>
<div id="Rich_Web_Header_Main">
	<img src="<?php echo esc_url(plugins_url('/Images/rich-web-logo.png',__FILE__));?>">
	<div id="RW_Flex_Section">
		<div id="Rich_Web_Header_Pro_Option">
			<a href="http://rich-web.org/wp-tab-accordion/" target="_blank" style="text-decoration: none; color: #fff; display: block;">
				<i class=' rich_web rich_web-shopping-basket' style="margin-right: 10px;"></i>
				This is free version. <br>
				<span style="display:block;margin-top:5px;">For more adventures click to buy Pro version.</span>
			</a>
		</div>
		<div id="Rich_Web_Header_Links">
			<a href="https://wordpress.org/support/plugin/tabbed/" target="_blank">Support</a>
			<a href="https://rich-web.org/wordpress-tabs-1/" target="_blank">Demo</a>
			<a href="https://wordpress.org/support/plugin/tabbed/" target="_blank">Contact US</a>
		</div>	
	</div>
</div>