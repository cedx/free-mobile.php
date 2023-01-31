<?php
use freemobile\Client;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * Sends an SMS notification.
 */
try {
	$client = new Client(account: "your account identifier", apiKey: "your API key");
	$client->sendMessage("Hello World from PHP!");
	print "The message was sent successfully.";
}
catch (ClientExceptionInterface $e) {
	print "An error occurred: {$e->getMessage()}";
}
