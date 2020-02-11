<?php
$user_data = array( 'email' => $_REQUEST[ 'email' ] );
if ( is_array( $mv ) ) {
	$user_params = array_merge( $user_data, $mv );
}
else {
	$user_params = $user_data;
}
unset( $user_params['name'] );
unset( $user_params['fullname'] );
unset( $user_params['fname'] );
unset( $user_params['lname'] );

    $data_subscriber = array(
      'user' => $user_params,
      'user_list' => array('list_ids' => array( $sopts[ 89 ] ) )
    );
 
    $helper_user = WYSIJA::get('user','helper');
    $success = $helper_user->addSubscriber($data_subscriber);
if ($success) $result = true;
else {
	die('MailPoet Error: Couldn\'t add user');
}
?>