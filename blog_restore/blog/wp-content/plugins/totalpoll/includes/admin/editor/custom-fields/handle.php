<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-containable-handle" data-tp-containable-handle="<?php echo $custom_field_id; ?>">
	<?php echo $custom_field_type_label; ?>: <span class="totalpoll-containable-handle-title" data-tp-containable-preview="<?php echo $custom_field_id; ?>"></span>
	<button class="button button-small totalpoll-containable-remove" type="button" data-tp-containable-remove="<?php echo $custom_field_id; ?>"><?php _e( 'Remove', TP_TD ); ?></button>
</div>