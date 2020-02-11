<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content" data-tp-tab-content="<?php echo $custom_field_id; ?>-html">

	<div class="settings-item">

		<div class="settings-field">

			<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-field-class"><?php _e( 'Field CSS classes', TP_TD ); ?></label>
			<input
				id="<?php echo $custom_field_id; ?>-field-class"
				class="widefat text-field"
				type="text"
				placeholder="<?php _e( 'Field classes', TP_TD ); ?>"
				name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][class]"
				data-rename="totalpoll[settings][fields][{{new-index}}][class]"
				value="<?php echo isset( $custom_field['class'] ) ? esc_attr( $custom_field['class'] ) : ''; ?>"
				>

		</div>
	</div>

	<div class="settings-item">

		<div class="settings-field">

			<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-label-class"><?php _e( 'Label CSS classes', TP_TD ); ?></label>
			<input
				id="<?php echo $custom_field_id; ?>-label-class"
				class="widefat text-field"
				type="text"
				placeholder="<?php _e( 'Label classes', TP_TD ); ?>"
				name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][label][attributes][class]"
				data-rename="totalpoll[settings][fields][{{new-index}}][label][attributes][class]"
				value="<?php echo isset( $custom_field['label']['attributes']['class'] ) ? esc_attr( $custom_field['label']['attributes']['class'] ) : ''; ?>"
				>

		</div>

	</div>

	<div class="settings-item">

		<div class="settings-field">

			<label class="settings-field-label" for="<?php echo $custom_field_id; ?>-template"><?php _e( 'Template', TP_TD ); ?></label>
			<input
				id="<?php echo $custom_field_id; ?>-template"
				class="widefat text-field"
				type="text"
				placeholder="<?php _e( 'Field template', TP_TD ); ?>"
				name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][template]"
				data-rename="totalpoll[settings][fields][{{new-index}}][template]"
				value="<?php echo isset( $custom_field['template'] ) ? esc_attr( $custom_field['template'] ) : ''; ?>"
				>

			<p class="totalpoll-feature-tip"><?php _e( '%label% for field label.' ); ?></p>

			<p class="totalpoll-feature-tip"><?php _e( '%field% for field input.' ); ?></p>


		</div>

	</div>

</div>