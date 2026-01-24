<?php declare(strict_types=1);
namespace Belin\FreeMobile;

use PHPUnit\Framework\{Assert, TestCase};
use PHPUnit\Framework\Attributes\{Test, TestDox};
use function PHPUnit\Framework\{assertThat, isTrue};

/**
 * Tests the features of the {@see Client} class.
 */
#[TestDox("Client")]
final class ClientTests extends TestCase {

	#[Test, TestDox("sendMessage(): should throw a `RuntimeException` if a network error occurred.")]
	public function networkError(): void {
		$this->expectException(\RuntimeException::class);
		$client = new Client("anonymous", "secret", baseUrl: "http://localhost:10000/");
		$client->sendMessage("Hello World!");
	}

	#[Test, TestDox("sendMessage(): should throw a `RuntimeException` if the credentials are invalid.")]
	public function invalidCredentials(): void {
		$this->expectException(\RuntimeException::class);
		$client = new Client("anonymous", "secret");
		$client->sendMessage("Hello World!");
	}

	#[Test, TestDox("sendMessage(): should send SMS messages if the credentials are valid.")]
	public function validCredentials(): void {
		try {
			$client = new Client(getenv("FREEMOBILE_ACCOUNT") ?: "", getenv("FREEMOBILE_API_KEY") ?: "");
			$client->sendMessage("Hello Cédric, from PHP!");
			assertThat(true, isTrue());
		}
		catch (\RuntimeException $e) {
			Assert::fail($e->getMessage());
		}
	}
}
