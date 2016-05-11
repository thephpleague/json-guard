<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class MaxProperties implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_object($value) || count(get_object_vars($value)) <= $parameter) {
            return null;
        }

        $message = sprintf('Object does not contain less than "%d" properties', JsonGuard\as_string($parameter));
        return new ValidationError(
            $message,
            ErrorCode::MAX_PROPERTIES_EXCEEDED,
            $value,
            $pointer,
            ['max_properties' => $parameter]
        );
    }
}
