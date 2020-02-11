<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-general active" data-tp-tab-content="general">

	<div class="settings-field">
		<label>
			<input type="checkbox" name="totalpoll[options][general][async][enabled]" <?php checked( empty( $this->options['general']['async']['enabled'] ), false ); ?>>
			<?php _e( 'Asynchronous loading', TP_TD ); ?>
		</label>

		<p class="totalpoll-feature-tip"><?php _e( "This can be useful when TotalPoll is being used with cache plugins.", TP_TD ); ?></p>
	</div>

	<div class="settings-field">
		<label>
			<input type="checkbox" name="totalpoll[options][general][rest-api][enabled]" <?php checked( empty( $this->options['general']['rest-api']['enabled'] ), false ); ?>>
			<?php _e( 'Enable REST API (Preview)', TP_TD ); ?>
		</label>

		<p class="totalpoll-feature-tip"><?php _e( 'Access your polls data through an easy-to-use <a href="http://v2.wp-api.org/">HTTP REST API</a>.', TP_TD ); ?></p>
	</div>

</div>