<?php
	if( ! class_exists( 'MailChimp' ) ) {
		require_once( sprintf( "%s/MailChimp.php", dirname( __FILE__ ) ) );
	}
	$MailChimp = new MailChimp( $sopts[ 75 ] );
	$mlid = $mailchimp_listid;
	$mc_data = array(
		'email_address' => $_REQUEST[ 'email' ],
		'status' => 'subscribed' //change it to pending to enable double-optin
	);
	$this->custom[ 'NAME' ] = $_REQUEST[ 'name' ];
	$this->custom[ 'FNAME' ] = $_REQUEST[ 'name' ];
	if ( isset( $this->custom ) ) {
		if ( is_array( $this->custom ) ) {
			foreach( $this->custom as $key=>$mvitem ) {
				if ( ! empty( $mvitem ) ) {
					$nmv[ $key ] = $mvitem;
				}
			}
			if ( ! empty( $nmv ) ) {
				$contact_datas[ 'merge_fields' ] = $nmv;
			}
		}
	}
	if ( isset( $contact_datas ) ) {
		$contact_datas = array_merge( $contact_datas, $mc_data );
	}
	else {
		$contact_datas = $mc_data;		
	}
	$result = $MailChimp->post( 'lists/' . $mlid . '/members', $contact_datas );
	if ( $result[ 'status' ] == 400 ) {
		$subscriber_hash = $MailChimp->subscriberHash( $email );
		$result = $MailChimp->put( 'lists/' . $mlid . '/members/' . $subscriber_hash, $mc_data );					
		if ( $result[ 'status' ] == 400 ) {
			var_dump( $result );
		}
	}
	if ( $MailChimp->success() ) {
		$result = true;
	}
	else {
		$result = false;
		$error_msg = $MailChimp->getLastError();
		var_dump($error_msg);
	}	
?>