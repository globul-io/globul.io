<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\Medicine\Product;
use App\Domain\Repository\ProductRepository;
use App\Infrastructure\Persistence\Entity\Medicine\Product as ProductEntity;
use App\Infrastructure\Persistence\Pager\EmptyPager;
use Core23\Doctrine\Adapter\ORM\AbstractEntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Sonata\DatagridBundle\Pager\Doctrine\Pager;
use Sonata\DatagridBundle\Pager\PagerInterface;

final class ProductDoctrineRepository extends AbstractEntityManager implements ProductRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(ProductEntity::class, $registry);
    }

    public function search(string $query, int $page = 1, int $limit = 25, array $sort = []): PagerInterface
    {
        if ('' === trim($query)) {
            return new EmptyPager($limit);
        }

        $qb = $this
            ->createQueryBuilder('p')
        ;

        if (0 === \count($sort)) {
            $sort = ['name' => 'asc'];
        }

        $orx = $qb->expr()->orX();
        $orx
            ->add('p.name LIKE :title_like')
            ->add('p.pzn = :title')
        ;

        $qb->andWhere($orx)
            ->setParameter('title', $query)
            ->setParameter('title_like', '%'.$query.'%')
        ;

        $this->addOrder($qb, $sort, 'p', [
            'product' => 'p',
        ]);

        return Pager::create($qb, $limit, $page);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findByName(string $name): ?Product
    {
        return $this
            ->createQueryBuilder('p')
            ->andWhere('p.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function store(Product $product): void
    {
        $this->save($product);
    }
}
