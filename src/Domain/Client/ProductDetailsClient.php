<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Client;

use App\Domain\Client\Exception\ClientException;
use App\Domain\Client\Model\ProductDetails;

interface ProductDetailsClient
{
    /**
     * @throws ClientException
     */
    public function details(string $id): ProductDetails;
}
