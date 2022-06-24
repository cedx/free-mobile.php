<?php declare(strict_types=1);
namespace FreeMobile;

use Psr\Http\Message\UriInterface;
use Symfony\Component\HttpClient\{Psr18Client, Psr18NetworkException, Psr18RequestException};
use Symfony\Component\HttpClient\Exception\TransportException;

/**
 * Sends messages by SMS to a Free Mobile account.
 */
class Client {

	/**
	 * The Free Mobile account.
	 * @var string
	 */
	public readonly string $account;

	/**
	 * The Free Mobile API key.
	 * @var string
	 */
	public readonly string $apiKey;

	/**
	 * The base URL of the remote API endpoint.
	 * @var UriInterface
	 */
	public readonly UriInterface $baseUrl;

	/**
	 * The underlying HTTP client.
	 * @var Psr18Client
	 */
	private Psr18Client $http;

	/**
	 * Creates a new client.
	 * @param string $account The Free Mobile account.
	 * @param string $apiKey The Free Mobile API key.
	 * @param string $baseUrl The base URL of the remote API endpoint.
	 */
	function __construct(string $account, string $apiKey, string $baseUrl = "https://smsapi.free-mobile.fr/") {
		$this->http = new Psr18Client;
		$this->account = $account;
		$this->apiKey = $apiKey;
		$this->baseUrl = $this->http->createUri($baseUrl);
	}

	/**
	 * Sends a SMS message to the underlying account.
	 * @param string $text The message text.
	 * @throws \Psr\Http\Client\NetworkExceptionInterface An error occurred while sending the message.
	 * @throws \Psr\Http\Client\RequestExceptionInterface The provided credentials are invalid.
	 */
	function sendMessage(string $text): void {
		$url = $this->baseUrl->withPath("{$this->baseUrl->getPath()}sendmsg")->withQuery(http_build_query([
			"msg" => mb_substr(trim($text), 0, 160),
			"pass" => $this->apiKey,
			"user" => $this->account
		], arg_separator: "&", encoding_type: PHP_QUERY_RFC3986));

		$request = $this->http->createRequest("GET", $url);
		$response = $this->http->sendRequest($request);

		$statusCode = $response->getStatusCode();
		$error = new TransportException($response->getReasonPhrase(), $statusCode);

		switch (intdiv($statusCode, 100)) {
			case 4: throw new Psr18RequestException($error, $request);
			case 5: throw new Psr18NetworkException($error, $request);
		}
	}
}
