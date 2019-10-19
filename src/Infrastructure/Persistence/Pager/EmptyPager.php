<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Persistence\Pager;

use Sonata\DatagridBundle\Pager\PagerInterface;

final class EmptyPager implements \Iterator, \Countable, \Serializable, PagerInterface
{
    /**
     * @var int
     */
    private $max;

    public function __construct(int $max)
    {
        $this->max = $max;
    }

    public function current()
    {
        return null;
    }

    public function next()
    {
    }

    public function key()
    {
        return null;
    }

    public function valid()
    {
        return true;
    }

    public function rewind()
    {
    }

    public function serialize()
    {
        return serialize([]);
    }

    public function unserialize($serialized)
    {
    }

    public function count()
    {
        return null;
    }

    public function init()
    {
    }

    public function getMaxPerPage()
    {
        return $this->max;
    }

    public function setMaxPerPage($max)
    {
        $this->max = $max;
    }

    public function setPage($page)
    {
    }

    public function setQuery($query)
    {
    }

    public function getResults()
    {
        return [];
    }
}
