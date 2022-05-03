<?php declare(strict_types=1);
namespace FreeMobile;

use PHPUnit\Framework\{Assert, TestCase};
use Psr\Http\Client\ClientExceptionInterface;

use function PHPUnit\Framework\{assertThat, isInstanceOf};

/**
 * @testdox FreeMobile\Client
 */
class ClientTest extends TestCase {

	/**
	 * @testdox ->sendMessage()
	 */
	function testSendMessage(): void {
		// It should throw a `ClientExceptionInterface` if a network error occurred.
		try {
			(new Client(account: "anonymous", apiKey: "secret", baseUrl: "http://localhost:10000/"))->sendMessage("Hello World!");
			Assert::fail("Exception not thrown.");
		}
		catch (\Throwable $e) {
			assertThat($e, isInstanceOf(ClientExceptionInterface::class));
		}

		// It should throw a `ClientExceptionInterface` if the credentials are invalid.
		try {
			(new Client(account: "anonymous", apiKey: "secret"))->sendMessage("Hello World!");
			Assert::fail("Exception not thrown.");
		}
		catch (\Throwable $e) {
			assertThat($e, isInstanceOf(ClientExceptionInterface::class));
		}

		// It should send SMS messages if credentials are valid.
		try {
			(new Client(getenv("FREEMOBILE_ACCOUNT"), getenv("FREEMOBILE_API_KEY")))->sendMessage("Hello Cédric, from PHP!");
		}
		catch (\Throwable $e) {
			Assert::fail($e->getMessage());
		}
	}
}
