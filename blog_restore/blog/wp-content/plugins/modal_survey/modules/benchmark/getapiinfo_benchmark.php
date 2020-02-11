<?php
$req = http_build_query(array(
			'token' => $field1
		));
		$curl = curl_init('http://www.benchmarkemail.com/api/1.0/?output=php&method=listGet');
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $req);
		curl_setopt($curl, CURLOPT_TIMEOUT, 20);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		$resp = curl_exec($curl);
		curl_close($curl);
		$result = unserialize($resp);
		if (isset($result['error'])) die($result['error']);
		$lists = array();
			$output = '<table><tr><th>List ID</th><th>List Name</th></tr>';
		foreach ($result as $key => $value) {
			$output .= '<tr><td class="getid" data-target="benchmark_listid" data-value="'.$value['id'].'">'.$value['id'].'</td><td class="getid" data-target="benchmark_listid" data-value="'.$value['id'].'">'.$value['listname'].'</td></tr>';
		}
			$output .= '</table>';
		die($output);
?>