<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Client;

use App\Domain\Client\Exception\ClientException;
use App\Domain\Client\Model\ProductItem;

interface ProductClient
{
    /**
     * @throws ClientException
     *
     * @return iterable<ProductItem>
     */
    public function products(string $startChar, int $limit = 100, int $page = 1): iterable;

    /**
     * @throws ClientException
     */
    public function count(string $startChar): int;
}
