<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Repository;

use App\Domain\Model\Medicine\Producer;

interface ProducerRepository
{
    public function findByName(string $name): ?Producer;

    public function store(Producer $producer): void;
}
