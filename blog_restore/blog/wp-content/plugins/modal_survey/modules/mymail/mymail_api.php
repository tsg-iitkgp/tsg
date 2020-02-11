<?php
$user = array(
    'email' => $email,
    'status' => 1,
	'referer' => 'Modal Survey'
);
if ( is_array( $mv ) ) {
	$user_params = array_merge( $user, $mv );
}
else {
	$user_params = $user;
}
$subscriber_id = mailster('subscribers')->add($user_params, true );
$success = mailster('subscribers')->assign_lists($subscriber_id, $sopts[ 95 ], $remove_old = false);
if ($success) $result = true;
else die('Mailster Error: Couldn\'t add user');
?>