<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Http\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

final class SearchProduct
{
    /**
     * @Assert\NotNull()
     * @Assert\Length(
     *     min="3"
     * )
     *
     * @var string|null
     */
    private $query;

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(?string $query): void
    {
        $this->query = $query;
    }
}
