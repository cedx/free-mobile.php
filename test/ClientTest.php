<?php declare(strict_types=1);
namespace FreeMobile;

use function PHPUnit\Expect\{expect, it};
use GuzzleHttp\Psr7\{Uri};
use PHPUnit\Framework\{TestCase};

/** @testdox FreeMobile\Client */
class ClientTest extends TestCase {

  /** @testdox ->sendMessage() */
  function testSendMessage(): void {
    it('should throw a `ClientException` if a network error occurred', function() {
      expect(fn() => (new Client('anonymous', 'secret', new Uri('http://localhost/')))->sendMessage('Hello World!'))->to->throw(ClientException::class);
    });

    it('should send SMS messages if credentials are valid', function() {
      $username = (string) getenv('FREEMOBILE_USERNAME');
      $password = (string) getenv('FREEMOBILE_PASSWORD');
      expect(fn() => (new Client($username, $password))->sendMessage('Bonjour Cédric, à partir de PHP !'))->to->not->throw;
    });
  }
}
