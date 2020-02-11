<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-notifications" data-tp-tab-content="notifications">

	<?php do_action( 'totalpoll/actions/admin/editor/settings/notifications/before', $logs, $this->poll ); ?>

	<div class="settings-item">
		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-notifications-email">
				<?php _e( 'Email', TP_TD ); ?>
			</label>
			<input id="totalpoll-settings-notifications-email" type="text" name="totalpoll[settings][notifications][email]" class="settings-field-input widefat" value="<?php echo esc_attr( $notifications['email'] ); ?>">
		</div>
	</div>

	<div class="settings-item">
		<label class="settings-field-label">
			Email me when
		</label>

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][notifications][triggers][new_vote][enabled]" <?php checked( empty( $notifications['triggers']['new_vote']['enabled'] ), false ); ?>>
				<?php _e( 'New vote has been casted', TP_TD ); ?>
			</label>
		</div>
	</div>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/notifications/after', $logs, $this->poll ); ?>

</div>