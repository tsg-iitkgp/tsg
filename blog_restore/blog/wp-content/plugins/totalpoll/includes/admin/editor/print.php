<?php
if ( defined( 'ABSPATH' ) === false ) :
	exit;
endif; // Shhh
?>
<!doctype html>
<html lang="<?php language_attributes(); ?>">
<head>
	<meta charset="UTF-8">
	<title><?php _e( 'Poll', TP_TD ); ?> - <?php echo $this->poll->question(); ?></title>
	<link rel="stylesheet" href="<?php echo TP_URL . 'assets/css/admin-print.css' ?>">
</head>
<body>
<div id="chart">

</div>
<table>
	<thead>
	<tr>
		<th style="width: 1%"></th>
		<th style="width: 70%"><?php _e( 'Choice', TP_TD ); ?></th>
		<th style="width: 29%"><?php _e( 'Votes', TP_TD ); ?></th>
	</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<script type="text/javascript">
	<?php
	$choices = array( array( __( 'Choice', TP_TD ), __( 'Votes', TP_TD ) ) );
	foreach ( $this->poll->choices() as $choice ):
		if ( empty( $choice['content']['visible'] ) ):
			continue;
		endif;

		$choices[] = array( (string) esc_html( $choice['content']['label'] ), $choice['votes'] );
	endforeach;
	?>

	window.printData = <?php echo json_encode( $choices ); ?>;
</script>
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>
<script type="text/javascript" src="<?php echo TP_URL . 'assets/js/min/admin-print.js' ?>"></script>
</body>
</html>