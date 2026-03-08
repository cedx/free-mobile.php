# Installation

## Requirements
Before installing **Free Mobile for PHP**, you need to make sure you have [PHP](https://www.php.net)
and [Composer](https://getcomposer.org), the PHP package manager, up and running.

You can verify if you're already good to go with the following commands:

```shell
php --version
# PHP 8.5.2 (cli) (built: Jan 13 2026 21:54:54) (NTS Visual C++ 2022 x64)

composer --version
# Composer version 2.9.3 2025-12-30 13:40:17
```

## Installing with Composer package manager

### 1. Install it
From a command prompt, run:

```shell
composer require cedx/free-mobile
```

### 2. Import it
Now in your [PHP](https://www.php.net) code, you can use:

```php
use Belin\FreeMobile\Client;
```
