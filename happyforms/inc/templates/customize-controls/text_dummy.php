<div class="customize-control customize-control-number customize-control-text_dummy input_dummy" id="customize-control-<?php echo $control['dummy_id']; ?>">
	<div class="customize-inside-control-row" data-pointer-target>
    <label for="max_entries" class="customize-control-title">
      <?php echo $control['label']; ?> <span class="members-only"><?php _e( 'Upgrade', 'happyforms') ?></span>
    </label>
		<input type="text" placeholder="<?php echo ( isset( $control['placeholder'] ) ) ? $control['placeholder'] : ''; ?>"/>
	</div>
</div>
