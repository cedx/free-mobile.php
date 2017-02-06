<?php
/**
 * Implementation of the `freemobile\test\ClientTest` class.
 */
namespace freemobile\test;

use freemobile\{Client};
use Psr\Http\Message\{RequestInterface, ResponseInterface};

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
   * Tests the `Client::jsonSerialize()` method.
   */
  public function testJsonSerialize() {
    $data = (new Client(['username' => 'anonymous', 'password' => 'secret']))->jsonSerialize();
    $this->assertEquals(count(get_object_vars($data)), 2);
    $this->assertEquals('secret', $data->password);
    $this->assertEquals('anonymous', $data->username);
  }

  /**
   * Tests the `Client::onRequest()` method.
   */
  public function testOnRequest() {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $client->onRequest()->subscribeCallback(function($request) { $this->assertInstanceOf(RequestInterface::class, $request); });
    try { $client->sendMessage('FooBar')->subscribeCallback(); }
    catch (\Throwable $e) {}
  }

  /**
   * Tests the `Client::onResponse()` method.
   */
  public function testOnResponse() {
    $client = new Client(['username' => 'anonymous', 'password' => 'secret']);
    $client->onResponse()->subscribeCallback(function($response) { $this->assertInstanceOf(ResponseInterface::class, $response); });
    try { $client->sendMessage('FooBar')->subscribeCallback(); }
    catch (\Throwable $e) {}
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
        function(\Throwable $e) { $this->fail($e->getMessage()); }
      );
    }
  }
}
