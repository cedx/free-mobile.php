# Usage

## SMS notifications
**Free Mobile for PHP** provides the `FreeMobile\Client` class, which allow to send SMS messages to your mobile phone by using the `sendMessage()` method:

```php
use FreeMobile\{Client, ClientException};

function main(): void {
	try {
		$client = new Client("your account identifier", "your API key");
		// For example: new Client("12345678", "a9BkVohJun4MAf")

		$client->sendMessage("Hello World!");
		print "The message was sent successfully.";
	}

	catch (Throwable $e) {
		print "An error occurred: {$e->getMessage()}" . PHP_EOL;
		if ($e instanceof ClientException) print "From: {$e->getUri()}" . PHP_EOL;
	}
}
```

The `Client->sendMessage()` method throws a `FreeMobile\ClientException` if any error occurred while sending the message.

!> The text of the messages will be automatically truncated to **160** characters: you can't send multipart messages using this library.

## Client events
The `FreeMobile\Client` class is an [EventDispatcher](https://symfony.com/doc/current/components/event_dispatcher.html) that triggers some events during its life cycle.

### The "request" event
Emitted every time a request is made to the remote service:

```php
use FreeMobile\{Client, RequestEvent};

function main(): void {
	$client = new Client("your account identifier", "your API key");
	$client->addListener(Client::eventRequest, fn(RequestEvent $event) =>
		print "Client request: {$event->getRequest()->getUri()}"
	);
}
```

### The "response" event
Emitted every time a response is received from the remote service:

```php
use FreeMobile\{Client, ResponseEvent};

function main(): void {
	$client = new Client("your account identifier", "your API key");
	$client->addListener(Client::eventResponse, fn(ResponseEvent $event) =>
		print "Server response: {$event->getResponse()->getStatusCode()}"
	);
}
```
