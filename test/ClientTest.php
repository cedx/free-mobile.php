<?php declare(strict_types=1);
namespace FreeMobile;

use PHPUnit\Framework\{Assert, TestCase};
use Psr\Http\Client\ClientExceptionInterface;
use function PHPUnit\Framework\{assertThat, equalTo, isInstanceOf, isNull};

/**
 * @testdox FreeMobile\Client
 */
class ClientTest extends TestCase {

	/**
	 * @testdox ->sendMessage(): it should throw a `ClientExceptionInterface` if a network error occurred.
	 */
	function testNetworkError(): void {
		try {
			(new Client(account: "anonymous", apiKey: "secret", baseUrl: "http://localhost:10000/"))->sendMessage("Hello World!");
			Assert::fail("Exception not thrown.");
		}

		catch (\Throwable $e) {
			assertThat($e, isInstanceOf(ClientExceptionInterface::class));
		}
	}

	/**
	 * @testdox ->sendMessage(): it should throw a `ClientExceptionInterface` if the credentials are invalid.
	 */
	function testInvalidCredentials(): void {
		try {
			(new Client(account: "anonymous", apiKey: "secret"))->sendMessage("Hello World!");
			Assert::fail("Exception not thrown.");
		}

		catch (\Throwable $e) {
			assertThat($e, isInstanceOf(ClientExceptionInterface::class));
		}
	}

	/**
	 * @testdox ->sendMessage(): it should send SMS messages if the credentials are valid.
	 */
	function testValidCredentials(): void {
		$client = new Client(getenv("FREEMOBILE_ACCOUNT") ?: "", getenv("FREEMOBILE_API_KEY") ?: "");
		assertThat($client->sendMessage("Hello Cédric, from PHP!"), isNull()); // @phpstan-ignore-line
	}
}
