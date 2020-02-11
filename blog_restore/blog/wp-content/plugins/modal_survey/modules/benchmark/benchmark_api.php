<?php
$contacts = array('email' => $email);
	$contact_datas = $contacts;
	if (isset($mv)) {
		if (is_array($mv)) 
		{
			$contact_datas = array_merge($contacts, $mv);
		}
	}
	$req = http_build_query(array(
		'contacts' => $contact_datas,
		'optin' => ( $sopts[ 38 ] == '1' ? 1 : 0),
		'listID' => $sopts[ 37 ],
		'token' => $sopts[ 36 ]
	));

	$curl = curl_init('http://www.benchmarkemail.com/api/1.0/?output=php&method=listAddContacts');
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $req);

	curl_setopt($curl, CURLOPT_TIMEOUT, 20);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
	curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
						
	$response = curl_exec($curl);
	curl_close($curl);
	$resp = unserialize($response);
	if (isset($resp['error'])) die($resp['error']);
	else $result=true;
?>