<form class="happyforms-service hf-ajax-submit">
	<div class="widget-content has-service-selection">
		<div class="mode-group">
 			<label><?php _e( 'Mode', 'happyforms' ); ?></label>
 			<div class="happyforms-buttongroup">
 				<label>
 					<input type="radio" checked disabled />
 					<span><?php _e( 'Live', 'happyforms' ); ?></span>
 				</label>
 				<label>
 					<input type="radio" disabled />
 					<span><?php _e( 'Test', 'happyforms' ); ?></span>
 				</label>
 			</div>
 		</div>

		<div id="happyforms-service-stripe" class="happyforms-service-integration">
			<div class="widget-content">
				<label for=""><?php _e( 'Publishable key', 'happyforms' ); ?></label>
				<div class="hf-pwd">
					<input type="password" class="widefat happyforms-credentials-input connected" id="" name="" value="" />
					<button type="button" class="button button-secondary hf-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-show="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-hide="<?php _e( 'Hide credentials', 'happyforms' ); ?>">
						<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
					</button>
				</div>
				<label for=""><?php _e( 'Secret key', 'happyforms' ); ?></label>
				<div class="hf-pwd">
					<input type="password" class="widefat happyforms-credentials-input connected" id="" name="" value="" />
					<button type="button" class="button button-secondary hf-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-show="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-hide="<?php _e( 'Hide credentials', 'happyforms' ); ?>">
						<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
					</button>
				</div>
				<div class="happyforms-clipboard-field">
					<label for="credentials[stripe][webhook_endpoint_url]"><?php _e( 'Webhook endpoint URL', 'happyforms' ); ?></label>
					<div class="happyforms-clipboard-field-input-wrapper">
						<input type="text" readonly value="" />
						<div class="happyforms-clipboard">
							<button type="button" class="button happyforms-clipboard__button" data-value=""><?php _e( 'Copy to clipboard', 'happyforms' ); ?></button>
							<span aria-hidden="true" class="hidden"><?php _e( 'Copied!', 'happyforms' ); ?></span>
						</div>
					</div>
				</div>
				<div class="happyforms-stripe-webhook-endpoint-secret-key happyforms-stripe-webhook-endpoint-secret-key-live">
					<label for=""><?php _e( 'Webhook endpoint secret key', 'happyforms' ); ?></label>
					<div class="hf-pwd">
						<input type="password" class="widefat happyforms-credentials-input connected" id="" name="" value="" />
						<button type="button" class="button button-secondary hf-hide-pw hide-if-no-js" data-toggle="0" aria-label="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-show="<?php _e( 'Show credentials', 'happyforms' ); ?>" data-label-hide="<?php _e( 'Hide credentials', 'happyforms' ); ?>">
							<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
						</button>
					</div>
				</div>
			</div>
		</div>

		<div class="widget-control-actions">
			<div class="alignleft">
				<span class="spinner"></span>
				<input type="submit" class="connected button button-primary widget-control-save right" value="<?php _e( 'Save Changes', 'happyforms' ); ?>">
			</div>
			<br class="clear" />
		</div>
	</div>
</form>

