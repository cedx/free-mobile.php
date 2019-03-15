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

  catch (\Throwable $e) {
    echo 'An error occurred: ', $e->getMessage(), PHP_EOL;
    if ($e instanceof ClientException) echo 'From: ', $e->getUri(), PHP_EOL;
  }
}
```

The `Client::sendMessage()` method throws an [`InvalidArgumentException`](https://secure.php.net/manual/en/class.invalidargumentexception.php)
if the account credentials are invalid or if the specified message is empty. It throws a `FreeMobile\ClientException` if any error occurred while sending the message.

!!! warning
    The text of the messages will be automatically truncated to **160** characters:  
    you can't send multipart messages using this library.

## Client events
The `FreeMobile\Client` class is a [`League\Event\Emitter`](https://event.thephpleague.com/2.0/emitter/basic-usage) that triggers some events during its life cycle:

- `Client::EVENT_REQUEST` : emitted every time a request is made to the remote service.
- `Client::EVENT_RESPONSE` : emitted every time a response is received from the remote service.

You can subscribe to them using the `addListener()` method:

```php
<?php
use FreeMobile\{Client, RequestEvent, ResponseEvent};

function main(): void {
  $client = new Client('your account identifier', 'your API key');
  
  $client->addListener(Client::EVENT_REQUEST, function(RequestEvent $event) {
    echo 'Client request: ', $event->getRequest()->getUri();
  });

  $client->addListener(Client::EVENT_RESPONSE, function(ResponseEvent $event) {
    echo 'Server response: ', $event->getResponse()->getStatusCode();
  });
}
```

## Unit tests
If you want to run the library tests, you must set two environment variables:

```shell
export FREEMOBILE_USERNAME="your account identifier"
export FREEMOBILE_PASSWORD="your API key"
```

Then, you can run the `test` script from the command prompt:

```shell
composer test
```
