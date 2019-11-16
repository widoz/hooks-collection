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

namespace Widoz\HooksCollection\Hook;

use Widoz\Hooks\Hook\Hook;

/**
 * Class BaseHook
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class BaseHook implements Hook
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var callable
     */
    private $callback;

    /**
     * @var int
     */
    private $priority;

    /**
     * @var int
     */
    private $numberOfArguments;

    /**
     * Hook constructor
     * @param string $name
     * @param callable $callback
     * @param int $priority
     * @param int $numberOfArguments
     */
    public function __construct(
        string $name,
        callable $callback,
        int $priority,
        int $numberOfArguments
    ) {

        $this->name = $name;
        $this->callback = $callback;
        $this->priority = $priority;
        $this->numberOfArguments = $numberOfArguments;
    }

    /**
     * Retrieve the name / key of the hook
     *
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Retrieve the callback associated to the hook
     *
     * @return callable
     */
    public function callback(): callable
    {
        return $this->callback;
    }

    /**
     * Retrieve the hook priority
     *
     * @return int
     */
    public function priority(): int
    {
        return $this->priority;
    }

    /**
     * Retrieve the number of arguments accepted by the hook callback
     *
     * @return int
     */
    public function numberOfArguments(): int
    {
        return $this->numberOfArguments;
    }
}
