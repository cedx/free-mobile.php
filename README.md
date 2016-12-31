# Free Mobile for PHP
![Release](https://img.shields.io/packagist/v/cedx/free-mobile.svg) ![License](https://img.shields.io/packagist/l/cedx/free-mobile.svg) ![Downloads](https://img.shields.io/packagist/dt/cedx/free-mobile.svg) ![Coverage](https://coveralls.io/repos/github/cedx/free-mobile.php/badge.svg) ![Build](https://travis-ci.org/cedx/free-mobile.php.svg)

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
This package provides a single class allowing to send messages to your mobile phone.
It has an API based on [Observables](http://reactivex.io/intro.html).

### Creating the client
The provided `freemobile\Client` class have standard getters and setters to access its properties.
To ease the initialization of this class, its constructor accepts an associative array of property values (`"property" => "value"`), and its setters have a fluent interface:

```php
use freemobile\{Client};

// Using an associative array with the constructor.
$client = new Client([
  'username' => 'your Free Mobile user name',
  'password' => 'your Free Mobile identification key'
]);

// Using the fluent interface of the setters.
$client = (new Client())
  ->setUsername('your Free Mobile user name')
  ->setPassword('your Free Mobile identification key');
```

### Sending messages
Once the `freemobile\Client` class instantiated with your credentials, use the `sendMessage()` method:

```php
$client->sendMessage('Hello World!')->subscribeCallback(
  function() {
    echo 'The message was sent successfully.';
  },
  function(\Throwable $e) {
    echo 'An error occurred: ', $e->getMessage();
  }
);
```

The text of the messages will be automatically truncated to 160 characters: you can't send multipart messages using this library.

### Subscribing to events
The `freemobile\Client` class triggers some events during its life cycle:

- `request` : emitted every time a request is made to the remote service.
- `response` : emitted every time a response is received from the remote service.

These events are exposed as `Observable`, you can subscribe to them using the `on<EventName>` methods:

```php
use Psr\Http\Message\{RequestInterface, ResponseInterface};

$client->onRequest()->subscribeCallback(function(RequestInterface $request) {
  echo 'Client request: ', $request->getUri();
});

$client->onResponse()->subscribeCallback(function(ResponseInterface $response) {
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
- [Code coverage](https://coveralls.io/github/cedx/free-mobile.php)
- [Continuous integration](https://travis-ci.org/cedx/free-mobile.php)

## License
[Free Mobile for PHP](https://github.com/cedx/free-mobile.php) is distributed under the Apache License, version 2.0.
