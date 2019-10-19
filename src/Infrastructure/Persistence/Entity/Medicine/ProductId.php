<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Persistence\Entity\Medicine;

use App\Infrastructure\Persistence\Entity\DoctrineEntityId;

class ProductId extends DoctrineEntityId
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return \App\Domain\Model\Medicine\ProductId::class;
    }
}
