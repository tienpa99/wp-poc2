<?php
/**
 *
 * Part loop
 *
 */
?>

<?php $message = array( 'parts' => $response ); ?>

<?php foreach( $form['parts'] as $part ) : ?>
	<?php if ( happyforms_email_is_part_visible( $part, $form, $message ) ) : ?>

	<b><?php echo happyforms_get_email_part_label( $message, $part, $form ); ?></b><br>
	<?php echo happyforms_get_email_part_value( $message, $part, $form ); ?>
	<br><br>

	<?php endif; ?>

<?php endforeach; ?>

<?php

/**
 *
 * User data
 *
 */

if ( happyforms_get_form_property( $form, 'include_submitters_ip' ) ) : ?>

	<b><?php _e( 'IPv4/IPv6', 'happyforms' ); ?></b><br>
	<?php echo $ip_address; ?>
	<br><br>

<?php endif; ?>

<?php if ( happyforms_get_form_property( $form, 'include_referral_link' ) ): ?>
	<b><?php _e( 'Referral', 'happyforms' ); ?></b><br><a href="<?php echo $client_referer; ?>"><?php echo $client_referer; ?></a>
	<br><br>
<?php endif; ?>

<?php if ( happyforms_get_form_property( $form, 'include_submission_date_time' ) ) : ?>

		<?php
		$submitted = sprintf(
			__( '%1$s UTC%2$s', 'happyforms' ),
			date_i18n( __( 'M j, Y g:i a' ) ),
			date_i18n( __( 'P' ) )
		);
		?>

		<b><?php _e( 'Date and time', 'happyforms' ); ?></b><br><?php echo $submitted; ?><br>
		<br><br>

<?php endif; ?>

<?php do_action( 'happyforms_email_owner_after' ); ?>
