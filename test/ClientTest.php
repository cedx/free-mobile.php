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
    $this->expectException(\InvalidArgumentException::class);
    new Client('', '');
  }

  /**
   * Tests the `Client::sendMessage()` method.
   */
  public function testSendMessage() {
    (new Client('foo', 'bar'))->sendMessage('')->subscribeCallback(
      null,
      function() { $this->assertTrue(true); },
      function() { $this->fail('An empty message should not be sent.'); }
    );

    if (is_string($userName = getenv('FREEMOBILE_USERNAME')) && is_string($password = getenv('FREEMOBILE_PASSWORD'))) {
      (new Client($userName, $password))->sendMessage('Hello World!')->subscribeCallback(
        null,
        function(\Exception $e) { $this->fail($e->getMessage()); },
        function() { $this->assertTrue(true); }
      );
    }
  }
}
