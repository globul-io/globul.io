<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Model\Medicine;

class Flag
{
    /**
     * @var FlagId
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $abstract;

    public function getId(): FlagId
    {
        return $this->id;
    }

    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public static function createFromId(FlagId $flagId): self
    {
        $flag     = new static();
        $flag->id = $flagId;

        return $flag;
    }
}
