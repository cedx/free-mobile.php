<?php namespace freemobile;

use Nyholm\Psr7\Uri;
use Psr\Http\Message\UriInterface;

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
	 * Creates a new client.
	 * @param string $account The Free Mobile account.
	 * @param string $apiKey The Free Mobile API key.
	 * @param string|UriInterface $baseUrl The base URL of the remote API endpoint.
	 */
	function __construct(string $account, string $apiKey, string|UriInterface $baseUrl = "https://smsapi.free-mobile.fr") {
		$this->account = $account;
		$this->apiKey = $apiKey;
		$this->baseUrl = new Uri(rtrim($baseUrl, "/"));
	}

	/**
	 * Sends an SMS message to the underlying account.
	 * @param string $text The message text.
	 * @throws \RuntimeException An error occurred while sending the message.
	 */
	function sendMessage(string $text): void {
		$handle = curl_init($this->baseUrl->withPath("{$this->baseUrl->getPath()}/sendmsg")->withQuery(http_build_query([
			"msg" => mb_substr(trim($text), 0, 160),
			"pass" => $this->apiKey,
			"user" => $this->account
		], arg_separator: "&", encoding_type: PHP_QUERY_RFC3986)));

		if (!$handle) throw new \RuntimeException("Unable to allocate the cURL handle.", 500);
		curl_setopt_array($handle, [CURLOPT_FOLLOWLOCATION => true, CURLOPT_RETURNTRANSFER => true, CURLOPT_USERAGENT => "PHP/".PHP_MAJOR_VERSION]);
		if (curl_exec($handle) === false) throw new \RuntimeException(curl_error($handle) ?: "An error occurred while sending the message.", 500);

		$code = intdiv($status = curl_getinfo($handle, CURLINFO_RESPONSE_CODE), 100);
		if ($code != 2) match ($code) {
			4 => throw new \RuntimeException("The provided credentials are invalid.", $status),
			default => throw new \RuntimeException("An error occurred while sending the message.", $status)
		};
	}
}
