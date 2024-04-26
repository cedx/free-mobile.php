# Free Mobile for PHP
Send SMS messages to your [Free Mobile](https://mobile.free.fr) device via any internet-connected device.

For example, you can configure a control panel or storage connected to your home network to send a notification to your mobile phone when an event occurs.

## Quick start
!!! note
    SMS notifications require an API key. If you are not already registered, [sign up for a Free Mobile account](https://mobile.free.fr/subscribe).

### Get an API key
You first need to enable the **SMS notifications** in [your subscriber account](https://mobile.free.fr/account).
This will give you an identification key allowing access to the [Free Mobile](https://mobile.free.fr) API.

![Screenshot](screenshot.webp)

### Get the library
Install the latest version of **Free Mobile for PHP** with [Composer](https://getcomposer.org) package manager:

```shell
composer require cedx/free-mobile
```

For detailed instructions, see the [installation guide](installation.md).

## Usage
This library provides the `Client` class, which allow to send SMS messages to your mobile phone by using the `sendMessage()` method:

```php
<?php
use freemobile\Client;

try {
  $client = new Client(account: "your account identifier", apiKey: "your API key");
  $client->sendMessage("Hello World from PHP!");
  print "The message was sent successfully.";
}
catch (RuntimeException $e) {
  print "An error occurred: {$e->getMessage()}";
}
```

The `Client->sendMessage()` method throws a [`RuntimeException`](https://www.php.net/manual/en/class.runtimeexception.php)
if any error occurred while sending the message.

!!! warning
    The text of the messages will be automatically truncated to **160** characters:  
    you can't send multipart messages using this library.
