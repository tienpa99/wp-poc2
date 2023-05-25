<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
$sahu_so_dashboard = unserialize(get_option('sahu_so_dashboard'));	
?>
<table class="form-table">
	<tr>
		<th><?php _e('Enable Site Offline Mode','site-offline'); ?></th>
	</tr>
	<tr class="radio-span" >
		<td>
			<span style="margin-bottom:10px;display: block;">
				<input type="radio" name="sahu_so_status" value="0" id="sahu_so_status" <?php if($sahu_so_dashboard['sahu_so_status'] == "0") { echo "checked"; } ?>  />&nbsp;<?php _e('Disabled','site-offline'); ?><br>
			</span>
			<span>	
				<input type="radio" name="sahu_so_status" value="1" id="sahu_so_status" <?php if($sahu_so_dashboard['sahu_so_status'] == "1") { echo "checked"; } ?>  />&nbsp;<?php _e('Enable Site Offline Mode','site-offline'); ?><br>
			</span>
		</td>
	</tr>
	<tr>
		<th><?php _e('Site Offline Logo','site-offline'); ?></th>
	</tr>
	<tr class="radio-span" >
		<td>
			<img src="<?php echo esc_url($sahu_so_dashboard['so_logo_url']); ?>" class="sahu-csw-admin-img" />
			<input type="button" id="upload-background" name="upload-background" value="Upload Image" class="button-primary rcsp_media_upload"  />
			<input type="hidden" id="so_logo_url" name="so_logo_url" class="rcsp_label_text"  value="<?php echo esc_url($sahu_so_dashboard['so_logo_url']); ?>"  readonly="readonly" placeholder="No Media Selected" />
		</td>
		
	</tr>
	
	<tr>
		<th><?php _e('Display Logo','site-offline'); ?></th>
	</tr>
	<tr class="radio-span" >
		<td>
			<span style="margin-bottom:10px;display: block;">
				<input type="radio" name="display_logo" value="0" id="display_logo" <?php if($sahu_so_dashboard['display_logo'] == "0") { echo "checked"; } ?> />&nbsp;<?php _e('Yes','site-offline'); ?><br>
			</span>
			<span>	
				<input type="radio" name="display_logo" value="1" id="display_logo" <?php if($sahu_so_dashboard['display_logo'] == "1") { echo "checked"; } ?>   />&nbsp;<?php _e('No','site-offline'); ?><br>
			</span>
		</td>
	</tr>
	<tr>
		<th><?php _e('Site Offline Headline','site-offline'); ?></th>
	</tr>
	<tr class="radio-span" >
		<td>
				<input type="text" class="pro_text" id="so_headline" name="so_headline" placeholder="<?php _e('Enter Site Offline Title/Headline Here..','site-offline'); ?>" size="56" value="<?php echo esc_textarea(stripslashes($sahu_so_dashboard['so_headline'])); ?>" />
	
		</td>
		
	</tr>

	<tr>
		<th><?php _e('Site Offline Description','site-offline'); ?></th>
	</tr>
	<tr class="radio-span" >
		<td>
				<textarea rows="6"  class="pro_text" id="so_description" name="so_description" placeholder="<?php _e('Enter Your Coming Soon Description Here...','site-offline'); ?>"><?php echo esc_textarea(stripslashes($sahu_so_dashboard['so_description'])); ?></textarea>
	
		</td>
		
	</tr>
	
	<tr class="radio-span" >
		<td>	
				
				<button class="portfolio_read_more_btn "  onclick="sahu_save_data_dashboard()"><?php _e('Save Settings','site-offline'); ?></button>
				<button class="portfolio_demo_btn"  onclick="sahu_reset_data_dashboard()"><?php _e('Reset Default Settings','site-offline'); ?></button>
		</td>
		
	</tr>							
	
</table>

<script>
function sahu_save_data_dashboard(){

 jQuery("#sahu_loding_image").show();
 var site_offline_form_secure = jQuery("#site_offline_form_secure").val();
 var so_headline = jQuery("#so_headline").val();
 var so_description = jQuery("#so_description").val();
 var sahu_so_status = jQuery('input:radio[name="sahu_so_status"]:checked').val();
 var display_logo = jQuery('input:radio[name="display_logo"]:checked').val();
var so_logo_url = jQuery("#so_logo_url").val();
 
 
 	jQuery.ajax(
            {
	    	    type: "POST",
		        url: location.href,
	
		        data : {
			    'action_dashboard':'sahu_sop_dashboard',
			    'site_offline_form_secure':site_offline_form_secure,
			    'sahu_so_status':sahu_so_status,
			    'so_headline':so_headline,
			    'so_description':so_description,
			    'display_logo':display_logo,
			    'so_logo_url':so_logo_url,
			   
			        },
                success : function(data){
					jQuery("#sahu_loding_image").fadeOut();
					jQuery(".dialog-button").click();
					location.href='?page=sahu_site_offline_wp';
			  
			   }			
            });
 
}

</script>
<?php
if(isset($_POST['site_offline_form_secure'])) 
{
	if ( wp_verify_nonce( $_POST['site_offline_form_secure'], 'site_offline_secure_action_nonce' ) )
	{	
		if(isset($_POST['action_dashboard'])=="sahu_sop_dashboard") 
		{
			$sahu_so_status       = sanitize_option('sahu_so_status', $_POST['sahu_so_status']);
			$so_headline          = sanitize_text_field($_POST['so_headline']);
			$so_description       = wp_kses_post($_POST['so_description']);
			$display_logo         = sanitize_option('display_logo', $_POST['display_logo']);
			$so_logo_url          = sanitize_text_field($_POST['so_logo_url']);
				
				
			$dashboard = serialize( array(
			'sahu_so_status' 		       => $sahu_so_status,
			'so_headline' 		       => $so_headline,
			'so_description' 		       => $so_description,
			'display_logo' 		       => $display_logo,
			'so_logo_url' 		       => $so_logo_url,
			
			) );

			update_option('sahu_so_dashboard', $dashboard);
		}
	}
}
 ?>
 
<script>
 
	function sahu_reset_data_dashboard(){
		if (confirm('Are you sure you want to reste this setting?')) {
    
		} else {
		   return;
		}
		jQuery("#sahu_loding_image").show();
		var site_offline_form_secure = jQuery("#site_offline_form_secure").val();
		jQuery.ajax(
		{
			type: "POST",
			url: location.href,

			data : {
			'reset_action_dashboard':'action_dashboard_reset',
			'site_offline_form_secure':site_offline_form_secure,
			
		   
				},
			success : function(data){
				jQuery("#sahu_loding_image").fadeOut();
				jQuery(".dialog-button").click();
				location.href='?page=sahu_site_offline_wp';
		  
		   }			
		});
	 
	}
	
</script>

<?php
if(isset($_POST['site_offline_form_secure'])) 
{
	if ( wp_verify_nonce( $_POST['site_offline_form_secure'], 'site_offline_secure_action_nonce' ) )
	{	

		if(isset($_POST['reset_action_dashboard'])=="action_dashboard_reset") {
			$default_url2 =  SAHU_SO_PLUGIN_URL.'assets/img/logo.png'; 
			$sahu_dashboard = serialize( array(
			'sahu_so_status' 		       => "0",
			'so_headline' 		       => "Site Offline",
			'so_description' 		       => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut vel fermentum dui. Pellentesque vitae porttitor ex, euismod sodales magna. Nunc sed felis sed dui pellentesque sodales porta a magna. Donec dui augue, dignissim faucibus lorem nec, fringilla molestie massa. Sed blandit dapibus bibendum. Sed interdum commodo laoreet. Sed mi orci.",
			'display_logo' 		       => "0",
			'so_logo_url' 		       => $default_url2,
			
			) );
			update_option('sahu_so_dashboard', $sahu_dashboard);
		}
	}
}
 ?>
