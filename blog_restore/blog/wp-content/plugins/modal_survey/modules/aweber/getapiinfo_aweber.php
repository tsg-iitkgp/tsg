<?php
require_once( sprintf( "%s/aweber_api/aweber_api.php", dirname( __FILE__ ) ) );
try {
	$aweber_auth = new AWeberAPI( $field3, $field4 );
	$account = $aweber_auth->getAccount( $field1, $field2 );
	$list_url = 'https://api.aweber.com/1.0/accounts/' . $account->data[ 'id' ] . '/lists';
	$lists = $account->loadFromUrl($list_url);
} catch( AWeberAPIException $exc ) {
    print "<h3>AWeberAPIException:</h3>";
    print " <li> Type: $exc->type              <br>";
    print " <li> Msg : $exc->message           <br>";
    print " <li> Docs: $exc->documentation_url <br>";
    print "<hr>";
}

if ( ! isset( $account ) ) {
	die( $result[ 'error' ] );
}
$output = '<table><tr><th>List ID</th><th>List Name</th></tr>';
foreach ( $lists->data[ 'entries' ] as $key => $value ) {
	$output .= '<tr><td class="getid" data-target="aweber_listid" data-value="' . $value[ 'id' ] . '">' . $value[ 'id' ] . '</td><td class="getid" data-target="aweber_listid" data-value="' . $value[ 'id' ] . '">' . $value[ 'name' ] . '</td></tr>';
}
$output .= '</table>';
die( $output );

?>