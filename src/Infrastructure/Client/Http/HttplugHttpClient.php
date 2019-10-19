<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Client\Http;

use App\Infrastructure\Client\Http\Exception\HttpClientException;
use Http\Client\Exception;
use Http\Client\HttpClient as Client;
use Http\Message\MessageFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

final class HttplugHttpClient implements HttpClient, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var MessageFactory
     */
    private $messageFactory;

    /**
     * Initialize client.
     */
    public function __construct(Client $client, MessageFactory $messageFactory)
    {
        $this->client         = $client;
        $this->messageFactory = $messageFactory;
        $this->logger         = new NullLogger();
    }

    public function sendRequest(string $url): ResponseInterface
    {
        $request = $this->messageFactory->createRequest('GET', $url);

        try {
            $response = $this->client->sendRequest($request);
        } catch (\Exception $e) {
            throw new HttpClientException(
                sprintf('Error crawling url "%s"', $url),
                $e->getCode(),
                $e
            );
        } catch (Exception $e) {
            throw new HttpClientException(
                sprintf('Error crawling url "%s"', $url),
                $e->getCode(),
                $e
            );
        }

        return $response;
    }
}
