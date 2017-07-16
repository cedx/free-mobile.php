# Free Mobile for PHP
![Runtime](https://img.shields.io/badge/php-%3E%3D7.0-brightgreen.svg) ![Release](https://img.shields.io/packagist/v/cedx/free-mobile.svg) ![License](https://img.shields.io/packagist/l/cedx/free-mobile.svg) ![Downloads](https://img.shields.io/packagist/dt/cedx/free-mobile.svg) ![Coverage](https://coveralls.io/repos/github/cedx/free-mobile.php/badge.svg) ![Build](https://travis-ci.org/cedx/free-mobile.php.svg)

Send SMS messages to your [Free Mobile](http://mobile.free.fr) account, in [PHP](https://secure.php.net).

To use this library, you must have enabled SMS Notifications in the Options of your [Subscriber Area](https://mobile.free.fr/moncompte).

## Requirements
The latest [PHP](https://secure.php.net) and [Composer](https://getcomposer.org) versions.
If you plan to play with the sources, you will also need the latest [Phing](https://www.phing.info) version.

## Installing via [Composer](https://getcomposer.org)
From a command prompt, run:

```shell
$ composer require cedx/free-mobile
```

## Usage
This package provides a single class, `freemobile\Client`, allowing to send messages to your mobile phone using the `sendMessage()` method:

```php
use freemobile\{Client};

try {
  $client = new Client('your user name', 'your identification key');
  $client->sendMessage('Hello World!');
  echo 'The message was sent successfully.';
}

catch (\Throwable $e) {
  echo 'An error occurred: ', $e->getMessage();
}
```

The text of the messages will be automatically truncated to 160 characters: you can't send multipart messages using this library.

### Subscribing to events
The `freemobile\Client` class is an `EventEmitter`.
During its life cycle, it emits these events:

- `request` : emitted every time a request is made to the remote service.
- `response` : emitted every time a response is received from the remote service.

You can subscribe to them using the `on()` method:

```php
use Psr\Http\Message\{RequestInterface, ResponseInterface};

$client->on('request', function(RequestInterface $request) {
  echo 'Client request: ', $request->getUri();
});

$client->on('response', function(ResponseInterface $response) {
  echo 'Server response: ', $response->getStatusCode();
});
```

## Unit Tests
In order to run the tests, you must set two environment variables:

```shell
$ export FREEMOBILE_USERNAME="<your Free Mobile user name>"
$ export FREEMOBILE_PASSWORD="<your Free Mobile identification key>"
```

Then, you can run the `test` script from the command prompt:

```shell
$ phing test
```

## See also
- [API reference](https://cedx.github.io/free-mobile.php)
- [Code coverage](https://coveralls.io/github/cedx/free-mobile.php)
- [Continuous integration](https://travis-ci.org/cedx/free-mobile.php)

## License
[Free Mobile for PHP](https://github.com/cedx/free-mobile.php) is distributed under the Apache License, version 2.0.
