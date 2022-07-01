<?php declare(strict_types=1);
namespace FreeMobile;

use Psr\Http\Client\ClientExceptionInterface;

/**
 * An exception caused by an error in a `Client` request.
 */
class ClientException extends \RuntimeException implements ClientExceptionInterface {}
