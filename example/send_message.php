<?php declare(strict_types=1);

use FreeMobile\Client;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * Sends an SMS notification.
 */
try {
	$client = new Client(account: "your account identifier", apiKey: "your API key");
	// For example: new Client("12345678", "a9BkVohJun4MAf")

	$client->sendMessage("Hello World from PHP!");
	print "The message was sent successfully.";
}

catch (ClientExceptionInterface $e) {
	print "An error occurred: {$e->getMessage()}";
}
