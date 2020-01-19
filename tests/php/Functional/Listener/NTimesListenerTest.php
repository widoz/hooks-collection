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
use ReflectionProperty;
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
        $numberOfTimeCallbackGetExecuted = 0;
        $callback = function (...$parameters) use (
            $expectedParameters,
            &$numberOfTimeCallbackGetExecuted
        ) {
            self::assertEquals($parameters, $expectedParameters);
            ++$numberOfTimeCallbackGetExecuted;
        };

        $nTimesListener = new NTimesListener(
            $callback,
            function () {
            },
            $times
        );

        // Necessary step in order to pass the SUT to the removeListener callback
        // We want to ensure the remove listener callback will get passed the instance
        // of the SUT because it's that invokable we want to remove from the Dispatcher.
        $propertyReflection = new ReflectionProperty($nTimesListener, 'removeListener');
        $propertyReflection->setAccessible(true);
        $propertyReflection->setValue(
            $nTimesListener,
            function (callable $listener) use ($nTimesListener) {
                self::assertInstanceOf(NTimesListener::class, $listener);
                self::assertEquals($nTimesListener, $listener);
            }
        );

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
