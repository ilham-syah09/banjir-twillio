<?php

require_once 'vendor/autoload.php';

use Twilio\Rest\Client;

$sid    = "ACf1a094e56b6e90a4ef4a48d87030efad";
$token  = "6f90b7820d10b5f387ab57df3f081ad4";
$twilio = new Client($sid, $token);

$to = "whatsapp:+6289660299603";
$from = "whatsapp:+14155238886";
$body = "Ketika asap tinggi, camera capture gambar, kirim WA.. JOS";

$message = $twilio->messages
	->create(
		$to, // to 
		array(
			"from" => $from,
			"body" => $body
		)
	);

print($message->sid);
