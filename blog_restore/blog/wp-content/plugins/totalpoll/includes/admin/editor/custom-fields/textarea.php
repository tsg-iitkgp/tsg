<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<li class="totalpoll-containable" data-tp-containable="<?php echo $custom_field_id; ?>">

	<?php
	$custom_field_type           = 'textarea';
	$custom_field_type_label     = __( 'Text area', TP_TD );
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

							<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-default"><?php _e( 'Default value', TP_TD ); ?></label>
							<input
								id="<?php echo $custom_field_id; ?>-default"
								class="widefat text-field"
								type="text"
								placeholder="<?php _e( 'Field default', TP_TD ); ?>"
								name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][default]"
								data-rename="totalpoll[settings][fields][{{new-index}}][default]"
								value="<?php echo isset( $custom_field['default'] ) ? esc_attr( $custom_field['default'] ) : ''; ?>"
								>

						</div>

					</div>

					<div class="settings-item">

						<div class="settings-field">

							<label class="settings-field-rows" for="<?php echo $custom_field_id; ?>-rows"><?php _e( 'Rows', TP_TD ); ?></label>
							<input
								id="<?php echo $custom_field_id; ?>-rows"
								class="widefat text-field"
								type="number"
								placeholder="<?php _e( 'Field rows', TP_TD ); ?>"
								name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][extra][rows]"
								data-rename="totalpoll[settings][fields][{{new-index}}][extra][rows]"
								min="1"
								step="1"
								value="<?php echo isset( $custom_field['extra']['rows'] ) ? esc_attr( $custom_field['extra']['rows'] ) : ''; ?>"
								>

						</div>

					</div>

				</div>
				<div class="totalpoll-tab-content" data-tp-tab-content="<?php echo $custom_field_id; ?>-validations">

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

					<div class="settings-item">

						<div class="settings-field">
							<label>
								<input
									type="checkbox"
									name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][validations][filter][enabled]"
									data-rename="totalpoll[settings][fields][{{new-index}}][validations][filter][enabled]"
									data-tp-toggle="fields-validations-<?php echo $custom_field_id; ?>-filter-advanced"
									<?php checked( empty( $custom_field['validations']['filter']['enabled'] ), false ); ?>
									>
								<?php _e( 'Filter by list', TP_TD ); ?>
							</label>
						</div>

					</div>

					<div class="settings-item-advanced <?php echo empty( $custom_field['validations']['filter']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="fields-validations-<?php echo $custom_field_id; ?>-filter-advanced">

						<div class="settings-field">
							<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-">
								<?php _e( 'List', TP_TD ); ?>
								<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'Run user input against the following list.', TP_TD ); ?>">?</span>
							</label>
							<textarea
								id="<?php echo $custom_field_id; ?>-validations-filter-list"
								name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][validations][filter][list]"
								data-rename="totalpoll[settings][fields][{{new-index}}][validations][filter][list]"
								class="settings-field-input widefat"
								rows="6"><?php echo esc_textarea( $custom_field['validations']['filter']['list'] ); ?></textarea>

							<p class="totalpoll-feature-tip"><?php _e( 'Word per line.' ); ?></p>

							<p class="totalpoll-feature-tip"><?php _e( '"+" before word means white-listed.' ); ?></p>

							<p class="totalpoll-feature-tip"><?php _e( '"-" before word means black-listed.' ); ?></p>

							<p class="totalpoll-feature-tip"><?php _e( '"*" means wildcard.' ); ?></p>
						</div>

					</div>

					<div class="settings-item">

						<div class="settings-field">
							<label>
								<input
									type="checkbox"
									name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][validations][regex][enabled]"
									data-rename="totalpoll[settings][fields][{{new-index}}][validations][regex][enabled]"
									data-tp-toggle="fields-validations-<?php echo $custom_field_id; ?>-regex-advanced"
									<?php checked( empty( $custom_field['validations']['regex']['enabled'] ), false ); ?>
									>
								<?php _e( 'Regex', TP_TD ); ?>
							</label>
						</div>

					</div>

					<div class="settings-item-advanced <?php echo empty( $custom_field['validations']['regex']['enabled'] ) ? '' : 'active'; ?>" data-tp-toggleable="fields-validations-<?php echo $custom_field_id; ?>-regex-advanced">

						<div class="settings-field">
							<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-validations-regex-against">
								<?php _e( 'Regular expression', TP_TD ); ?>
								<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'Run user input against a regular expression.', TP_TD ); ?>">?</span>
							</label>
							<input
								id="<?php echo $custom_field_id; ?>-validations-regex-against"
								type="text"
								name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][validations][regex][against]"
								data-rename="totalpoll[settings][fields][{{new-index}}][validations][regex][against]"
								value="<?php esc_attr_e( $custom_field['validations']['regex']['against'] ); ?>"
								class="settings-field-input widefat"
								>

							<p class="totalpoll-feature-tip"><?php _e( 'Must be a valid regular expression.' ); ?></p>
						</div>

						<div class="settings-field">
							<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-validations-error-message">
								<?php _e( 'Error message', TP_TD ); ?>
								<span class="totalpoll-feature-details" title="<?php esc_attr_e( 'This message will be shown if the validation failed.', TP_TD ); ?>">?</span>
							</label>
							<input
								id="<?php echo $custom_field_id; ?>-validations-error-message"
								type="text"
								name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][validations][regex][message]"
								data-rename="totalpoll[settings][fields][{{new-index}}][validations][regex][message]"
								value="<?php esc_attr_e( $custom_field['validations']['regex']['message'] ); ?>"
								class="settings-field-input widefat"
							>
						</div>

						<div class="settings-field">
							<label class="settings-field-label">
								<?php _e( 'Comparison', TP_TD ); ?>
							</label>

							<label>
								<input
									type="radio"
									name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][validations][regex][type]"
									data-rename="totalpoll[settings][fields][{{new-index}}][validations][regex][type]"
									<?php checked( $custom_field['validations']['regex']['type'], 'match' ); ?>
									value="match"
									>
								<?php _e( 'Must match', TP_TD ); ?>
							</label>
							&nbsp;
							<label>
								<input
									type="radio"
									name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][validations][regex][type]"
									data-rename="totalpoll[settings][fields][{{new-index}}][validations][regex][type]"
									<?php checked( $custom_field['validations']['regex']['type'], 'notmatch' ); ?>
									value="notmatch"
									>
								<?php _e( 'Must not match', TP_TD ); ?>
							</label>
						</div>

					</div>

				</div>
				<?php include 'html-fields.php'; ?>
				<?php include 'statistics-fields.php'; ?>
			</div>
		</div>

</li>