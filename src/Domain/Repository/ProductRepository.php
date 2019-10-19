<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Repository;

use App\Domain\Model\Medicine\Product;
use Sonata\DatagridBundle\Pager\PagerInterface;

interface ProductRepository
{
    public function search(string $query, int $page = 1, int $limit = 25, array $sort = []): PagerInterface;

    public function store(Product $product): void;

    public function findByName(string $name): ?Product;
}
