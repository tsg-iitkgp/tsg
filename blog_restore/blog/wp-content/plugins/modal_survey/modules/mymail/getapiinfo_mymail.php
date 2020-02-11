<?php
if ( function_exists( 'mymail' ) ) {
	$lists = mymail( 'lists' )->get();
	$output = '<table><tr><th>List ID</th><th>List Name</th></tr>';
	foreach ( $lists as $key => $value ) {
		$output .= '<tr><td class="getid" data-target="mymail_listid" data-value="' . $value->ID . '">' . $value->ID . '</td><td class="getid" data-target="mymail_listid" data-value="' . $value->ID . '">' . $value->name . '</td></tr>';
	}
	$output .= '</table>';
	die( $output );
}
else {
	die("MyMail is not installed. Get it here: <a target='_blank' href='http://codecanyon.net/item/mymail-email-newsletter-plugin-for-wordpress/3078294?ref=pantherius'>Download MyMail</a>");
}
?>