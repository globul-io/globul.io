<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Model\Medicine;

class Producer
{
    /**
     * @var ProducerId
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string|null
     */
    protected $abstract;

    private function __construct(string $name)
    {
        $this->id   = new ProducerId();
        $this->name = $name;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ProducerId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public static function create(string $name): self
    {
        return new static($name);
    }
}
