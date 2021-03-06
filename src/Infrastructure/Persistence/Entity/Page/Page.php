<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Persistence\Entity\Page;

use Doctrine\ORM\Mapping as ORM;
use Sonata\PageBundle\Entity\BasePage;

/**
 * @ORM\Entity
 * @ORM\Table(name="core23_page__page")
 */
class Page extends BasePage
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }
}
