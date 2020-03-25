<?php declare(strict_types=1);
namespace FreeMobile;

use GuzzleHttp\Psr7\{Uri};
use PHPUnit\Framework\{Assert, TestCase};
use function PHPUnit\Framework\{assertThat, isInstanceOf, isNull};

/** @testdox FreeMobile\Client */
class ClientTest extends TestCase {

  /** @testdox ->sendMessage() */
  function testSendMessage(): void {
    // It should throw a `ClientException` if a network error occurred.
    try {
      (new Client('anonymous', 'secret', new Uri('http://localhost/')))->sendMessage('Hello World!');
      Assert::fail('Exception not thrown');
    }

    catch (\Throwable $e) {
      assertThat($e, isInstanceOf(ClientException::class));
    }

    // It should send SMS messages if credentials are valid.
    try {
      $username = (string) getenv('FREEMOBILE_USERNAME');
      $password = (string) getenv('FREEMOBILE_PASSWORD');
      (new Client($username, $password))->sendMessage('Bonjour Cédric, à partir de PHP !');
      assertThat(null, isNull());
    }

    catch (\Throwable $e) {
      Assert::fail($e->getMessage());
    }
  }
}
