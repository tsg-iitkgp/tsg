<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-logs" data-tp-tab-content="logs">

	<?php do_action( 'totalpoll/actions/admin/editor/settings/logs/before', $logs, $this->poll ); ?>

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][logs][enabled]" <?php checked( empty( $logs['enabled'] ), false ); ?>>
				<?php _e( 'Enable logs', TP_TD ); ?>
			</label>

			<p class="totalpoll-feature-tip"><?php _e( 'Heads up! Statistics are based on logs.', TP_TD ); ?></p>
		</div>
		<?php do_action( 'totalpoll/actions/admin/editor/settings/logs/enable-logs', $logs, $this->poll ); ?>

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][logs][details][user_id][enabled]" <?php checked( empty( $logs['details']['user_id']['enabled'] ), false ); ?>>
				<?php _e( 'Include user ID', TP_TD ); ?>
			</label>
		</div>
		
		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][logs][details][user_login][enabled]" <?php checked( empty( $logs['details']['user_login']['enabled'] ), false ); ?>>
				<?php _e( 'Include username', TP_TD ); ?>
			</label>
		</div>

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[settings][logs][details][user_email][enabled]" <?php checked( empty( $logs['details']['user_email']['enabled'] ), false ); ?>>
				<?php _e( 'Include user email', TP_TD ); ?>
			</label>
		</div>


	</div>

	<?php do_action( 'totalpoll/actions/admin/editor/settings/logs/after', $logs, $this->poll ); ?>

</div>