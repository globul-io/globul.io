<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\Medicine\Producer;
use App\Domain\Repository\ProducerRepository;
use App\Infrastructure\Persistence\Entity\Medicine\Producer as ProducerEnity;
use Core23\Doctrine\Adapter\ORM\AbstractEntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

final class ProducerDoctrineRepository extends AbstractEntityManager implements ProducerRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(ProducerEnity::class, $registry);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByName(string $name): ?Producer
    {
        return $this
            ->createQueryBuilder('p')
            ->andWhere('p.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function store(Producer $producer): void
    {
        $this->save($producer);
    }
}
