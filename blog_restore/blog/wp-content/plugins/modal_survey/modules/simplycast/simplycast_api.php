<?php
require_once(sprintf("%s/SimplyCastAPI.php", dirname(__FILE__)));

$simplycast_api = new \SimplyCast\API($sopts[ 118 ], $sopts[ 119 ]);
//Here's a contact that we want to insert into the system.
$myContact = array(
  'email'          => $email
);
$mc_contact_params = array_merge($myContact, $mv);
$cclm = array();
foreach($mc_contact_params as $key=>$value)
{
$column = $simplycast_api->contactManager->getColumnsByName($key);
	if (!$column) {
	  $column = $simplycast_api->contactManager->createColumn($key);
	}
$cclm[] = $key;
}

$columns = $simplycast_api->contactManager->getColumnsByName($cclm);
if (!$columns) {
  die("Error, no columns loaded!\n");
}

$contact = array();
foreach($columns['columns'] as $col) {
  if (array_key_exists($col['name'], $mc_contact_params)) {
    $contact[] = array(
      'id'    => $col['id'],
      'value' =>  $mc_contact_params[$col['name']],
    );
  }
}

$createdContact = $simplycast_api->contactManager->createContact($contact,array( $sopts[ 120 ] ));
if (isset($createdContact['error'])) {
  die("SimplyCast: Error creating contact!\n");
}
else $result = true;
?>