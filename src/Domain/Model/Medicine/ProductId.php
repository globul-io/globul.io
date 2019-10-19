<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Domain\Model\Medicine;

use Ramsey\Uuid\Uuid;

final class ProductId
{
    /**
     * @var string
     */
    private $id;

    public function __construct(?string $id = null)
    {
        $this->id = $id ?? Uuid::uuid4()->toString();
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
     * @param ProductId $objectId
     *
     * @return bool
     */
    public function equals(self $objectId)
    {
        return $this->value() === $objectId->value();
    }
}
