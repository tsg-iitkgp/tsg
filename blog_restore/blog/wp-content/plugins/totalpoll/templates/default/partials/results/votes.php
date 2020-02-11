<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div class="totalpoll-choice-votes">
	<div class="totalpoll-choice-votes-bar <?php echo $choice['votes%'] == 0 ? 'totalpoll-choice-votes-bar-0' : ''; ?>" style="width: <?php echo $choice['votes%']; ?>%;"></div>
	<div class="totalpoll-choice-votes-text" itemprop="upvoteCount">
		<span><?php echo $this->votes( $choice ); ?></span>
	</div>
</div>