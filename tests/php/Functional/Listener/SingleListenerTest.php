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
use Widoz\EventListenersCollection\Listener\SingleListener;
use Widoz\EventListenersCollectionTests\TestCase;

/**
 * Class SingleListenerTest
 *
 * @package Widoz\EventListenersCollectionTests\Functional\Listener
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class SingleListenerTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     * @throws ExpectationFailedException
     * @throws ReflectionException
     * @throws \SebastianBergmann\RecursionContext\InvalidArgumentException
     */
    public function testInvoke()
    {
        $expectedParameters = [$this->faker->uuid];
        $numberOfTimesCallbackGetExecuted = 0;
        $callback = $this->initializeCallback(
            $expectedParameters,
            $numberOfTimesCallbackGetExecuted
        );
        $singleListener = new SingleListener(
            $callback,
            function () {
            }
        );

        $this->initializeRemoveListener($singleListener);

        $singleListener(...$expectedParameters);
        // Run it multiple times. $numberOfTimesCallbackGetExecuted should be equal to 1 always
        // despite the number of times the listener is called.
        $singleListener(...$expectedParameters);

        self::assertEquals(1, $numberOfTimesCallbackGetExecuted);
    }
}
