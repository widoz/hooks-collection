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

use Widoz\HooksCollection\Exception\NumberException;

/**
 * Class NTimesListener
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class NTimesListener
{
    /**
     * @var int
     */
    private $times;

    /**
     * @var callable
     */
    private $removeListener;

    /**
     * @var callable
     */
    private $callback;

    /**
     * NTimesHookDispatcher constructor
     * @param callable $callback
     * @param callable $removeListener
     * @param int $times
     * @throws NumberException
     */
    public function __construct(callable $callback, callable $removeListener, int $times)
    {
        if ($times <= 0) {
            throw NumberException::becauseValueIsLessThanZero($times);
        }

        $this->times = $times;
        $this->removeListener = $removeListener;
        $this->callback = $callback;
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

        static $counter = 0;

        if ($counter >= $this->times - 1) {
            $this->remove();
            return $this->call(...$parameters);
        }

        $counter++;
        return $this->call(...$parameters);
    }

    /**
     * Remove Listener
     */
    private function remove()
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
    private function call(...$parameters)
    {
        return ($this->callback)(...$parameters);
    }
}
