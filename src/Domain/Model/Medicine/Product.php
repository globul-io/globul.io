<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Model\Medicine;

class Product
{
    public const FLAG_HOMOEO = 'HOMOEO';

    /**
     * @var ProductId
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Producer
     */
    protected $producer;

    /**
     * @var string|null
     */
    protected $pzn;

    /**
     * @var string|null
     */
    protected $abstract;

    /**
     * @var Flag[]
     */
    protected $flags;

    private function __construct(Producer $producer, string $name, ?string $abstract)
    {
        $this->id       = new ProductId();
        $this->producer = $producer;
        $this->name     = $name;
        $this->abstract = $abstract;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getProducer(): Producer
    {
        return $this->producer;
    }

    public function setPzn(?string $pzn): void
    {
        $this->pzn = $pzn;
    }

    public function setAbstract(?string $abstract): void
    {
        $this->abstract = $abstract;
    }

    public function getId(): ProductId
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

    public function getPzn(): ?string
    {
        return $this->pzn;
    }

    public function addFlag(Flag $flag): void
    {
        $this->flags[] = $flag;
    }

    public function isApproved(): bool
    {
        foreach ($this->flags as $flag) {
            if (self::FLAG_HOMOEO === $flag->getId()->value()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return Flag[]
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    public static function create(Producer $producer, string $name, ?string $abstract = null): self
    {
        return new static($producer, $name, $abstract);
    }
}
