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

namespace Widoz\EventListenersCollectionTests\Functional\Listener;

use InvalidArgumentException;
use PHPUnit\Framework\ExpectationFailedException;
use ReflectionException;
use Widoz\EventListenersCollection\Listener\NTimesListener;
use Widoz\EventListenersCollectionTests\TestCase;

/**
 * Class NTimesListenerTest
 *
 * @package Widoz\EventListenersCollectionTests\Functional\Listener
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class NTimesListenerTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInstanceIsInstantiatedCorrectly()
    {
        $nTimesListener = new NTimesListener(
            function () {
            },
            function () {
            },
            1
        );

        self::assertInstanceOf(NTimesListener::class, $nTimesListener);
    }

    /**
     * @dataProvider lessThanOneDataProvider
     * @param int $value
     * @throws InvalidArgumentException
     */
    public function testInstanceCannotBeCreatedIfTimesIsLessThanOne(int $value)
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage("{$value} must be equal or greater than one.");

        new NTimesListener(
            function () {
            },
            function () {
            },
            $value
        );
    }

    /**
     * @dataProvider timesDataProvider
     * @param int $times
     * @throws ExpectationFailedException
     * @throws InvalidArgumentException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     * @throws ReflectionException
     */
    public function testInvoke(int $times)
    {
        $nTimesListener = null;
        $expectedParameters = [$this->faker->uuid];
        $numberOfTimesCallbackGetExecuted = 0;
        $callback = $this->initializeCallback(
            $expectedParameters,
            $numberOfTimesCallbackGetExecuted
        );

        $nTimesListener = new NTimesListener(
            $callback,
            function () {
            },
            $times
        );

        $this->initializeRemoveListener($nTimesListener);

        for ($count = 1; $count <= $times; ++$count) {
            $nTimesListener(...$expectedParameters);
        }

        self::assertEquals($times, $numberOfTimeCallbackGetExecuted);
    }

    public function timesDataProvider(): array
    {
        return [
            [1],
            [2],
            [mt_rand(3, 100)],
        ];
    }

    public function lessThanOneDataProvider(): array
    {
        return [
            [0],
            [-1],
        ];
    }
}
