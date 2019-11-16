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

use Widoz\Hooks\Dispatch\HookDispatcher;
use Widoz\Hooks\Hook\Hook;
use Widoz\Hooks\Remover\HookRemover;

/**
 * Class SingleHookDispatcher
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class SingleHookDispatcher implements HookDispatcher
{
    /**
     * @var Hook
     */
    private $hook;

    /**
     * @var HookRemover
     */
    private $hookRemover;

    /**
     * SingleFilter constructor
     * @param Hook $hook
     * @param HookRemover $hookRemover
     */
    public function __construct(Hook $hook, HookRemover $hookRemover)
    {
        $this->hook = $hook;
        $this->hookRemover = $hookRemover;
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

        $this->hookRemover->remove($this->hook, $this);

        return $this->hook->callback()(...$parameters);
    }
}
