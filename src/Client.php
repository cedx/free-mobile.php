<?php namespace freemobile;

use Nyholm\Psr7\Uri;
use Psr\Http\Message\UriInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Sends messages by SMS to a Free Mobile account.
 */
final readonly class Client {

	/**
	 * The Free Mobile account.
	 */
	public string $account;

	/**
	 * The Free Mobile API key.
	 */
	public string $apiKey;

	/**
	 * The base URL of the remote API endpoint.
	 */
	public UriInterface $baseUrl;

	/**
	 * The underlying HTTP client.
	 */
	private HttpClientInterface $http;

	/**
	 * Creates a new client.
	 * @param string $account The Free Mobile account.
	 * @param string $apiKey The Free Mobile API key.
	 * @param string|UriInterface $baseUrl The base URL of the remote API endpoint.
	 */
	function __construct(string $account, string $apiKey, string|UriInterface $baseUrl = "https://smsapi.free-mobile.fr") {
		$url = $baseUrl instanceof UriInterface ? (string) $baseUrl : $baseUrl;
		$this->account = $account;
		$this->apiKey = $apiKey;
		$this->baseUrl = new Uri(str_ends_with($url, "/") ? $url : "$url/");
		$this->http = HttpClient::createForBaseUri((string) $this->baseUrl);
	}

	/**
	 * Sends an SMS message to the underlying account.
	 * @param string $text The message text.
	 * @throws \Psr\Http\Client\ClientExceptionInterface An error occurred while sending the message.
	 */
	function sendMessage(string $text): void {
		try {
			$response = $this->http->request("GET", "sendmsg", ["query" => [
				"msg" => mb_substr(trim($text), 0, 160),
				"pass" => $this->apiKey,
				"user" => $this->account
			]]);

			$code = intdiv($status = $response->getStatusCode(), 100);
			if ($code != 2) match ($code) {
				4 => throw new ClientException("The provided credentials are invalid.", $status),
				default => throw new ClientException("An error occurred while sending the message.", $status)
			};
		}
		catch (HttpException) {
			throw new ClientException("An error occurred while sending the message.", 500);
		}
	}
}
