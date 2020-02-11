<?php
if (empty($field1)) die("You must specify the API Key.");
require_once(sprintf("%s/getresponse_api.php", dirname(__FILE__)));
$client = new jsonRPCClient('http://api2.getresponse.com');
$campaigns = $client->get_campaigns(
    $field1
);
$output = '<table><tr><th>List ID</th><th>List Name</th></tr>';
	foreach ($campaigns as $key => $value) {
		$output .= '<tr><td class="getid" data-target="getresponse_campaignid" data-value="'.$key.'">'.$key.'</td><td class="getid" data-target="getresponse_campaignid" data-value="'.$key.'">'.$value['name'].'</td></tr>';
	}
		$output .= '</table>';
die($output);
?>