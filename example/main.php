<?php declare(strict_types=1);
use FreeMobile\{Client, ClientException};

/**
 * Sends an SMS notification.
 */
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
