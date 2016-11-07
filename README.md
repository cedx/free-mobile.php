# Free Mobile
![Release](https://img.shields.io/packagist/v/cedx/free-mobile.svg) ![License](https://img.shields.io/packagist/l/cedx/free-mobile.svg) ![Downloads](https://img.shields.io/packagist/dt/cedx/free-mobile.svg) ![Code quality](https://img.shields.io/codacy/grade/73859544fbc54257b639170a26acdc53.svg) ![Build](https://img.shields.io/travis/cedx/free-mobile.php.svg)

Send SMS messages to your [Free Mobile](http://mobile.free.fr) account, in [PHP](https://secure.php.net).

To use this library, you must have enabled SMS Notifications in the Options of your [Subscriber Area](https://mobile.free.fr/moncompte).

## Requirements
The latest [PHP](https://secure.php.net) and [Composer](https://getcomposer.org) versions.
If you plan to play with the sources, you will also need the [Phing](https://www.phing.info) latest version.

## Installing via [Composer](https://getcomposer.org)
From a command prompt, run:

```shell
$ composer require cedx/free-mobile
```

## Usage
This package has an API based on [Observables](http://reactivex.io/intro.html).

It provides a single class, `freemobile\Client` which allow to send messages to your mobile phone by using the `sendMessage` method:

```php
use freemobile\{Client};

$client = new Client('<your Free Mobile user name>', '<your Free Mobile identification key>');
$client->sendMessage('Hello World!')->subscribeCallback(
  null,
  function(\Exception $e) {
    echo 'An error occurred while sending the message: ' . $e->getMessage();
  },
  function() {
    echo 'The message was sent successfully.';
  }
);
```

The message text will be automatically truncated to 160 characters.

## See Also
- [Code Quality](https://www.codacy.com/app/cedx/free-mobile.php)
- [Continuous Integration](https://travis-ci.org/cedx/free-mobile.php)

## License
[Free Mobile](https://github.com/cedx/free-mobile.php) is distributed under the Apache License, version 2.0.
