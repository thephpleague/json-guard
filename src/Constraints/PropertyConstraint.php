<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard\ValidationError;

/**
 * A property constraint validates a single
 * JSON object property value.
 */
interface PropertyConstraint extends Constraint
{
    /**
     * @param mixed       $value
     * @param mixed       $parameter
     * @param string|null $pointer
     *
     * @return \Yuloh\JsonGuard\ValidationError|null
     */
    public static function validate($value, $parameter, $pointer = null);
}
