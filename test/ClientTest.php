<?php declare(strict_types=1);
namespace FreeMobile;

use PHPUnit\Framework\{Assert, TestCase};
use Psr\Http\Client\{NetworkExceptionInterface, RequestExceptionInterface};
use function PHPUnit\Framework\{assertThat, isInstanceOf, isNull};

/**
 * @testdox FreeMobile\Client
 */
class ClientTest extends TestCase {

	/**
	 * @testdox ->sendMessage()
	 */
	function testSendMessage(): void {
		// It should throw a `NetworkExceptionInterface` if a network error occurred.
		try {
			(new Client(account: "anonymous", apiKey: "secret", baseUrl: "http://localhost:10000/"))->sendMessage("Hello World!");
			Assert::fail("Exception not thrown.");
		}
		catch (\Throwable $e) {
			assertThat($e, isInstanceOf(NetworkExceptionInterface::class));
		}

		// It should throw a `RequestExceptionInterface` if the credentials are invalid.
		try {
			(new Client(account: "anonymous", apiKey: "secret"))->sendMessage("Hello World!");
			Assert::fail("Exception not thrown.");
		}
		catch (\Throwable $e) {
			assertThat($e, isInstanceOf(RequestExceptionInterface::class));
		}

		// It should send SMS messages if credentials are valid.
		$client = new Client(getenv("FREEMOBILE_ACCOUNT") ?: "", getenv("FREEMOBILE_API_KEY") ?: "");
		assertThat($client->sendMessage("Hello Cédric, from PHP!"), isNull());
	}
}
