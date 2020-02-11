<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<li class="totalpoll-containable <?php echo isset( $choice_css_class ) ? esc_attr( $choice_css_class ) : ''; ?>" data-tp-containable="<?php echo $choice_id; ?>">

	<?php
	$choice_type       = 'text';
	$choice_type_label = __( 'Text', TP_TD );
	include 'handle.php';
	?>

	<div class="totalpoll-containable-content">
		<?php
		include 'hidden-fields.php';
		?>
		<div class="field-wrapper">
			<label for="<?php echo $choice_id; ?>-label"><?php _e( 'Label', TP_TD ); ?></label>
			<input
				id="<?php echo $choice_id; ?>-label"
				class="widefat text-field"
				type="text"
				placeholder="<?php _e( 'Choice content', TP_TD ); ?>"
				name="totalpoll[choices][<?php echo $choice_index; ?>][content][label]"
				data-rename="totalpoll[choices][{{new-index}}][content][label]"
				value="<?php echo isset( $choice['content']['label'] ) ? $choice['content']['label'] : ''; ?>"
				data-tp-containable-field="<?php echo $choice_id; ?>"
				data-tp-containable-preview-field
				x-webkit-speech
			>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/choice/text-fields', $choice_index, $choice_id, $choice_type, $choice ); ?>
	</div>

</li>