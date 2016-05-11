<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard;
use function League\JsonGuard\asString;
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

        $message = sprintf('Array does not contain less than "%d" items', asString($parameter));
        return new ValidationError(
            $message,
            ErrorCode::MAX_ITEMS_EXCEEDED,
            $value,
            $pointer,
            ['max_items' => $parameter]
        );
    }
}
