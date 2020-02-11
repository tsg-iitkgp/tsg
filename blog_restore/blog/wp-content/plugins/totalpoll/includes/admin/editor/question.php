<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-question-wrapper">
	<h2 class="totalpoll-h2"><?php _e( 'Question', TP_TD ); ?></h2>
	<input class="widefat" type="text" name="totalpoll[question]" value="<?php echo esc_attr( $this->poll->question() ); ?>" placeholder="<?php esc_attr_e( 'Question', TP_TD ); ?>">
</div>