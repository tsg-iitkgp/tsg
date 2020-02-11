<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tab-content" data-tp-tab-content="<?php echo $custom_field_id; ?>-statistics">

	<div class="settings-item">

		<div class="settings-field">
			<label>
				<input
					type="checkbox"
					name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][statistics][enabled]"
					data-rename="totalpoll[settings][fields][{{new-index}}][statistics][enabled]"
					<?php checked( empty( $custom_field['statistics']['enabled'] ), false ); ?>
					>
				<?php _e( 'Include in statistics', TP_TD ); ?>
			</label>
		</div>

	</div>

</div>