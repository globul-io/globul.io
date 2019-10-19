<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Client\Http\Plugin;

use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\RetryPlugin as ParentRetryPlugin;
use Http\Client\Exception\HttpException;
use Http\Promise\Promise;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RetryPlugin implements Plugin
{
    /**
     * @var ParentRetryPlugin
     */
    private $parentPlugin;

    public function __construct()
    {
        $options = [
            'retries'                => 5,
            'error_response_decider' => static function (RequestInterface $request, ResponseInterface $response) {
                // do not retry client errors
                return $response->getStatusCode() >= 300 && $response->getStatusCode() < 600;
            },
            'exception_decider'      => static function (RequestInterface $request, ClientExceptionInterface $e) {
                // do not retry client errors
                return !$e instanceof HttpException || $e->getCode() >= 300 && $e->getCode() < 600;
            },
            'error_response_delay' => __CLASS__.'::defaultErrorResponseDelay',
            'exception_delay'      => __CLASS__.'::defaultExceptionDelay',
        ];

        $this->parentPlugin = new ParentRetryPlugin($options);
    }

    public function handleRequest(RequestInterface $request, callable $next, callable $first): Promise
    {
        return $this->parentPlugin->handleRequest($request, $next, $first);
    }

    public static function defaultErrorResponseDelay(RequestInterface $request, ResponseInterface $response, int $retries): int
    {
        return static::retryDelay($retries);
    }

    public static function defaultExceptionDelay(RequestInterface $request, ClientExceptionInterface $e, int $retries): int
    {
        return static::retryDelay($retries);
    }

    private static function retryDelay(int $retries): int
    {
        return (int) pow(2, $retries) * (500000 + random_int(0, 2000));
    }
}
