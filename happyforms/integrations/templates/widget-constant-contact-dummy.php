<form class="happyforms-service">
	<div class="widget-content">
		<div id="happyforms-service-active-campaign" class="happyforms-service-integration">
			<div class="widget-content">
				<div class="happyforms-clipboard-field">
					<label for="constant-contact-redirect-uri"><?php _e( 'Redirect URI', 'happyforms' ); ?></label>
					<div class="happyforms-clipboard-field-input-wrapper">
						<input type="text" name="constant-contact-redirect-uri" readonly value="" />
						<div class="happyforms-clipboard">
							<button type="button" class="button happyforms-clipboard__button" data-value=""><?php _e( 'Copy to clipboard', 'happyforms' ); ?></button>
							<span aria-hidden="true" class="hidden"><?php _e( 'Copied!', 'happyforms' ); ?></span>
						</div>
					</div>
				</div>
				<label for=""><?php _e( 'API URL', 'happyforms' ); ?></label>
				<div class="hf-pwd">
					<input type="password" class="widefat happyforms-credentials-input connected" id="" name="" value="" />
					<button type="button" class="button button-secondary hf-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-show="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-hide="<?php _e( 'Hide credentials', 'happyforms' ); ?>">
						<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
					</button>
				</div>
				<label for=""><?php _e( 'API key', 'happyforms' ); ?></label>
				<div class="hf-pwd">
					<input type="password" class="widefat happyforms-credentials-input connected" id="" name="" value="" />
					<button type="button" class="button button-secondary hf-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-show="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-hide="<?php _e( 'Hide credentials', 'happyforms' ); ?>">
						<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		</div>
		<div class="widget-control-actions">
			<div class="alignleft">
				<input type="submit" class="connected button button-primary widget-control-save right" value="<?php _e( 'Save Changes', 'happyforms' ); ?>">
			</div>
			<br class="clear" />
		</div>
	</div>
</form>
