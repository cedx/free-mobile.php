# Free Mobile <small>for PHP</small>
![Runtime](https://img.shields.io/packagist/php-v/cedx/free-mobile.svg) ![Release](https://img.shields.io/packagist/v/cedx/free-mobile.svg) ![License](https://img.shields.io/packagist/l/cedx/free-mobile.svg) ![Downloads](https://img.shields.io/packagist/dt/cedx/free-mobile.svg) ![Coverage](https://coveralls.io/repos/github/cedx/free-mobile.php/badge.svg) ![Build](https://github.com/cedx/free-mobile.php/workflows/build/badge.svg)

![Free Mobile](img/free_mobile.png)

## Send SMS messages to your Free Mobile account
Send notifications to your own mobile device via any internet-connected device.

For example, you can configure a control panel or a network-attached storage to your home so that they send an SMS to your [Free Mobile](http://mobile.free.fr) phone when an event occurs.

## Quick start

!!! warning
    SMS notifications require an API key. If you are not already registered,
    [sign up for a Free Mobile account](https://mobile.free.fr/subscribe).

### Get an API key
You first need to enable the **SMS notifications** in [your subscriber account](https://mobile.free.fr/moncompte).
This will give you an identification key allowing access to the [Free Mobile](http://mobile.free.fr) API.

![SMS notifications](img/sms_notifications.jpg)  

### Get the library
Install the latest version of **Free Mobile for PHP** with [Composer](https://getcomposer.org):

```shell
composer require cedx/free-mobile
```

For detailed instructions, see the [installation guide](installation.md).
