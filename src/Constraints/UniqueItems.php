<?php

namespace League\JsonGuard\Constraints;

use function League\JsonGuard\as_string;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class UniqueItems implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_array($value)) {
            return null;
        }

        if (count($value) === count(array_unique(array_map('serialize', $value)))) {
            return null;
        }

        $message = sprintf('Array "%s" is not unique.', as_string($value));
        return new ValidationError($message, ErrorCode::NOT_UNIQUE_ITEM, $value, $pointer);
    }
}
