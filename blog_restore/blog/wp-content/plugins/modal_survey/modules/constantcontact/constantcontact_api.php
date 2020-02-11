<?php
// require the autoloader
require_once(sprintf("%s/Ctct/autoload.php", dirname(__FILE__)));

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

// Enter your Constant Contact APIKEY and ACCESS_TOKEN
define("APIKEY", $sopts[ 47 ]);
define("ACCESS_TOKEN", $sopts[ 48 ] );

$cc = new ConstantContact(APIKEY);

// check if the form was submitted
    $action = "Getting Contact By Email Address";
    try {
        // check to see if a contact with the email addess already exists in the account
        $response = $cc->getContactByEmail(ACCESS_TOKEN, $email);

        // create a new contact if one does not exist
        if (empty($response->results)) {
            $action = "Creating Contact";

            $contact = new Contact();
            $contact->addEmail( $email );
            $contact->addList( $sopts[ 49 ] );
            if (isset($mv['firstname'])) $contact->first_name = $mv['firstname'];
            if (isset($mv['lastname'])) $contact->last_name = $mv['lastname'];
			if (isset($mv)) {
				if (is_array($mv)) 
				{
				$c=0;
					foreach($mv as $key=>$mvitem)
					{
					$c++;
						$contact_datas[] = array('name'=>'CustomField'.$c,'label'=>'CustomField'.$c,'value'=>$mvitem);
					}
				}
			}
			$contact->custom_fields = $contact_datas;
            /*
             * The third parameter of addContact defaults to false, but if this were set to true it would tell Constant
             * Contact that this action is being performed by the contact themselves, and gives the ability to
             * opt contacts back in and trigger Welcome/Change-of-interest emails.
             *
             * See: http://developer.constantcontact.com/docs/contacts-api/contacts-index.html#opt_in
             */
            $returnContact = $cc->addContact(ACCESS_TOKEN, $contact, true);

            // update the existing contact if address already existed
        } else {
            $action = "Updating Contact";

            $contact = $response->results[0];
            $contact->addList( $sopts[ 49 ] );
            if (isset($mv['firstname'])) $contact->first_name = $mv['firstname'];
            if (isset($mv['lastname'])) $contact->last_name = $mv['lastname'];
			if (isset($mv)) {
				if (is_array($mv)) 
				{
				$c=0;
					foreach($mv as $key=>$mvitem)
					{
					$c++;
						$contact_datas[] = array('name'=>'CustomField'.$c,'label'=>'CustomField'.$c,'value'=>$mvitem);
					}
				}
			}
			$contact->custom_fields = $contact_datas;
            /*
             * The third parameter of updateContact defaults to false, but if this were set to true it would tell
             * Constant Contact that this action is being performed by the contact themselves, and gives the ability to
             * opt contacts back in and trigger Welcome/Change-of-interest emails.
             *
             * See: http://developer.constantcontact.com/docs/contacts-api/contacts-index.html#opt_in
             */
            $returnContact = $cc->updateContact(ACCESS_TOKEN, $contact, true);
        }
		$result = true;
        // catch any exceptions thrown during the process and print the errors to screen
    } catch (CtctException $ex) {
       echo '<span class="label label-important">Error ' . $action . '</span>';
        echo '<div class="container alert-error"><pre class="failure-pre">';
        print_r($ex->getErrors());
        echo '</pre></div>';
        die();
    }
?>