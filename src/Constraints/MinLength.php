<?php

namespace League\JsonGuard\Constraints;

use function League\JsonGuard\strlen;
use function League\JsonGuard\asString;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class MinLength implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_string($value) || strlen($value) >= $parameter) {
            return null;
        }

        $message = sprintf('String is not at least "%s" characters long', asString($parameter));

        return new ValidationError(
            $message,
            ErrorCode::INVALID_MIN_LENGTH,
            $value,
            $pointer,
            ['min_length' => $parameter]
        );
    }
}
