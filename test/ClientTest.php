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
   * @test Client::jsonSerialize
   */
  public function testJsonSerialize() {
    it('should return a map with the same public values', function() {
      $data = (new Client('anonymous', 'secret'))->jsonSerialize();
      expect(get_object_vars($data))->to->have->lengthOf(3);
      expect($data->endPoint)->to->equal(Client::DEFAULT_ENDPOINT);
      expect($data->password)->to->equal('secret');
      expect($data->username)->to->equal('anonymous');
    });
  }

  /**
   * @test Client::sendMessage
   */
  public function testSendMessage() {
    it('should not send valid messages with invalid credentials', function() {
      try {
        (new Client('', ''))->sendMessage('Hello World!');
        fail('A message with empty credentials should not be sent.');
      }

      catch (\Throwable $e) {
        expect(true)->to->be->true;
      }
    });

    it('should not send invalid messages with valid credentials', function() {
      try {
        (new Client('anonymous', 'secret'))->sendMessage('');
        fail('A message with empty credentials should not be sent.');
      }

      catch (\Throwable $e) {
        expect(true)->to->be->true;
      }
    });

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      it('should send valid messages with valid credentials', function() use ($password, $username) {
        try {
          (new Client($username, $password))->sendMessage('Bonjour Cédric !');
          expect(true)->to->be->true;
        }

        catch (\Throwable $e) {
          fail($e->getMessage());
        }
      });
    }
  }

  /**
   * @test Client::setEndPoint
   */
  public function testSetEndPoint() {
    it('should return an instance of `UriInterface` for strings', function() {
      $endPoint = (new Client)->setEndPoint('https://github.com/cedx/free-mobile.php')->getEndPoint();
      expect($endPoint)->to->be->instanceOf(UriInterface::class);
      expect((string) $endPoint)->to->equal('https://github.com/cedx/free-mobile.php');
    });

    it('should return a `null` reference for unsupported values', function() {
      expect((new Client)->setEndPoint(123)->getEndPoint())->to->be->null;
    });
  }

  /**
   * @test Client::__toString
   */
  public function testToString() {
    $client = (string) new Client('anonymous', 'secret');

    it('should start with the class name', function() use ($client) {
      expect($client)->to->startWith('FreeMobile\Client {');
    });

    it('should contain the instance properties', function() use ($client) {
      expect($client)->to->contain('"password":"secret"')->and->contain('"username":"anonymous"');
    });
  }
}
