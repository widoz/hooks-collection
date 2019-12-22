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
     * @var HookRemover
     */
    private $hookRemover;

    /**
     * SingleDispatcherFactory constructor
     * @param HookRemover $hookRemover
     */
    public function __construct(HookRemover $hookRemover)
    {
        $this->hookRemover = $hookRemover;
    }

    /**
     * @inheritDoc
     */
    public function create(Hook $hook, array $extraArguments): HookDispatcher
    {
        return new SingleHookDispatcher($hook, $this->hookRemover);
    }
}
