<?php declare(strict_types=1);
namespace FreeMobile;

use Nyholm\Psr7\{Uri};
use PHPUnit\Framework\{Assert, TestCase};
use function PHPUnit\Framework\{assertThat, isInstanceOf, isNull, stringStartsWith};

/** @testdox FreeMobile\Client */
class ClientTest extends TestCase {

  /** @testdox ->sendMessage() */
  function testSendMessage(): void {
    // It should throw a `ClientException` if a network error occurred.
    try {
      (new Client('anonymous', 'secret', new Uri('http://localhost:10000/')))->sendMessage('Hello World!');
      Assert::fail('Exception not thrown');
    }

    catch (\Throwable $e) {
      assertThat($e, isInstanceOf(ClientException::class));
    }

    // It should trigger events.
    $client = new Client((string) getenv('FREEMOBILE_USERNAME'), (string) getenv('FREEMOBILE_PASSWORD'));
    $client->onRequest(function(RequestEvent $event) {
      assertThat((string) $event->getRequest()->getUri(), stringStartsWith('https://smsapi.free-mobile.fr/sendmsg?'));
    });

    // It should send SMS messages if credentials are valid.
    try { $client->sendMessage('Bonjour Cédric, à partir de PHP !'); }
    catch (\Throwable $e) { Assert::fail($e->getMessage()); }
  }
}
