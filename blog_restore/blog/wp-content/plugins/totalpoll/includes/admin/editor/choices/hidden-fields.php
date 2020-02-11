<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<input
	type="hidden"
	name="totalpoll[choices][<?php echo $choice_index; ?>][content][type]"
	data-rename="totalpoll[choices][{{new-index}}][content][type]"
	value="<?php echo $choice_type; ?>"
>
<input
	type="hidden"
	name="totalpoll[choices][<?php echo $choice_index; ?>][last_votes]"
	data-rename="totalpoll[choices][{{new-index}}][last_votes]"
	value="<?php echo $choice['votes']; ?>"
>
<input
	type="hidden"
	name="totalpoll[choices][<?php echo $choice_index; ?>][content][date]"
	data-rename="totalpoll[choices][{{new-index}}][content][date]"
	value="<?php echo $choice['content']['date']; ?>"
>
<input
	type="hidden" name="totalpoll[choices][<?php echo $choice_index; ?>][index]"
	data-rename="totalpoll[choices][{{new-index}}][index]"
	value="<?php echo $choice_index; ?>"
>
<?php do_action( 'totalpoll/actions/admin/editor/choice/hidden-fields', $choice_index, $choice_id, $choice_type, $choice ); ?>