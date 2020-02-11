<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content settings-tab-content settings-sharing" data-tp-tab-content="sharing">

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input type="checkbox" name="totalpoll[options][sharing][enabled]" data-tp-toggle="sharing-advanced" <?php checked( empty( $this->options['sharing']['enabled'] ), false ); ?>>
				<?php _e( 'Enable sharing', TP_TD ); ?>
			</label>
		</div>

	</div>

	<div class="settings-item-advanced <?php echo empty( $this->options['sharing']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="sharing-advanced">

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-sharing-expression">
				<?php _e( 'Sharing expression', TP_TD ); ?>
			</label>
			<input id="totalpoll-settings-sharing-expression" type="text" name="totalpoll[options][sharing][expression]" class="settings-field-input widefat" value="<?php echo empty( $this->options['sharing']['expression'] ) ? '' : esc_attr( $this->options['sharing']['expression'] ); ?>">

			<p class="totalpoll-feature-tip"><?php _e( 'You can use %question% variable for question text.', TP_TD ); ?></p>
		</div>

		<div class="settings-field">
			<label class="settings-field-label" for="totalpoll-settings-sharing-networks">
				<?php _e( 'Social networks', TP_TD ); ?>
			</label>
			<select id="totalpoll-settings-sharing-networks" name="totalpoll[options][sharing][networks][]" class="settings-field-input widefat" multiple>
				<?php foreach ( array( 'facebook' => 'Facebook', 'twitter' => 'Twitter', 'googlePlus' => 'Google+', 'reddit' => 'Reddit', 'linkedin' => 'LinkedIn', 'email' => 'Email', 'whatsapp' => 'WhatsApp' ) as $network => $label ): ?>
					<option value="<?php echo esc_attr( $network ); ?>" <?php selected( in_array( $network, empty( $this->options['sharing']['networks'] ) ? array() : $this->options['sharing']['networks'] ), true ); ?>><?php echo $label; ?></option>
				<?php endforeach; ?>
			</select>

			<p class="totalpoll-feature-tip"><?php _e( 'Hold Control/Command for multiple selection.', TP_TD ); ?></p>
		</div>

	</div>

</div>