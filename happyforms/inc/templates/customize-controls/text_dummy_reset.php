<div class="customize-control customize-control-number customize-control-text_dummy_reset input_dummy" id="customize-control-<?php echo $control['dummy_id']; ?>">
    <label for="max_entries" class="customize-control-title">
      <?php echo $control['label']; ?> <span class="members-only"><?php _e( 'Upgrade', 'happyforms') ?></span>
    </label>
	<div class="customize-control-reset-wrap" data-pointer-target>
    <input id="error_message" type="text" placeholder="<?php echo ( isset( $control['placeholder'] ) ) ? $control['placeholder'] : ''; ?>"/>
    <button type="button" class="reset-default button button-secondary" data-default="" disabled>Reset</button>
	</div>
</div>
