<?php

namespace League\JsonGuard\Exceptions;

class ConstraintNotFoundException extends \Exception
{
    public static function forRule($rule)
    {
        return new static(sprintf('The constraint for "%s" was not found.', $rule));
    }
}
