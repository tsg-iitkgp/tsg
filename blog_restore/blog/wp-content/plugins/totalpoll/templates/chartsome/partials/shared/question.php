<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<h4 data-tp-question class="totalpoll-question" itemprop="name">
	<?php echo $this->poll->question(); ?>
</h4>