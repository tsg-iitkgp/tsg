<?php
if (empty($field1)||empty($field2)) die("You must specify the API Key and Access Token.");
// require the autoloader
require_once(sprintf("%s/Ctct/autoload.php", dirname(__FILE__)));

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

// Enter your Constant Contact APIKEY and ACCESS_TOKEN
define("APIKEY", $field1);
define("ACCESS_TOKEN", $field2);

$cc = new ConstantContact(APIKEY);

// attempt to fetch lists in the account, catching any exceptions and printing the errors to screen
try {
    $lists = $cc->getLists(ACCESS_TOKEN);
} catch (CtctException $ex) {
    foreach ($ex->getErrors() as $error) {
        print_r($error);
    }
}
			$output = '<table><tr><th>List ID</th><th>List Name</th></tr>';
		foreach ($lists as $key => $value) {
			$output .= '<tr><td class="getid" data-target="constantcontact_listid" data-value="'.$value->id.'">'.$value->id.'</td><td class="getid" data-target="constantcontact_listid" data-value="'.$value->id.'">'.$value->name.'</td></tr>';
		}
			$output .= '</table>';
		die($output);
?>
