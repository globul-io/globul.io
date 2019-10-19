<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Client\Model;

final class ProductItem
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $producer;

    private function __construct(string $id, string $name, string $producer)
    {
        $this->id   = $id;
        $this->name = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setProducer(string $producer): void
    {
        $this->producer = $producer;
    }

    public static function create(string $id, string $name, string $producer): self
    {
        return new static($id, $name, $producer);
    }
}
