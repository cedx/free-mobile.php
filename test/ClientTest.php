<?php declare(strict_types=1);
namespace FreeMobile;

use function PHPUnit\Expect\{expect, it};
use GuzzleHttp\Psr7\{Uri};
use PHPUnit\Framework\{TestCase};

/** @testdox FreeMobile\Client */
class ClientTest extends TestCase {

  /** @testdox constructor */
  function testConstructor(): void {
    it('should throw an exception if the username or password is empty', function() {
      expect(function() { new Client('', ''); })->to->throw(\InvalidArgumentException::class);
    });
  }

  /** @testdox ->sendMessage() */
  function testSendMessage(): void {
    it('should not send invalid messages with valid credentials', function() {
      expect(function() { (new Client('anonymous', 'secret'))->sendMessage(''); })->to->throw(\InvalidArgumentException::class);
    });

    it('should throw a `ClientException` if a network error occurred', function() {
      expect(function() { (new Client('anonymous', 'secret', new Uri('http://localhost/')))->sendMessage('Hello World!'); })->to->throw(ClientException::class);
    });

    it('should send valid messages with valid credentials', function() {
      $username = (string) getenv('FREEMOBILE_USERNAME');
      $password = (string) getenv('FREEMOBILE_PASSWORD');
      expect(function() use ($username, $password) { (new Client($username, $password))->sendMessage('Bonjour Cédric, à partir de PHP !'); })->to->not->throw;
    });
  }
}
