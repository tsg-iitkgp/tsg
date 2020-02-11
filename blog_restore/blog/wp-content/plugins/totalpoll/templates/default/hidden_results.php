<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<?php echo do_shortcode( wpautop( $this->poll->settings( 'results', 'hide', 'content' ) ) ); ?>