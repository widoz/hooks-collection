<?php

/*
 * This file is part of the Hooks Collection package.
 *
 * (c) Guido Scialfa <dev@guidoscialfa.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Widoz\HooksCollection\Listener;

/**
 * Class SingleListener
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class SingleListener
{
    /**
     * @var callable
     */
    private $removeListener;

    /**
     * @var callable
     */
    private $callback;

    /**
     * SingleFilter constructor
     * @param callable $callback
     * @param callable $removeListener
     */
    public function __construct(callable $callback, callable $removeListener)
    {
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

        ($this->removeListener)($this);

        return ($this->callback)(...$parameters);
    }
}
