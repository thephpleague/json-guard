<?php

namespace League\JsonGuard\Constraints;

use function League\JsonGuard\asString;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class MinItems implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_array($value) || count($value) >= $parameter) {
            return null;
        }

        $message = sprintf('Array does not contain more than "%d" items', asString($parameter));
        return new ValidationError(
            $message,
            ErrorCode::INVALID_MIN_COUNT,
            $value,
            $pointer,
            ['min_items' => $parameter]
        );
    }
}
