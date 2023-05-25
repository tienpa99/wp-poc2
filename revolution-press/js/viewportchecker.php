<?php if( ! defined( 'ABSPATH' ) ) exit;

function revolution_press_animation_classes () { ?>
	<script>

	<?php if ( get_theme_mod('revolution_press_animation_content','zoomIn')) { ?>
		jQuery("body").ready(function() {
				jQuery('article').addClass("hidden").viewportChecker({
					classToAdd: 'animated <?php echo esc_html( get_theme_mod('revolution_press_animation_content','zoomIn') ); ?>', // Class to add to the elements when they are visible
					offset: 0  
				   }); 
		});  
	<?php } ?>
	
	
	</script>
<?php } 

add_action('wp_footer', 'revolution_press_animation_classes');				   
				   
		
		