<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Model\Medicine;

final class FlagId
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value();
    }

    public function value(): string
    {
        return $this->id;
    }

    /**
     * @param FlagId $objectId
     *
     * @return bool
     */
    public function equals(self $objectId)
    {
        return $this->value() === $objectId->value();
    }
}
