<?php
require_once( sprintf( "%s/aweber_api/aweber_api.php", dirname( __FILE__ ) ) );
$aweber = new AWeberAPI( $sopts[ 30 ], $sopts[ 31 ] );

try {
    $account = $aweber->getAccount( $sopts[ 32 ], $sopts[ 33 ] );
    $listURL = "/accounts/" . $account->data[ 'id' ] . "/lists/" . $sopts[ 34 ];
    $list = $account->loadFromUrl( $listURL );

    # create a subscriber
    $contact = array(
        'email' => $_REQUEST[ 'email' ],
        'ip_address' => $_SERVER[ 'REMOTE_ADDR' ],
        'misc_notes' => 'Added by Modal Survey',
        'name' => $_REQUEST[ 'name' ]
    );
	if ( ! empty( $this->custom[ 'firstname' ] ) && ! empty( $this->custom[ 'lastname' ] ) ) {
		$contact[ "first_name" ] = $this->custom[ 'firstname' ];
		$contact[ "last_name" ] = $this->custom[ 'lastname' ];
	}
	unset( $this->custom[ 'firstname' ] );
	unset( $this->custom[ 'lastname' ] );		
	$contact_datas = $contact;
	if ( isset( $this->custom ) ) {
		if ( is_array( $this->custom ) ) {
			foreach( $this->custom as $key=>$mvitem ) {
				$nmv[ $key ] = $mvitem;
			}
			$contact_datas[ 'custom_fields' ] = $nmv;
		}
	}
   $subscribers = $list->subscribers;
    $new_subscriber = $subscribers->create( $contact_datas );
	$result = true;

} catch(AWeberAPIException $exc) {
 	die( $exc->message );
	if ( $exc->message == "email: Subscriber already subscribed and has not confirmed." ) {
		$result = true;
	}
	else {
		$error = "AWeberAPIException: $exc->message";
	}
}
?>