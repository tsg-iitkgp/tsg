<?php
$path = str_replace( array( "\\", "/" ), array( MSDIRS, MSDIRS ), dirname( __FILE__ ) ) . MSDIRS . ".." . MSDIRS . "exports" . MSDIRS . $survey_exp[ 'id' ] . ".json";
if ( file_put_contents( $path, json_encode( $survey_exp ) ) ) {
	$result = "success";
}
else {
	 $result = __( 'Write error', MODAL_SURVEY_TEXT_DOMAIN );
}
?>