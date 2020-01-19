<?php

/*
 * This file is part of the Event Listeners Collection package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Widoz\EventListenersCollectionTests;

use Faker\Factory;
use Faker\Generator;
use InvalidArgumentException;
use ProjectTestsHelper\Phpunit\TestCase as PhpunitTestCase;

/**
 * Class TestCase
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class TestCase extends PhpunitTestCase
{
    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @inheritDoc
     * @throws InvalidArgumentException
     */
    protected function setUp()
    {
        $factory = new Factory();
        $this->faker = $factory->create();

        parent::setUp();
    }
}
