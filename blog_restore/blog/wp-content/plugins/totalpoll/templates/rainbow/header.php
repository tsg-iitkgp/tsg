<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>

<form method="post" novalidate class="totalpoll-view-<?php echo $this->current; ?>">

<?php echo implode( '', $this->hidden_fields() ); ?>