<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Client\Model;

final class ProductDetails
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $producer;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $flags = [];

    private function __construct(string $id, string $producer, string $name)
    {
        $this->id       = $id;
        $this->producer = $producer;
        $this->name     = $name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getProducer(): string
    {
        return $this->producer;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addFlag(string $flag): void
    {
        $this->flags[] = $flag;
    }

    /**
     * @return string[]
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    public static function create(string $id, string $producer, string $name): self
    {
        return new static($id, $producer, $name);
    }
}
