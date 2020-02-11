<?php
if (empty($field1)||empty($field2)) die("You must specify the Username and API Key");
require_once(sprintf("%s/YMLP_API.class.php", dirname(__FILE__)));
// create API class
$api = new YMLP_API($field2,$field1);

// run command
$list=$api->GroupsGetList();
if (isset($list['Code'])) die('YMLP Error: Connection Error');
			$output = '<table><tr><th>List ID</th><th>List Name</th></tr>';
		foreach ($list as $key => $value) {
			$output .= '<tr><td class="getid" data-target="ymlp_groupid" data-value="'.$value['ID'].'">'.$value['ID'].'</td><td class="getid" data-target="ymlp_groupid" data-value="'.$value['ID'].'">'.$value['GroupName'].'</td></tr>';
		}
			$output .= '</table>';
		die($output);
?>