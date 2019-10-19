<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Persistence\Entity\Medicine;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="core23_medicine__flag")
 */
class Flag extends \App\Domain\Model\Medicine\Flag
{
    /**
     * @ORM\Id
     * @ORM\Column(type="App\Domain\Model\Medicine\FlagId")
     */
    protected $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $abstract;
}
