<?php
require_once(sprintf("%s/YMLP_API.class.php", dirname(__FILE__)));
// create API class
$api = new YMLP_API( $sopts[ 123 ],$sopts[ 122 ] );

// run command
$OverruleUnsubscribedBounced = "0";
$output=$api->ContactsAdd( $email, $mv, $sopts[ 124 ], $OverruleUnsubscribedBounced );

// output results
if ($api->ErrorMessage){
	die("YMLP connection problem: " . $api->ErrorMessage);
} else {
	$result = true;
}
?>