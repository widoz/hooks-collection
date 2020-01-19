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

namespace Widoz\EventListenersCollection\Listener;

use InvalidArgumentException;
use Webmozart\Assert\Assert;

/**
 * Class NTimesListener
 *
 * @package Widoz\EventListenersCollection\Listener
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class NTimesListener
{
    /**
     * @var int
     */
    protected $times;

    /**
     * @var callable
     */
    protected $removeListener;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * NTimesHookDispatcher constructor
     *
     * @param callable $callback
     * @param callable $removeListener
     * @param int $times
     * @throws InvalidArgumentException
     */
    public function __construct(callable $callback, callable $removeListener, int $times)
    {
        if ($times < 1) {
            Assert::greaterThanEq($times, 1, "{$times} must be equal or greater than one.");
        }

        $this->callback = $callback;
        $this->removeListener = $removeListener;
        $this->times = $times;
    }

    /**
     * @inheritDoc
     *
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     * phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
     */
    public function __invoke(...$parameters)
    {
        // phpcs:enable

        static $counter = 1;

        if ($counter === $this->times) {
            $this->remove();
        }

        ++$counter;
        return $this->call(...$parameters);
    }

    /**
     * Remove Listener
     */
    protected function remove()
    {
        ($this->removeListener)($this);
    }

    /**
     * Execute the inner listener
     *
     * @param mixed ...$parameters
     * @return mixed
     *
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     * phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
     */
    protected function call(...$parameters)
    {
        return ($this->callback)(...$parameters);
    }
}
