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
 * @ORM\Table(name="core23_medicine__product", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="name_unq", columns={"name"}),
 *      @ORM\UniqueConstraint(name="pzn_unq", columns={"pzn"})
 * })
 */
class Product extends \App\Domain\Model\Medicine\Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="App\Domain\Model\Medicine\ProductId")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=250)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Infrastructure\Persistence\Entity\Medicine\Producer",
     *     cascade={"persist", "merge"},
     * )
     */
    protected $producer;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    protected $pzn;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $abstract;

    /**
     * @ORM\ManyToMany(targetEntity="App\Infrastructure\Persistence\Entity\Medicine\Flag",
     *     cascade={"persist", "merge"},
     * )
     * @ORM\JoinTable(name="core23_medicine__product_flag",
     *      joinColumns={
     *          @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(name="flag_id", referencedColumnName="id")
     *      }
     * )
     */
    protected $flags = [];
}
