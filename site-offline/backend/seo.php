<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
$sahu_so_seo = unserialize(get_option('sahu_so_seo'));	
?>
<table class="form-table">
							
	<tr>
		<th><?php _e('Favicon Icon','site-offline'); ?></th>
	</tr>
	<tr class="radio-span" >
		<td>
				
				<img src="<?php echo $sahu_so_seo['sahu_so_favicon']; ?>" class="sahu-csw-admin-img" />
				
				<input type="button" id="upload-background" name="upload-background" value="Upload Image" class="button-primary rcsp_media_upload"  />
				<input type="hidden" id="sahu_so_favicon" name="sahu_so_favicon" class="rcsp_label_text"  value="<?php echo esc_url($sahu_so_seo['sahu_so_favicon']); ?>"  readonly="readonly" placeholder="No Media Selected" />
				
		</td>
		
	</tr>
	
	
	<tr>
		<th><?php _e('SEO Title','site-offline'); ?></th>
	</tr>
	<tr class="radio-span" >
		<td>
				<input type="text" class="pro_text" id="sahu_so_seo_title" name="sahu_so_seo_title" placeholder="<?php _e('Enter Your SEO Title Here...','site-offline'); ?>" size="56" value="<?php echo esc_attr($sahu_so_seo['sahu_so_seo_title']); ?>" />
	
		</td>
		
	</tr>

	<tr>
		<th><?php _e('SEO Description','site-offline'); ?></th>
	</tr>
	<tr class="radio-span" >
		<td>
				<textarea rows="6"  class="pro_text" id="sahu_so_seo_desc" name="sahu_so_seo_desc" placeholder="<?php _e('Enter Your SEO Description Here...','site-offline'); ?>"><?php echo esc_textarea(stripslashes($sahu_so_seo['sahu_so_seo_desc'])); ?></textarea>
	
		</td>
		
	</tr>

	<tr>
		<th><?php _e('Google Analytics Script','site-offline'); ?></th>
	</tr>
	<tr class="radio-span" >
		<td>
				<textarea rows="6"  class="pro_text" id="sahu_so_seo_analytiso" name="sahu_so_seo_analytiso" placeholder="<?php _e('Enter Your Google Analytics Script Here','site-offline'); ?>"><?php echo esc_textarea(stripslashes($sahu_so_seo['sahu_so_seo_analytiso'])); ?></textarea>
	
		</td>
		
	</tr>
<tr class="radio-span" >
		<td>
				<button class="portfolio_read_more_btn "  onclick="sahu_save_data_seo()"><?php _e('Save Settings','site-offline'); ?></button>
				<button class="portfolio_demo_btn" onclick="sahu_reset_data_seo()" ><?php _e('Reset Default Setting','site-offline'); ?></button>
		</td>
		
	</tr>		
	
</table>



<script>
function sahu_save_data_seo(){
 jQuery("#sahu_loding_image").show();
 var site_offline_form_secure = jQuery("#site_offline_form_secure").val();
 var sahu_so_favicon = jQuery("#sahu_so_favicon").val();
 var sahu_so_seo_title = jQuery("#sahu_so_seo_title").val();
 var sahu_so_seo_desc = jQuery("#sahu_so_seo_desc").val();
 var sahu_so_seo_analytiso = jQuery("#sahu_so_seo_analytiso").val();
 
 
 	jQuery.ajax(
            {
	    	    type: "POST",
		        url: location.href,
	
		        data : {
			    'action_seo':'sahu_sop_seo',
				 'site_offline_form_secure':site_offline_form_secure,
			    'sahu_so_favicon':sahu_so_favicon,
			    'sahu_so_seo_title':sahu_so_seo_title,
			    'sahu_so_seo_desc':sahu_so_seo_desc,
			    'sahu_so_seo_analytiso':sahu_so_seo_analytiso,
			   
			        },
                success : function(data){
									jQuery("#sahu_loding_image").fadeOut();	
									jQuery(".dialog-button").click();
                                   //location.href='?page=sahu_coming_soon_wp';
								  
                                   }			
            });
 
}
</script>
<?php
if(isset($_POST['site_offline_form_secure'])) 
{
	if ( wp_verify_nonce( $_POST['site_offline_form_secure'], 'site_offline_secure_action_nonce' ) )
	{	
		if(isset($_POST['action_seo'])=="sahu_sop_seo") {
		$sahu_so_favicon          = sanitize_text_field($_POST['sahu_so_favicon']);
		$sahu_so_seo_title        = sanitize_text_field($_POST['sahu_so_seo_title']);
		$sahu_so_seo_desc         = wp_kses_post($_POST['sahu_so_seo_desc']);
		$sahu_so_seo_analytiso    = wp_kses_post($_POST['sahu_so_seo_analytiso']);
					
					
		$seo = serialize( array(
			'sahu_so_favicon' 		       => $sahu_so_favicon,
			'sahu_so_seo_title' 		       => $sahu_so_seo_title,
			'sahu_so_seo_desc' 		       => $sahu_so_seo_desc,
			'sahu_so_seo_analytiso' 		       => $sahu_so_seo_analytiso,
			
			) );

		update_option('sahu_so_seo', $seo);
		}
	}
}
 ?>
 
 
  <script>
 
	function sahu_reset_data_seo(){
		if (confirm('Are you sure you want to reset this setting?')) {
    
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
			'reset_action_seo':'action_seo_reset',
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

		if(isset($_POST['reset_action_seo'])=="action_seo_reset") {
			
			$default_url2 =  SAHU_SO_PLUGIN_URL.'assets/img/logo.png'; 
			
			
			$sahu_seo = serialize( array(
			'sahu_so_favicon' 		       => $default_url2,
			'sahu_so_seo_title' 		   => "Site Is Offline",
			'sahu_so_seo_desc' 		       => "",
			'sahu_so_seo_analytiso' 	   => "",
			
			) );

			update_option('sahu_so_seo', $sahu_seo);
		}
	}
}
 ?>