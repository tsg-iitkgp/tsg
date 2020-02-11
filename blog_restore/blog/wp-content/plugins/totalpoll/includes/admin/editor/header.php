<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<div id="totalpoll-core">
	<div id="totalpoll-editor">
		<?php if ( ! get_post_meta( $this->poll->id(), '_tp_migrated', true ) && get_post_meta( $this->poll->id(), '_tp_options', true ) ): ?>
			<div class="error">
				<h1><?php printf( __( 'This poll was created with prior version of TotalPoll. Please <a href="%s">migrate your polls</a>.', TP_TD ), admin_url( 'edit.php?post_type=poll&page=tp-tools' ) ); ?></h1>
			</div>
		<?php endif; ?>
	
