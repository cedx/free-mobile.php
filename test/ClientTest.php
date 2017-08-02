<?php
declare(strict_types=1);
namespace FreeMobile;

use function PHPUnit\Expect\{expect, fail, it};
use PHPUnit\Framework\{TestCase};
use Psr\Http\Message\{UriInterface};
use Rx\Subject\{Subject};

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
   * @test Client::onRequest
   */
  public function testOnRequest() {
    it('should return an `Observable` instead of the underlying `Subject`', function() {
      expect((new Client)->onRequest())->to->not->be->instanceOf(Subject::class);
    });
  }

  /**
   * @test Client::onResponse
   */
  public function testOnResponse() {
    it('should return an `Observable` instead of the underlying `Subject`', function() {
      expect((new Client)->onResponse())->to->not->be->instanceOf(Subject::class);
    });
  }

  /**
   * @test Client::sendMessage
   */
  public function testSendMessage() {
    it('should not send valid messages with invalid credentials', function() {
      (new Client('', ''))->sendMessage('Hello World!')->subscribe(
        function() { fail('A message with empty credentials should not be sent.'); },
        function() { expect(true)->to->be->true; }
      );
    });

    it('should not send invalid messages with valid credentials', function() {
      (new Client('anonymous', 'secret'))->sendMessage('')->subscribe(
        function() { fail('A message with empty credentials should not be sent.'); },
        function() { expect(true)->to->be->true; }
      );
    });

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      it('should send valid messages with valid credentials', function() use ($password, $username) {
        (new Client($username, $password))->sendMessage('Bonjour Cédric !')->subscribe(
          function() { expect(true)->to->be->true; },
          function(\Throwable $e) { fail($e->getMessage()); }
        );
      });
    }
  }

  /**
   * @test Client::setEndPoint
   */
  public function testSetEndPoint() {
    it('should return an instance of `UriInterface` for strings', function() {
      $endPoint = (new Client('', ''))->setEndPoint('https://github.com/cedx/free-mobile.php')->getEndPoint();
      expect($endPoint)->to->be->instanceOf(UriInterface::class);
      expect((string) $endPoint)->to->equal('https://github.com/cedx/free-mobile.php');
    });

    it('should return a `null` reference for unsupported values', function() {
      $endPoint = (new Client('', ''))->setEndPoint(123)->getEndPoint();
      expect($endPoint)->to->be->null;
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
