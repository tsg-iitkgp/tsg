<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-tabs" data-tp-tabs>
	<a href="#" class="active" data-tp-tab="<?php echo $custom_field_id; ?>-basic"><?php _e( 'Basic', TP_TD ); ?></a>
	<a href="#" data-tp-tab="<?php echo $custom_field_id; ?>-validations"><?php _e( 'Validations', TP_TD ); ?></a>
	<a href="#" data-tp-tab="<?php echo $custom_field_id; ?>-html"><?php _e( 'HTML', TP_TD ); ?></a>
	<a href="#" data-tp-tab="<?php echo $custom_field_id; ?>-statistics"><?php _e( 'Statistics', TP_TD ); ?></a>
</div>