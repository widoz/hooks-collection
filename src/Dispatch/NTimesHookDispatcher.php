<?php # -*- coding: utf-8 -*-

/*
 * This file is part of the Hooks package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Widoz\HooksCollection\Dispatch;

use Widoz\Hooks\Dispatch\RemoveCapableHookDispatcher;
use Widoz\Hooks\Hook\Hook;
use Widoz\Hooks\Remover\HookRemover;
use Widoz\HooksCollection\NumberException;

/**
 * Class NTimesHookDispatcher
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class NTimesHookDispatcher implements RemoveCapableHookDispatcher
{
    /**
     * @var int
     */
    private $times;

    /**
     * @var Hook
     */
    private $hook;

    /**
     * @var HookRemover
     */
    private $hookRemover;

    /**
     * NTimesHookDispatcher constructor
     * @param Hook $hook
     * @param HookRemover $hookRemover
     * @param int $times
     * @throws NumberException
     */
    public function __construct(
        Hook $hook,
        HookRemover $hookRemover,
        int $times
    ) {

        if ($times <= 0) {
            throw NumberException::becauseValueIsLessThanZero($times);
        }

        $this->times = $times;
        $this->hook = $hook;
        $this->hookRemover = $hookRemover;
    }

    /**
     * @inheritDoc
     */
    public function remove(): void
    {
        $this->hookRemover->remove($this->hook, $this);
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
            return $this->hook->callback()(...$parameters);
        }

        $counter++;
        return $this->hook->callback()(...$parameters);
    }
}
