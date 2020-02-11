<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh

/**
 * @var TP_Admin_Options $options
 */
$options = TotalPoll::instance( 'admin/options' );
if ( ! empty( $_POST['totalpoll'] ) ):
	$options->save();
endif;


?>
<div class="wrap" id="totalpoll-options">
	<form method="post">
		<?php

		$options->header();
		$options->options();
		$options->settings();
		$options->footer();

		?>
		<button class="button button-primary button-large"><?php _e( 'Save changes', TP_TD ); ?></button>

	</form>
</div>