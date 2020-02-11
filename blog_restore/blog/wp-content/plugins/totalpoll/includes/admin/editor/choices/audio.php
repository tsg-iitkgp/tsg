<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<li class="totalpoll-containable <?php echo isset( $choice_css_class ) ? esc_attr( $choice_css_class ) : ''; ?>" data-tp-containable="<?php echo $choice_id; ?>">

	<?php
	$choice_type       = 'audio';
	$choice_type_label = __( 'Audio', TP_TD );
	include 'handle.php';
	?>

	<div class="totalpoll-containable-content">
		<?php
		include 'hidden-fields.php';
		?>
		<input
			id="<?php echo $choice_id; ?>-media-id"
			type="hidden"
			name="totalpoll[choices][<?php echo $choice_index; ?>][content][audio][id]"
			data-rename="totalpoll[choices][{{new-index}}][content][audio][id]"
			value="<?php echo isset( $choice['content']['audio']['id'] ) ? $choice['content']['audio']['id'] : ''; ?>"
		>
		<input
			id="<?php echo $choice_id; ?>-thumbnail-media-id"
			type="hidden"
			name="totalpoll[choices][<?php echo $choice_index; ?>][content][thumbnail][id]"
			data-rename="totalpoll[choices][{{new-index}}][content][thumbnail][id]"
			value="<?php echo isset( $choice['content']['thumbnail']['id'] ) ? $choice['content']['thumbnail']['id'] : ''; ?>"
		>

		<div class="field-wrapper">
			<label for="<?php echo $choice_id; ?>-label"><?php _e( 'Label', TP_TD ); ?></label>
			<input
				id="<?php echo $choice_id; ?>-label"
				class="widefat text-field"
				type="text"
				placeholder="<?php _e( 'Choice label', TP_TD ); ?>"
				name="totalpoll[choices][<?php echo $choice_index; ?>][content][label]"
				data-rename="totalpoll[choices][{{new-index}}][content][label]"
				value="<?php echo isset( $choice['content']['label'] ) ? $choice['content']['label'] : ''; ?>"
				data-tp-containable-field="<?php echo $choice_id; ?>"
				data-tp-containable-preview-field
				data-tp-containable-media-label-field
				x-webkit-speech
			>
		</div>
		<div class="field-wrapper">
			<label for="<?php echo $choice_id; ?>-full-audio-url"><?php _e( 'Full audio url', TP_TD ); ?></label>

			<div class="field-row">
				<div class="field-column">
					<input
						id="<?php echo $choice_id; ?>-full-audio-url"
						class="widefat text-field"
						type="text"
						placeholder="<?php _e( 'Choice full audio url', TP_TD ); ?>"
						name="totalpoll[choices][<?php echo $choice_index; ?>][content][audio][url]"
						data-rename="totalpoll[choices][{{new-index}}][content][audio][url]"
						value="<?php echo isset( $choice['content']['audio']['url'] ) ? $choice['content']['audio']['url'] : ''; ?>"
						data-tp-containable-field="<?php echo $choice_id; ?>"
					>
				</div>
				<div class="field-column fifth">
					<button
						class="button widefat"
						type="button"
						data-tp-containable-upload
						data-tp-containable-upload-type="audio"
						data-tp-containable-upload-field-id="#<?php echo $choice_id; ?>-media-id"
						data-tp-containable-upload-field-label="#<?php echo $choice_id; ?>-label"
						data-tp-containable-upload-field-full="#<?php echo $choice_id; ?>-full-audio-url"
					><?php _e( 'Upload', TP_TD ); ?></button>
				</div>
			</div>
		</div>
		<div class="field-wrapper">
			<div class="field-row">
				<div class="field-column">
					<label for="<?php echo $choice_id; ?>-thumbnail-audio-url"><?php _e( 'Thumbnail audio url', TP_TD ); ?></label>
					<input
						id="<?php echo $choice_id; ?>-thumbnail-audio-url"
						class="widefat text-field"
						type="text"
						placeholder="<?php _e( 'Choice thumbnail audio url', TP_TD ); ?>"
						name="totalpoll[choices][<?php echo $choice_index; ?>][content][thumbnail][url]"
						data-rename="totalpoll[choices][{{new-index}}][content][thumbnail][url]"
						value="<?php echo isset( $choice['content']['thumbnail']['url'] ) ? $choice['content']['thumbnail']['url'] : ''; ?>"
						data-tp-containable-field="<?php echo $choice_id; ?>"
					>
				</div>
				<div class="field-column fifth">
					<label for="<?php echo $choice_id; ?>-available-sizes"><?php _e( 'Available sizes', TP_TD ); ?></label>
					<select
						id="<?php echo $choice_id; ?>-available-sizes"
						class="widefat text-field"
						data-tp-containable-media-sizes>
						<?php
						if ( ! empty( $choice['content']['thumbnail']['id'] ) ):
							$meta_data = wp_prepare_attachment_for_js( $choice['content']['thumbnail']['id'] );
						endif;

						if ( empty( $meta_data ) ):
							printf( '<option disabled>%s</option>', __( 'Upload image first', TP_TD ) );
						else:
							foreach ( $meta_data['sizes'] as $size => $size_data ):
								$selected = isset( $choice['content']['thumbnail']['url'] ) && $choice['content']['thumbnail']['url'] == $size_data['url'];
								printf( '<option value="%s" %s>%s</option>', $size_data['url'], $selected ? 'selected' : '', $size );
							endforeach;
						endif;

						unset( $meta_data );
						?>

					</select>
				</div>
				<div class="field-column fifth">
					<label>&nbsp;</label>
					<button
						class="button widefat"
						type="button"
						data-tp-containable-upload
						data-tp-containable-upload-type="image"
						data-tp-containable-upload-field-id="#<?php echo $choice_id; ?>-thumbnail-media-id"
						data-tp-containable-upload-field-label="#<?php echo $choice_id; ?>-label"
						data-tp-containable-upload-field-thumbnail="#<?php echo $choice_id; ?>-thumbnail-audio-url"
						data-tp-containable-upload-field-sizes="#<?php echo $choice_id; ?>-available-sizes"
					><?php _e( 'Upload', TP_TD ); ?></button>
				</div>
			</div>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/choice/audio-fields', $choice_index, $choice_id, $choice_type, $choice ); ?>
	</div>

</li>