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

namespace Widoz\HooksCollection\Exception;

use UnexpectedValueException;

/**
 * Class NumberException
 *
 * @author Guido Scialfa <dev@guidoscialfa.com>
 */
class NumberException extends UnexpectedValueException
{
    /**
     * @param int $value
     * @return NumberException
     */
    public static function becauseValueIsLessThanOne(int $value): NumberException
    {
        return new self("{$value} must be greater than zero");
    }
}
