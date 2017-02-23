<?php
/**
 * Implementation of the `freemobile\test\ClientTest` class.
 */
namespace freemobile\test;

use Codeception\{Specify};
use freemobile\{Client};
use PHPUnit\Framework\{TestCase};
use Rx\{Observable};
use Rx\Subject\{Subject};

/**
 * @coversDefaultClass \freemobile\Client
 */
class ClientTest extends TestCase {
  use Specify;

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $this->specify('should return a map with the same public values', function() {
      $data = (new Client('anonymous', 'secret'))->jsonSerialize();
      $this->assertEquals(count(get_object_vars($data)), 3);
      $this->assertEquals(Client::DEFAULT_ENDPOINT, $data->endPoint);
      $this->assertEquals('secret', $data->password);
      $this->assertEquals('anonymous', $data->username);
    });
  }

  /**
   * @test ::onRequest
   */
  public function testOnRequest() {
    $this->specify('should return an `Observable` instead of the underlying `Subject`', function() {
      $stream = (new Client('anonymous', 'secret'))->onRequest();
      $this->assertInstanceOf(Observable::class, $stream);
      $this->assertFalse($stream instanceof Subject);
    });
  }

  /**
   * @test ::onRequest
   */
  public function testOnResponse() {
    $this->specify('should return an `Observable` instead of the underlying `Subject`', function() {
      $stream = (new Client('anonymous', 'secret'))->onResponse();
      $this->assertInstanceOf(Observable::class, $stream);
      $this->assertFalse($stream instanceof Subject);
    });
  }

  /**
   * @test ::sendMessage
   */
  public function testSendMessage() {
    $this->specify('should not send valid messages with invalid credentials', function() {
      try {
        (new Client('', ''))->sendMessage('Hello World!');
        $this->fail('A message with empty credentials should not be sent.');
      }

      catch (\Throwable $e) {
        $this->assertTrue(true);
      }
    });

    $this->specify('should not send invalid messages with valid credentials', function() {
      try {
        (new Client('anonymous', 'secret'))->sendMessage('');
        $this->fail('An empty message with credentials should not be sent.');
      }

      catch (\Throwable $e) {
        $this->assertTrue(true);
      }
    });

    $this->specify('should send valid messages with valid credentials', function() {
      if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
        (new Client($username, $password))->sendMessage('Bonjour Cédric !');
        $this->assertTrue(true);
      }
    });
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $client = (string) new Client('anonymous', 'secret');

    $this->specify('should start with the class name', function() use ($client) {
      $this->assertStringStartsWith('freemobile\Client {', $client);
    });

    $this->specify('should contain the instance properties', function() use ($client) {
      $this->assertContains(sprintf('"endPoint":"%s"', Client::DEFAULT_ENDPOINT), $client);
      $this->assertContains('"username":"anonymous"', $client);
      $this->assertContains('"password":"secret"', $client);
    });
  }
}
