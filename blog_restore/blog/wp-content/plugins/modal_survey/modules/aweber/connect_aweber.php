<?php
# Refer to our getting started guide for a complete API walkthrough
# https://labs.aweber.com/snippets/gs/using_the_api
require_once( 'aweber_api/aweber_api.php' );
try {
    # set $authorization_code to the code that is given to you from
    # https://auth.aweber.com/1.0/oauth/authorize_app/YOUR_APP_ID
    $authorization_code = $_REQUEST[ 'field1' ];

    $auth = AWeberAPI::getDataFromAweberID( trim( $authorization_code ) );
    list( $consumerKey, $consumerSecret, $accessKey, $accessSecret ) = $auth;
	if ( ! empty( $auth[ 0 ] ) && ! empty( $auth[ 0 ] ) && ! empty( $auth[ 0 ] ) && ! empty( $auth[ 0 ] ) ) {
		$result = $auth[ 0 ] . ":ms-param:" . $auth[ 1 ] . ":ms-param:" . $auth[ 2 ] . ":ms-param:" . $auth[ 3 ] . ":ms-param:success";
	}
	else {
		$result = false;
	}
    # Store the Consumer key/secret, as well as the AccessToken key/secret
    # in your app, these are the credentials you need to access the API.
}
catch( AWeberAPIException $exc ) {
    $result = "AWeberAPIException: ";
    $result .= $exc->message;
}
	die( $result );
?>