<?php
if ( empty( $sopts[ 71 ] ) || empty( $sopts[ 72 ] )|| empty( $sopts[ 73 ] ) ) {
	die( "MadMimi: You must specify the Username, API Key and List name" );
}
require_once(sprintf("%s/madmimi_api.php", dirname(__FILE__)));
$mailer = new MadMimi( $sopts[ 71 ], $sopts[ 72 ] ); 
$user = array('email' => $email, 'add_list' => $sopts[ 73 ] );
$user_params = array_merge($user,$mv);
$res = $mailer->AddUser($user_params);
if ($res) $result = true;
else die('MadMimi: Error');
?>