<?php
   require_once(sprintf("%s/ActiveCampaign.class.php", dirname(__FILE__)));
	$ac = new ActiveCampaign( $sopts[ 25 ], $sopts[ 26 ] );
	if (!empty( $sopts[ 27 ] )) $list_id = $sopts[ 27 ];
	else die("ActiveCampaign Error: List ID not specified.");
	/*
	 * TEST API CREDENTIALS.
	 */
	if (!(int)$ac->credentials_test()) {
		die("ActiveCampaign: Invalid Credentials");
		exit();
	}
	/*
	 * ADD OR EDIT CONTACT (TO THE NEW LIST CREATED ABOVE).
	 */
	if ( isset( $_REQUEST[ 'email' ] ) && ! empty( $_REQUEST[ 'email' ] ) ) {
		$email = $_REQUEST[ 'email' ];
	}
	unset( $this->custom[ 'name' ] );
	unset( $this->custom[ 'fullname' ] );
	unset( $this->custom[ 'fname' ] );
	unset( $this->custom[ 'lname' ] );
	$contact = array(
		"email" => $_REQUEST[ 'email' ],
		"p[{$list_id}]" => $list_id,
		"status[{$list_id}]" => 1, // "Active" status
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
				$nmv[ 'field[%' . $key . '%,0]' ] = $mvitem;
			}
			$contact_datas = array_merge( $contact, $nmv );
		}
	}
	$contact_sync = $ac->api("contact/sync", $contact_datas);
	if ((int)$contact_sync->success) {
		// successful request
		$contact_id = (int)$contact_sync->subscriber_id;
		$result = true;
	}
	else {
		// request failed
		$result = false;
		die("ActiveCampaign Error: " . $contact_sync->error);
		exit();
	}
?>