<?php
/**
 * Implementation of the `freemobile\test\ClientTest` class.
 */
namespace freemobile\test;
use freemobile\{Client};

/**
 * Tests the features of the `freemobile\Client` class.
 */
class ClientTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the `Client` constructor.
   */
  public function testConstructor() {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $this->assertEquals('secret', $client->getPassword());
    $this->assertEquals('anonymous', $client->getUsername());

    $this->assertSame($client, $client->setPassword(''));
    $this->assertEmpty($client->getPassword());
  }

  /**
   * Tests the `Client::sendMessage()` method.
   */
  public function testSendMessage() {
    $client = new Client(['username' => '', 'password' => '']);
    $client->sendMessage('Hello World!')->subscribeCallback(
      function() { $this->fail('A message with empty credentials should not be sent.'); },
      function() { $this->assertTrue(true); }
    );

    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $client->sendMessage('')->subscribeCallback(
      function() { $this->fail('An empty message with credentials should not be sent.'); },
      function() { $this->assertTrue(true); }
    );

    if (is_string($username = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      $client = new Client(['username' => $username, 'password' => $password]);
      $client->sendMessage('Bonjour Cédric !')->subscribeCallback(
        function() { $this->assertTrue(true); },
        function(\Exception $e) { $this->fail($e->getMessage()); }
      );
    }
  }

  /**
   * Tests the `Client::toJSON()` method.
   */
  public function testToJSON() {
    $data = (new Client(['username' => 'anonymous', 'password' => 'secret']))->toJSON();
    $this->assertEquals('secret', $data->password);
    $this->assertEquals('anonymous', $data->username);
  }
}
