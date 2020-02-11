<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

foreach ( $this->poll->limitations()->errors() as $error ): ?>
	<div data-tp-errors class="totalpoll-error-message">
		<p><?php echo $error; ?></p>
	</div>
<?php endforeach; ?>