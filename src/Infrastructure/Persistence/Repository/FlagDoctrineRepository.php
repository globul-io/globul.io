<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\Medicine\Flag;
use App\Domain\Model\Medicine\FlagId;
use App\Domain\Repository\FlagRepository;
use App\Infrastructure\Persistence\Entity\Medicine\Flag as FlagEntity;
use Core23\Doctrine\Adapter\ORM\AbstractEntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;

class FlagDoctrineRepository extends AbstractEntityManager implements FlagRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(FlagEntity::class, $registry);
    }

    /**
     * @throws ORMException
     */
    public function getById(FlagId $id): ?Flag
    {
        $flag =  $this->find($id);

        if ($flag instanceof FlagEntity) {
            return $flag;
        }

        return null;
    }
}
