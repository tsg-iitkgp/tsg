<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<input
	type="hidden"
	name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][type]"
	data-rename="totalpoll[settings][fields][{{new-index}}][type]"
	value="<?php echo $custom_field_type; ?>"
	>
<input
	type="hidden" name="totalpoll[settings][fields][<?php echo $custom_field_index; ?>][index]"
	data-rename="totalpoll[settings][fields][{{new-index}}][index]"
	value="<?php echo $custom_field_index; ?>"
	>