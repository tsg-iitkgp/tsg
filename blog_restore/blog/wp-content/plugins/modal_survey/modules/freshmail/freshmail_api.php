<?php
  require_once(sprintf("%s/class.rest.php", dirname(__FILE__)));

$rest = new FmRestAPI();
$rest->setApiKey( $sopts[ 51 ] );
$rest->setApiSecret( $sopts[ 52 ] );

$data = array(
    'email' => $email,
    'list'  => $sopts[ 53 ],
    'custom_fields' => $mv
);

//testing transactional mail request
try {
    $response = $rest->doRequest('subscriber/add', $data);
	$result = true;
} catch (Exception $e) {
    die("FreshMail Error: ".$e->getMessage());
}
?>