<?php
if (empty($field1)||empty($field2)) die("You must specify the Domain and API Key");
require_once(sprintf("%s/httpful-0.2.0.phar", dirname(__FILE__)));
require_once(sprintf("%s/Campayn.php", dirname(__FILE__)));
require_once(sprintf("%s/CampaynList.php", dirname(__FILE__)));
require_once(sprintf("%s/CampaynContact.php", dirname(__FILE__)));
require_once(sprintf("%s/CampaynException.php", dirname(__FILE__)));
$campayn = new Campayn($field2, array(
    'domain' => $field1,
));
$res = $campayn->getLists();
		$lists = array();
			$output = '<table><tr><th>List ID</th><th>List Name</th></tr>';
		foreach ($res as $key => $value) {
			$output .= '<tr><td class="getid" data-target="campayn_listid" data-value="'.$value->id.'">'.$value->id.'</td><td class="getid" data-target="campayn_listid" data-value="'.$value->id.'">'.$value->name.'</td></tr>';
		}
			$output .= '</table>';
		die($output);
?>
