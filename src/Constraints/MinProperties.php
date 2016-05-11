<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class MinProperties implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $min, $pointer = null)
    {
        if (!is_object($value) || count(get_object_vars($value)) >= $min) {
            return null;
        }

        $message = sprintf('Object does not contain at least "%d" properties', JsonGuard\as_string($min));
        return new ValidationError(
            $message,
            ErrorCode::INVALID_MIN_COUNT,
            $value,
            $pointer,
            ['min_properties' => $min]
        );
    }
}
