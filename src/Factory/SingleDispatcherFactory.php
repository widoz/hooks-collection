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

namespace Widoz\HooksCollection\Factory;

use Widoz\Hooks\Dispatch\HookDispatcher;
use Widoz\Hooks\Factory\HookDispatcherFactory;
use Widoz\Hooks\Hook\Hook;
use Widoz\Hooks\Hook\HookInjector;
use Widoz\Hooks\Remover\HookRemover;
use Widoz\HooksCollection\Dispatch\SingleHookDispatcher;

/**
 * Class SingleDispatcherFactory
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class SingleDispatcherFactory implements HookDispatcherFactory
{
    /**
     * @var HookInjector
     */
    private $hookInjector;

    /**
     * @var HookRemover
     */
    private $hookRemover;

    /**
     * SingleDispatcherFactory constructor
     * @param HookInjector $hookInjector
     * @param HookRemover $hookRemover
     */
    public function __construct(HookInjector $hookInjector, HookRemover $hookRemover)
    {
        $this->hookInjector = $hookInjector;
        $this->hookRemover = $hookRemover;
    }

    /**
     * @inheritDoc
     */
    public function create(Hook $hook, array $extraArguments): HookDispatcher
    {
        $singleHookDispatcher = new SingleHookDispatcher($hook, $this->hookRemover);

        $this->hookInjector->addHook($hook, $singleHookDispatcher);

        return $singleHookDispatcher;
    }
}
