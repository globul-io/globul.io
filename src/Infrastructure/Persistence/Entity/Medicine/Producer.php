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
 * @ORM\Table(name="core23_medicine__producer", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="name_unq", columns={"name"})
 * })
 */
class Producer extends \App\Domain\Model\Medicine\Producer
{
    /**
     * @ORM\Id
     * @ORM\Column(type="App\Domain\Model\Medicine\ProducerId")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    protected $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $abstract;
}
