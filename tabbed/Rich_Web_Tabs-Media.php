<?php
	global $wpdb;
	$RWTabs_Manager_Table = $wpdb->prefix . "rich_web_tabs_manager_data";
	$RichWebTabsCount = $wpdb->get_results($wpdb->prepare("SELECT * FROM $RWTabs_Manager_Table WHERE id>%d order by id",0));
?>
<script type="text/javascript">
	jQuery(document).ready(function () {
		jQuery('#RW_Tabs_Media_Insert').on('click', function () {
			var rw_tabs_insert_id = jQuery('#RW_Tabs_Media_Select option:selected').val();
			window.send_to_editor('[Rich_Web_Tabs id="' + rw_tabs_insert_id + '"]');
			tb_remove();
			return false;
		});
	});
</script>
<form method="POST">
	<div id="RWTabs" style="display: none;">
		<?php
			$new_tabs_link = admin_url('admin.php?page=Rich_Web_Tabs_Admin');
			$new_tabs_link_n = wp_nonce_url( '', 'edit-menu_', 'Rich_Web_Tabs_Nonce' );
			if ($RichWebTabsCount && !empty($RichWebTabsCount)) { ?>
				<h3>Select Tab</h3>
				<select id="RW_Tabs_Media_Select">
					<?php
						foreach ($RichWebTabsCount as $RichWebTabsCount1)
						{
							?> <option value="<?php esc_attr_e($RichWebTabsCount1->id); ?>"> <?php esc_html_e($RichWebTabsCount1->Tabs_Name); ?> </option> <?php
						}
					?>
				</select>
				<button class='button primary' id='RW_Tabs_Media_Insert'>Insert Tab</button>
			<?php } else {
				printf('<p>%s<a class="button" href="%s">%s</a></p>', 'You have not created any tabs yet' . '<br>', $new_tabs_link . $new_tabs_link_n, 'Create New Tab');
			}
		?>
	</div>
</form>