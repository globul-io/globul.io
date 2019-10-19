<?php

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Infrastructure\Persistence\Batch;

use Closure;
use Doctrine\ORM\EntityManagerInterface;

final class BatchProcessor
{
    private const BATCH_SIZE = 20;

    /**
     * @var EntityManagerInterface
     */
    private $registry;

    public function __construct(EntityManagerInterface $registry)
    {
        $this->registry = $registry;
    }

    public function process(iterable $iterator, Closure $closure): void
    {
        $step = 0;

        foreach ($iterator as $item) {
            $closure($item);

            if (++$step > self::BATCH_SIZE) {
                $step = 0;

                $this->flush();
            }
        }

        $this->flush();
    }

    /**
     * Commits all actions and flush the memory.
     */
    private function flush(): void
    {
        $this->registry->flush();
        $this->registry->clear();

        gc_collect_cycles();
    }
}
