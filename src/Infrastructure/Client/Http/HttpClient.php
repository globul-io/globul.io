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
use Psr\Http\Message\ResponseInterface;

interface HttpClient
{
    /**
     * Loads a page and returns the page body.
     *
     * @throws HttpClientException
     */
    public function sendRequest(string $url): ?ResponseInterface;
}
