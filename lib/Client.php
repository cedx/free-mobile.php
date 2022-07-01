<?php declare(strict_types=1);
namespace FreeMobile;

use Psr\Http\Message\UriInterface;
use Symfony\Component\HttpClient\Psr18Client;

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
	 * @throws \Psr\Http\Client\ClientExceptionInterface An error occurred while sending the message.
	 */
	function sendMessage(string $text): void {
		$url = $this->baseUrl->withPath("{$this->baseUrl->getPath()}sendmsg")->withQuery(http_build_query([
			"msg" => mb_substr(trim($text), 0, 160),
			"pass" => $this->apiKey,
			"user" => $this->account
		], arg_separator: "&", encoding_type: PHP_QUERY_RFC3986));

		$request = $this->http->createRequest("GET", $url);
		$response = $this->http->sendRequest($request);

		$status = $response->getStatusCode();
		$code = intdiv($status, 100);
		if ($code != 2) switch ($code) {
			case 4: throw new ClientException("The provided credentials are invalid.", $status);
			default: throw new ClientException("An error occurred while sending the message.", $status);
		}
	}
}
