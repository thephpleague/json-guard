<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard;
use function League\JsonGuard\as_string;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class MaxItems implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_array($value) || count($value) <= $parameter) {
            return null;
        }

        $message = sprintf('Array does not contain less than "%d" items', as_string($parameter));
        return new ValidationError(
            $message,
            ErrorCode::MAX_ITEMS_EXCEEDED,
            $value,
            $pointer,
            ['max_items' => $parameter]
        );
    }
}
