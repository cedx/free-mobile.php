<?php
/**
 * Implementation of the `freemobile\test\ClientTest` class.
 */
namespace freemobile\test;

use freemobile\{Client};
use PHPUnit\Framework\{TestCase};

/**
 * @coversDefaultClass \freemobile\Client
 */
class ClientTest extends TestCase {

  /**
   * @test ::jsonSerialize
   */
  public function testJsonSerialize() {
    $data = (new Client('anonymous', 'secret'))->jsonSerialize();
    $this->assertEquals(count(get_object_vars($data)), 3);
    $this->assertEquals(Client::DEFAULT_ENDPOINT, $data->endPoint);
    $this->assertEquals('secret', $data->password);
    $this->assertEquals('anonymous', $data->username);
  }

  /**
   * @test ::sendMessage
   */
  public function testSendMessage() {
    try {
      (new Client('', ''))->sendMessage('Hello World!');
      $this->fail('A message with empty credentials should not be sent.');
    }

    catch (\Throwable $e) {
      $this->assertTrue(true);
    }

    try {
      (new Client('anonymous', 'secret'))->sendMessage('');
      $this->fail('An empty message with credentials should not be sent.');
    }

    catch (\Throwable $e) {
      $this->assertTrue(true);
    }

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      (new Client($username, $password))->sendMessage('Bonjour Cédric !');
      $this->assertTrue(true);
    }
  }
}
