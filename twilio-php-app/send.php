<?php

require_once 'vendor/autoload.php';

use Twilio\Rest\Client;

$sid    = "AC136d88c12e8d8c2b7b333d8766cde6f9";
$token  = "4d4ba12edb5587d6d3b7ce8ddc315c17";
$twilio = new Client($sid, $token);

$message = $twilio->messages
	->create(
		"whatsapp:+6285747771509", // to 
		array(
			"from" => "whatsapp:+14155238886",
			"body" => "Tes WA Gateway"
		)
	);

print($message->sid);
