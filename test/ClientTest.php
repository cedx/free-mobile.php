<?php
declare(strict_types=1);
namespace FreeMobile;

use function PHPUnit\Expect\{expect, fail, it};
use PHPUnit\Framework\{TestCase};
use Psr\Http\Message\{UriInterface};

/**
 * Tests the features of the `FreeMobile\Client` class.
 */
class ClientTest extends TestCase {

  /**
   * @test Client::__construct
   */
  public function testConstructor(): void {
    it('should throw an exception if the username or password is empty', function() {
      expect(function() { new Client('', ''); })->to->throw(\InvalidArgumentException::class);
    });
  }

  /**
   * @test Client::getEndPoint
   */
  public function testGetEndPoint(): void {
    it('should not be empty by default', function() {
      $endPoint = (new Client('anonymous', 'secret'))->getEndPoint();
      expect($endPoint)->to->be->an->instanceOf(UriInterface::class);
      expect((string) $endPoint)->to->equal('https://smsapi.free-mobile.fr');
    });

    it('should be an instance of the `Uri` class', function() {
      $endPoint = (new Client('anonymous', 'secret', 'http://localhost'))->getEndPoint();
      expect($endPoint)->to->be->an->instanceOf(UriInterface::class);
      expect((string) $endPoint)->to->equal('http://localhost');
    });
  }

  /**
   * @test Client::sendMessage
   */
  public function testSendMessage(): void {
    it('should not send invalid messages with valid credentials', function() {
      try {
        (new Client('anonymous', 'secret'))->sendMessage('');
        fail('An empty message with valid credentials should not be sent');
      }

      catch (\Throwable $e) {
        expect($e)->to->be->an->instanceOf(\InvalidArgumentException::class);
      }
    });

    it('should throw a `ClientException` if a network error occurred', function() {
      try {
        (new Client('anonymous', 'secret', 'http://localhost'))->sendMessage('Hello World!');
        fail('A message with an invalid endpoint should not be sent');
      }

      catch (\Throwable $e) {
        expect($e)->to->be->an->instanceOf(ClientException::class);
      }
    });

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      it('should send valid messages with valid credentials', function() use ($password, $username) {
        try {
          (new Client($username, $password))->sendMessage('Bonjour Cédric !');
          expect(true)->to->be->true;
        }

        catch (\Throwable $e) {
          expect($e)->to->be->an->instanceOf(ClientException::class);
        }
      });
    }
  }
}
