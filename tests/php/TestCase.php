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

use Closure;
use Faker\Factory;
use Faker\Generator;
use InvalidArgumentException;
use ProjectTestsHelper\Phpunit\TestCase as PhpunitTestCase;
use ReflectionException;
use ReflectionProperty;
use Webmozart\Assert\Assert;

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
     */
    protected function setUp()
    {
        $factory = new Factory();
        $this->faker = $factory->create();

        parent::setUp();
    }

    /**
     * Necessary step in order to pass the SUT to the removeListener callback.
     * We want to ensure the remove listener callback will get passed the instance
     * of the SUT because it's that invokable we want to remove from the Dispatcher.
     *
     * @param callable $sutListener
     * @throws ReflectionException
     */
    protected function initializeRemoveListener(callable $sutListener)
    {
        // Necessary step in order to pass the SUT to the removeListener callback
        // We want to ensure the remove listener callback will get passed the instance
        // of the SUT because it's that invokable we want to remove from the Dispatcher.
        $propertyReflection = new ReflectionProperty($sutListener, 'removeListener');
        $propertyReflection->setAccessible(true);
        $propertyReflection->setValue(
            $sutListener,
            function (callable $listener) use ($sutListener) {
                self::assertEquals($sutListener, $listener);
            }
        );
    }

    /**
     * @param $expectedParameters
     * @param int $numberOfTimesCallbackGetExecuted
     * @throws InvalidArgumentException
     * @return Closure
     *
     * @phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     */
    protected function initializeCallback(
        $expectedParameters,
        int &$numberOfTimesCallbackGetExecuted
    ): callable {
        // phpcs:enable

        Assert::same(
            0,
            $numberOfTimesCallbackGetExecuted,
            "{$numberOfTimesCallbackGetExecuted} must be always 0."
        );

        // @phpcs:ignore Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
        return function (...$parameters) use (
            $expectedParameters,
            &$numberOfTimesCallbackGetExecuted
        ): void {
            self::assertEquals($parameters, $expectedParameters);
            ++$numberOfTimesCallbackGetExecuted;
        };
    }
}
