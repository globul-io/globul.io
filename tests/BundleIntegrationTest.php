<?php

declare(strict_types=1);

/*
 * (c) Christian Gripp <mail@core23.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class BundleIntegrationTest extends WebTestCase
{
    public function testStartup(): void
    {
        $client = self::createClient();

        $client->request('GET', '/search');

        static::assertSame(200, $client->getResponse()->getStatusCode());
    }
}
