path: blob/master
source: lib/Client.php

# Usage

## SMS notifications
**Free Mobile for PHP** provides the `FreeMobile\Client` class, which allow to send SMS messages to your mobile phone by using the `sendMessage()` method:

```php
<?php
use FreeMobile\{Client, ClientException};

function main(): void {
  try {
    $client = new Client('your account identifier', 'your API key');
    // For example: new Client('12345678', 'a9BkVohJun4MAf')

    $client->sendMessage('Hello World!');
    echo 'The message was sent successfully.';
  }

  catch (Throwable $e) {
    echo 'An error occurred: ', $e->getMessage(), PHP_EOL;
    if ($e instanceof ClientException) echo 'From: ', $e->getUri(), PHP_EOL;
  }
}
```

The `Client->sendMessage()` method throws a `FreeMobile\ClientException` if any error occurred while sending the message.

!!! warning
    The text of the messages will be automatically truncated to **160** characters:  
    you can't send multipart messages using this library.

## Client events
The `FreeMobile\Client` class is an [EventDispatcher](https://symfony.com/doc/current/components/event_dispatcher.html) that triggers some events during its life cycle.

### The `Client::eventRequest` event
Emitted every time a request is made to the remote service:

```php
<?php
use FreeMobile\{Client, RequestEvent};

function main(): void {
  $client = new Client('your account identifier', 'your API key');
  $client->addListener(Client::eventRequest, function(RequestEvent $event) {
    echo 'Client request: ', $event->getRequest()->getUri();
  });
}
```

### The `Client::eventResponse` event
Emitted every time a response is received from the remote service:

```php
<?php
use FreeMobile\{Client, ResponseEvent};

function main(): void {
  $client = new Client('your account identifier', 'your API key');
  $client->addListener(Client::eventResponse, function(ResponseEvent $event) {
    echo 'Server response: ', $event->getResponse()->getStatusCode();
  });
}
```
