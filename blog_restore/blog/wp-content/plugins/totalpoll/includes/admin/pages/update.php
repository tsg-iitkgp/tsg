<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

$key = get_option( 'totalpoll_license_key', false );

if ( isset( $_POST['totalpoll_license_key'] ) ):
	$status = $this->activate( $_POST['totalpoll_license_key'] );
else:
	$status = get_option( 'totalpoll_license_status', false );
endif;

?>
<div id="totalpoll-activation" class="totalpoll-page wrap">

	<form method="post" class="totalpoll-page-container">
		<?php if ( $status == true ): ?>
			<svg width="72" height="72" viewBox="0 0 24 24">
				<path fill="#9ebaa0" d="M20,12A8,8 0 0,1 12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4C12.76,4 13.5,4.11 14.2,4.31L15.77,2.74C14.61,2.26 13.34,2 12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12M7.91,10.08L6.5,11.5L11,16L21,6L19.59,4.58L11,13.17L7.91,10.08Z"/>
			</svg>
			<h1><?php _e( 'Thank you!', TP_TD ); ?></h1>

			<p><?php _e( 'You are currently receiving updates!', TP_TD ); ?></p>
			<p><?php _e( 'Thank you for activating TotalPoll.', TP_TD ); ?></p>

		<?php else: ?>
			<svg width="72" height="72" viewBox="0 0 24 24" fill="#dddddd">
				<path d="M12,6V9L16,5L12,1V4A8,8 0 0,0 4,12C4,13.57 4.46,15.03 5.24,16.26L6.7,14.8C6.25,13.97 6,13 6,12A6,6 0 0,1 12,6M18.76,7.74L17.3,9.2C17.74,10.04 18,11 18,12A6,6 0 0,1 12,18V15L8,19L12,23V20A8,8 0 0,0 20,12C20,10.43 19.54,8.97 18.76,7.74Z"/>
			</svg>
			<h1><?php _e( 'Updates', TP_TD ); ?></h1>

			<p><?php _e( 'Do you want to receive updates when released?', TP_TD ); ?></p>

			<p><?php _e( 'Please enter your Envato license key.', TP_TD ); ?></p>

			<input id="totalpoll-license-key" name="totalpoll_license_key" type="text" maxlength="36" placeholder="12345678-1234-1234-1234-123456789123" value="<?php echo esc_attr( $key ); ?>" class="input-key">

			<?php if ( isset( $_POST['totalpoll_license_key'] ) ): ?>
				<p class="invalid-key"><?php _e( "The submitted license key isn't valid. Please check it.", TP_TD ); ?></p>
			<?php endif; ?>

			<button class="button button-primary"><?php _e( 'Activate now!', TP_TD ); ?></button>
		<?php endif; ?>
	</form>
</div>