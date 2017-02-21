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
    // Should return a map with the same public values.
    $data = (new Client('anonymous', 'secret'))->jsonSerialize();
    $this->assertEquals(count(get_object_vars($data)), 3);
    $this->assertEquals(Client::DEFAULT_ENDPOINT, $data->endPoint);
    $this->assertEquals('secret', $data->password);
    $this->assertEquals('anonymous', $data->username);
  }

  /**
   * @test ::onRequest
   */
  public function testOnRequest() {
    // Should return an `Observable` instead of the underlying `Subject`.
    $stream = (new Client('anonymous', 'secret'))->onRequest();
    $this->assertInstanceOf(Observable::class, $stream);
    $this->assertFalse($stream instanceof Subject);
  }

  /**
   * @test ::onRequest
   */
  public function testOnResponse() {
    // Should return an `Observable` instead of the underlying `Subject`.
    $stream = (new Client('anonymous', 'secret'))->onResponse();
    $this->assertInstanceOf(Observable::class, $stream);
    $this->assertFalse($stream instanceof Subject);
  }

  /**
   * @test ::sendMessage
   */
  public function testSendMessage() {
    // Should not send valid messages with invalid credentials.
    try {
      (new Client('', ''))->sendMessage('Hello World!');
      $this->fail('A message with empty credentials should not be sent.');
    }

    catch (\Throwable $e) {
      $this->assertTrue(true);
    }

    // Should not send invalid messages with valid credentials.
    try {
      (new Client('anonymous', 'secret'))->sendMessage('');
      $this->fail('An empty message with credentials should not be sent.');
    }

    catch (\Throwable $e) {
      $this->assertTrue(true);
    }

    // Should send valid messages with valid credentials.
    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      (new Client($username, $password))->sendMessage('Bonjour Cédric !');
      $this->assertTrue(true);
    }
  }

  /**
   * @test ::__toString
   */
  public function testToString() {
    $config = (string) new Client('anonymous', 'secret');

    // Should start with the class name.
    $this->assertStringStartsWith('freemobile\Client {', $config);

    // Should contain the instance properties.
    $this->assertContains('"username":"anonymous"', $config);
    $this->assertContains('"password":"secret"', $config);
  }
}
