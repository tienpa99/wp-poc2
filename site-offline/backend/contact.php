<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
$sahu_so_contact = unserialize(get_option('sahu_so_contact'));	
?>
<table class="form-table">
		<tr>
			<th><?php _e('Address','site-offline'); ?></th>
		</tr>
		<tr class="radio-span" >
			<td>
					
				
				<input type="text" class="pro_text" id="sahu_so_address" name="sahu_so_address" placeholder="<?php _e('Enter your Address','site-offline'); ?>" size="56" value="<?php echo esc_attr($sahu_so_contact['sahu_so_address']); ?>" />
							
								
			</td>
			
		</tr>
		<tr>
			<th><?php _e('Contact No.','site-offline'); ?></th>
		</tr>
		<tr class="radio-span" >
			<td>
					
				
				<input type="text" class="pro_text" id="sahu_so_no" name="sahu_so_no" placeholder="<?php _e('Enter your contact No.','site-offline'); ?>" size="56" value="<?php echo esc_attr($sahu_so_contact['sahu_so_no']); ?>" />
							
								
			</td>
			
		</tr>
		
		<tr>
			<th><?php _e('Email Address','site-offline'); ?></th>
		</tr>
		<tr class="radio-span" >
			<td>
					
				
				<input type="text" class="pro_text" id="sahu_so_email" name="sahu_so_email" placeholder="<?php _e('Enter your contact email','site-offline'); ?>" size="56" value="<?php echo esc_attr($sahu_so_contact['sahu_so_email']); ?>" />
							
								
			</td>
			
		</tr>
		<tr class="radio-span" >
		<td>
				<button class="portfolio_read_more_btn "  onclick="sahu_save_data_contact()"><?php _e('Save Settings','site-offline'); ?></button>
				<button class="portfolio_demo_btn" onclick="sahu_reset_data_contact()" ><?php _e('Reset Default Setting','site-offline'); ?></button>
		</td>
		
	</tr>	
		
</table>


<script>
function sahu_save_data_contact(){
 jQuery("#sahu_loding_image").show();
 var site_offline_form_secure = jQuery("#site_offline_form_secure").val();
 var sahu_so_address = jQuery("#sahu_so_address").val();
 var sahu_so_no = jQuery("#sahu_so_no").val();
 var sahu_so_email = jQuery("#sahu_so_email").val();
 
 
 	jQuery.ajax(
            {
	    	    type: "POST",
		        url: location.href,
	
		        data : {
			    'action_contact':'sahu_sop_contact',
			    'sahu_so_address':sahu_so_address,
				 'site_offline_form_secure':site_offline_form_secure,
			    'sahu_so_no':sahu_so_no,
			    'sahu_so_email':sahu_so_email,
			    
			        },
                success : function(data){
								jQuery("#sahu_loding_image").fadeOut();
								jQuery(".dialog-button").click();
                                  
                                   }			
            });
 
}
</script>
<?php
if(isset($_POST['site_offline_form_secure'])) 
{
	if ( wp_verify_nonce( $_POST['site_offline_form_secure'], 'site_offline_secure_action_nonce' ) )
	{	
if(isset($_POST['action_contact'])=="sahu_sop_contact") {
$sahu_so_address        = sanitize_text_field($_POST['sahu_so_address']);
$sahu_so_no             = sanitize_text_field($_POST['sahu_so_no']);
$sahu_so_email          = sanitize_text_field($_POST['sahu_so_email']);
			
			
$contact = serialize( array(
	'sahu_so_address' 		       => $sahu_so_address,
	'sahu_so_no' 		       => $sahu_so_no,
	'sahu_so_email'    		   => $sahu_so_email,
	
	) );

update_option('sahu_so_contact', $contact);
}
	}
}
 ?>
 
  <script>
 
	function sahu_reset_data_contact(){
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
			'reset_action_contact':'action_contact_reset',
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
		if(isset($_POST['reset_action_contact'])=="action_contact_reset") {
			
			
			$sahu_contact = serialize( array(
			'sahu_so_address' 		       => "123 Street, City",
			'sahu_so_no' 		       => "(00) 123-4567890",
			'sahu_so_email'    		   => "email@example.com",
			
			) );

			update_option('sahu_so_contact', $sahu_contact);
			
		}
	}
}
 ?>