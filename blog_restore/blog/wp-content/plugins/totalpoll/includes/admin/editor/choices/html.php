<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<li class="totalpoll-containable <?php echo isset( $choice_css_class ) ? esc_attr( $choice_css_class ) : ''; ?>" data-tp-containable="<?php echo $choice_id; ?>">

	<?php
	$choice_type       = 'html';
	$choice_type_label = __( 'Rich', TP_TD );
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
			>
		</div>
		<div class="field-wrapper">
			<?php
			ob_start();
			wp_editor( isset( $choice['content']['html'] ) ? $choice['content']['html'] : '', 'totalpollTinyMceTemplate', array( 'textarea_name' => "totalpoll[choices][{$choice_index}][content][html]" ) );
			$editor = str_replace(
				array( 'totalpollTinyMceTemplate', 'name="totalpoll[choices][' . $choice_index . '][content][html]"' ),
				array( "tinymce-{$choice_id}", 'name="totalpoll[choices][' . $choice_index . '][content][html]" data-rename="totalpoll[choices][{{new-index}}][content][html]"' ),
				ob_get_clean()
			);
			echo $editor;
			?>
		</div>

		<?php do_action( 'totalpoll/actions/admin/editor/choice/html-fields', $choice_index, $choice_id, $choice_type, $choice ); ?>
	</div>

</li>