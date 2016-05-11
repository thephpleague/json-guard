<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class MaxLength implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_string($value) || JsonGuard\strlen($value) <= $parameter) {
            return null;
        }

        $message = sprintf('String is not at most "%s" characters long', JsonGuard\as_string($parameter));
        return new ValidationError(
            $message,
            ErrorCode::INVALID_MAX_LENGTH,
            $value,
            $pointer,
            ['max_length' => $parameter]
        );
    }
}
