<p class="happyforms-buttongroup-wrapper">
	<label for="<%= instance.id %>_width"><?php _e( 'Width', 'happyforms' ); ?></label>
	<span class="happyforms-buttongroup happyforms-buttongroup-field_width">
		<label for="<%= instance.id %>_width_full">
			<input type="radio" id="<%= instance.id %>_width_full" value="full" name="<%= instance.id %>_width" data-bind="width" <%= ( instance.width == 'full' ) ? 'checked' : '' %> />
			<span><?php _e( 'Full', 'happyforms' ); ?></span>
		</label>
		<label for="<%= instance.id %>_width_half">
			<input type="radio" id="<%= instance.id %>_width_half" value="half" name="<%= instance.id %>_width" data-bind="width" <%= ( instance.width == 'half' ) ? 'checked' : '' %> />
			<span><?php _e( 'Half', 'happyforms' ); ?></span>
		</label>
		<label for="<%= instance.id %>_width_third">
			<input type="radio" id="<%= instance.id %>_width_third" value="third" name="<%= instance.id %>_width" data-bind="width" <%= ( instance.width == 'third' ) ? 'checked' : '' %>/>
			<span><?php _e( 'Third', 'happyforms' ); ?></span>
		</label>
		<label for="<%= instance.id %>_width_quarter">
			<input type="radio" id="<%= instance.id %>_width_quarter" value="quarter" data-bind="width" name="<%= instance.id %>_width" <%= ( instance.width == 'quarter' ) ? 'checked' : '' %>/>
			<span><?php _e( 'Quarter', 'happyforms' ); ?></span>
		</label>
		<label for="<%= instance.id %>_width_auto">
			<input type="radio" id="<%= instance.id %>_width_auto" value="auto" data-bind="width" name="<%= instance.id %>_width" <%= ( instance.width == 'auto' ) ? 'checked' : '' %> />
			<span><?php _e( 'Auto', 'happyforms' ); ?></span>
		</label>
	</span>
</p>
<p class="width-options" style="display: none">
	<label>
		<input type="checkbox" class="checkbox apply-all-check" value="" data-apply-to="width" /> <?php _e( 'Apply to all fields', 'happyforms' ); ?>
	</label>
</p>