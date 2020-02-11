<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<li class="totalpoll-containable" data-tp-containable="<?php echo $custom_field_id; ?>">

	<?php
	$custom_field_type           = 'radio';
	$custom_field_type_label     = __( 'Radio', TP_TD );
	$custom_field['validations'] = empty( $custom_field['validations'] ) ? array() : $custom_field['validations'];
	include 'handle.php';
	?>

	<div class="totalpoll-containable-content with-tabs">
		<?php
		include 'hidden-fields.php';
		?>

		<div class="totalpoll-tabs-container">
			<?php include 'tabs.php'; ?>
			<div class="totalpoll-tabs-content" data-tp-tabs-content>
				<div class="totalpoll-tab-content active" data-tp-tab-content="<?php echo $custom_field_id; ?>-basic">

					<div class="settings-item">

						<div class="settings-field">

							<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-name"><?php _e( 'Name', TP_TD ); ?></label>
							<input
								id="<?php echo $custom_field_id; ?>-name"
								class="widefat text-field"
								type="text"
								placeholder="<?php _e( 'Field name', TP_TD ); ?>"
								name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][name]"
								data-rename="totalpoll[settings][fields][{{new-index}}][name]"
								value="<?php echo isset( $custom_field['name'] ) ? esc_attr( $custom_field['name'] ) : ''; ?>"
								data-tp-containable-preview-field
								>

						</div>

					</div>

					<div class="settings-item">

						<div class="settings-field">

							<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-label"><?php _e( 'Label', TP_TD ); ?></label>
							<input
								id="<?php echo $custom_field_id; ?>-label"
								class="widefat text-field"
								type="text"
								placeholder="<?php _e( 'Field label', TP_TD ); ?>"
								name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][label][content]"
								data-rename="totalpoll[settings][fields][{{new-index}}][label][content]"
								value="<?php echo isset( $custom_field['label']['content'] ) ? esc_attr( $custom_field['label']['content'] ) : ''; ?>"
								>

						</div>

					</div>

					<div class="settings-item">

						<div class="settings-field">

							<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-options"><?php _e( 'Options', TP_TD ); ?></label>
							<textarea
								id="<?php echo $custom_field_id; ?>-options"
								class="widefat text-field"
								placeholder="<?php _e( 'option_key : Option label', TP_TD ); ?>"
								name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][extra][options]"
								data-rename="totalpoll[settings][fields][{{new-index}}][extra][options]"
								rows="6"
								><?php echo isset( $custom_field['extra']['options'] ) ? esc_textarea( $custom_field['extra']['options'] ) : ''; ?></textarea>

							<p class="totalpoll-feature-tip"><?php _e( 'Option per line.' ); ?></p>

						</div>

					</div>

					<div class="settings-item">

						<div class="settings-field">

							<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-default"><?php _e( 'Default values', TP_TD ); ?></label>
							<input
								id="<?php echo $custom_field_id; ?>-default"
								class="widefat text-field"
								type="text"
								placeholder="<?php _e( 'option_key', TP_TD ); ?>"
								name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][default]"
								data-rename="totalpoll[settings][fields][{{new-index}}][default]"
								value="<?php echo isset( $custom_field['default'] ) ? esc_textarea( $custom_field['default'] ) : ''; ?>"
								>

						</div>

					</div>

				</div>
				<div class="totalpoll-tab-content" data-tp-tab-content="<?php echo $custom_field_id; ?>-validations">

					<input
						type="hidden"
						name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][validations][options][enabled]"
						data-rename="totalpoll[settings][fields][{{new-index}}][validations][options][enabled]"
						value="1"
						>

					<div class="settings-item">

						<div class="settings-field">
							<label>
								<input
									type="checkbox"
									name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][validations][filled][enabled]"
									data-rename="totalpoll[settings][fields][{{new-index}}][validations][filled][enabled]"
									<?php checked( empty( $custom_field['validations']['filled']['enabled'] ), false ); ?>
									>
								<?php _e( 'Filled (required)', TP_TD ); ?>
							</label>
						</div>

					</div>

				</div>
				<?php include 'html-fields.php'; ?>
				<?php include 'statistics-fields.php'; ?>
			</div>
		</div>

</li>