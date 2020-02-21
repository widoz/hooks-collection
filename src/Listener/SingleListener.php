<?php

declare(strict_types=1);

namespace Widoz\EventListenersCollection\Listener;

/**
 * SingleListener is a listener which is called once and the automatically remove it self
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
     * @var bool
     */
    private $removed = false;

    /**
     * SingleFilter constructor
     *
     * @param callable $callback
     * @param callable $removeListener
     */
    public function __construct(callable $callback, callable $removeListener)
    {
        $this->callback = $callback;
        $this->removeListener = $removeListener;
    }

    /**
     * Single Listener Callback
     *
     * @param mixed $parameters A list of parameters passed by the dispatcher.
     *
     * @return mixed
     *
     * phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType
     * phpcs:disable Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType
     */
    public function __invoke(...$parameters)
    {
        // phpcs:enable

        if ($this->removed) {
            return null;
        }

        ($this->removeListener)($this);
        $this->removed = true;

        return ($this->callback)(...$parameters);
    }
}
