<?php declare(strict_types=1);
namespace FreeMobile;

use GuzzleHttp\Psr7\{Uri};
use PHPUnit\Framework\{TestCase};

/** Tests the features of the `FreeMobile\Client` class. */
class ClientTest extends TestCase {

  /** @test Client->__construct() */
  function testConstructor(): void {
    // It should throw an exception if the username or password is empty.
    $this->expectException(\InvalidArgumentException::class);
    new Client('', '');
  }

  /** @test Client->sendMessage() */
  function testSendMessage(): void {
    // It should not send invalid messages with valid credentials.
    try {
      (new Client('anonymous', 'secret'))->sendMessage('');
      $this->fail('Exception not thrown.');
    }

    catch (\Throwable $e) {
      assertThat($e, isInstanceOf(\InvalidArgumentException::class));
    }

    // It should throw a `ClientException` if a network error occurred.
    try {
      (new Client('anonymous', 'secret', new Uri('http://localhost/')))->sendMessage('Hello World!');
      $this->fail('Exception not thrown.');
    }

    catch (\Throwable $e) {
      assertThat($e, isInstanceOf(ClientException::class));
    }

    // It should send valid messages with valid credentials.
    try {
      $username = (string) getenv('FREEMOBILE_USERNAME');
      $password = (string) getenv('FREEMOBILE_PASSWORD');
      (new Client($username, $password))->sendMessage('Bonjour Cédric, à partir de PHP !');
      assertThat(true, isTrue());
    }

    catch (\Throwable $e) {
      $this->fail($e->getMessage());
    }
  }
}
