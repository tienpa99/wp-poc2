<?php
/**
 * Event Tickets Emails: Order Attendee Name
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/tickets/v2/emails/template-parts/body/order/attendees-table/attendee-name.php
 *
 * See more documentation about our views templating system.
 *
 * @link https://evnt.is/tickets-emails-tpl Help article for Tickets Emails template files.
 *
 * @version 5.5.11
 *
 * @since 5.5.11
 *
 * @var Tribe_Template  $this  Current template object.
 * @var array           $order                 [Global] The order object.
 * @var bool            $is_tec_active         [Global] Whether `The Events Calendar` is active or not.
 */

if ( empty( $attendee['name'] ) ) {
	return;
}

?>
<div>
	<?php echo esc_html( $attendee['name'] ); ?>
</div>
