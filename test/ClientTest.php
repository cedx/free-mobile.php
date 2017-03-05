<?php
/**
 * Implementation of the `freemobile\test\ClientTest` class.
 */
namespace freemobile\test;

use freemobile\{Client};
use PHPUnit\Framework\{TestCase};
use Rx\{Observable};
use Rx\Subject\{Subject};

/**
 * @coversDefaultClass \freemobile\Client
 */
class ClientTest extends TestCase {

  /**
   * @test ::jsonSerialize
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
   * @test ::onRequest
   */
  public function testOnRequest() {
    it('should return an `Observable` instead of the underlying `Subject`', function() {
      $stream = (new Client('anonymous', 'secret'))->onRequest();
      expect($stream)->to->be->instanceOf(Observable::class);
      expect($stream)->to->not->be->instanceOf(Subject::class);
    });
  }

  /**
   * @test ::onRequest
   */
  public function testOnResponse() {
    it('should return an `Observable` instead of the underlying `Subject`', function() {
      $stream = (new Client('anonymous', 'secret'))->onResponse();
      expect($stream)->to->be->instanceOf(Observable::class);
      expect($stream)->to->not->be->instanceOf(Subject::class);
    });
  }

  /**
   * @test ::sendMessage
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
        fail('An empty message with credentials should not be sent.');
      }

      catch (\Throwable $e) {
        expect(true)->to->be->true;
      }
    });

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      it('should send valid messages with valid credentials', function() use ($password, $username) {
        (new Client($username, $password))->sendMessage('Bonjour Cédric !');
        expect(true)->to->be->true;
      });
    }
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $client = (string) new Client('anonymous', 'secret');

    it('should start with the class name', function() use ($client) {
      expect($client)->to->startWith('freemobile\Client {');
    });

    it('should contain the instance properties', function() use ($client) {
      expect($client)->to->contain('"password":"secret"')->and->contain('"username":"anonymous"');
    });
  }
}
