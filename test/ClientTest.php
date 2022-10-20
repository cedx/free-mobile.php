<?php namespace FreeMobile;

use PHPUnit\Framework\{Assert, TestCase};
use Psr\Http\Client\ClientExceptionInterface;
use function PHPUnit\Expect\{expect, it};

/**
 * @testdox FreeMobile\Client
 */
class ClientTest extends TestCase {

	/**
	 * @testdox ->sendMessage()
	 */
	function testNetworkError(): void {
		it("should reject if a network error occurred", function() {
			$client = new Client(account: "anonymous", apiKey: "secret", baseUrl: "http://localhost:10000/");
			expect(fn() => $client->sendMessage("Hello World!"))->to->throw(ClientExceptionInterface::class);
		});

		it("should reject if the credentials are invalid", function() {
			$client = new Client(account: "anonymous", apiKey: "secret");
			expect(fn() => $client->sendMessage("Hello World!"))->to->throw(ClientExceptionInterface::class);
		});

		it("should send SMS messages if the credentials are valid", function() {
			$client = new Client(getenv("FREEMOBILE_ACCOUNT") ?: "", getenv("FREEMOBILE_API_KEY") ?: "");
			expect(fn() => $client->sendMessage("Hello Cédric, from PHP!"))->to->not->throw;
		});
	}
}
