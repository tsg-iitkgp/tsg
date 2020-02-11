<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>

<?php foreach ( $this->poll->limitations()->errors() as $error ): ?>
	<div class="totalpoll-error-message">
		<p><?php echo $error; ?></p>
	</div>
<?php endforeach; ?>