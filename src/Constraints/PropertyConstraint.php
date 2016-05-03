<?php

namespace League\JsonGuard\Constraints;

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
     * @return \League\JsonGuard\ValidationError|null
     */
    public static function validate($value, $parameter, $pointer = null);
}
