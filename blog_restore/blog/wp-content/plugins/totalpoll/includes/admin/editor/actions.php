<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<input class="widefat" style="padding: 0.5rem;" onfocus="this.setSelectionRange(11, this.value.length)" onclick="this.onfocus()" onkeydown="return false;" type="text" value="<?php echo esc_attr( __( 'Shortcode', TP_TD ) . ": [totalpoll id=\"{$this->poll->id()}\"]" ); ?>">
<hr>