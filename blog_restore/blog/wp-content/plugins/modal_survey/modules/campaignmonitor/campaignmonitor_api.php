<?php
require_once( sprintf( "%s/csrest_subscribers.php", dirname( __FILE__ ) ) );
$auth = array( 'api_key' => $sopts[ 40 ] );	
$wrap = new CS_REST_Subscribers( $sopts[ 41 ], $auth );
$contact_datas = array();
	if ( isset( $mv ) ) {
		if ( is_array( $mv ) ) {
			foreach( $mv as $key=>$mvitem ) {
				$contact_datas[] = array( "Key"=>$key, "Value"=>$mvitem );
			}
		}
	}
if ( ! $name ) {
	$name = ( array_key_exists( 'NAME', $mv ) && ! empty( $mv[ 'NAME' ] ) ) ? $mv[ 'NAME' ] : 'non-existent or empty value key';
}
$res = $wrap->add(array(
    'EmailAddress' => $email,
	'Name' => $name,
    'CustomFields' => $contact_datas,
    'Resubscribe' => true
));
if( $res->was_successful() ) {
    $result=true;
} else {
	die( $res->http_status_code );
}

?>