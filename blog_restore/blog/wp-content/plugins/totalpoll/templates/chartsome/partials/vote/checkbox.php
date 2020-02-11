<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-choice-checkbox-container">
	<?php echo $this->choice_input( $choice )->attribute( 'class', 'totalpoll-choice-checkbox' ); ?>
</div>