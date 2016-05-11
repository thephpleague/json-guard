<?php

namespace League\JsonGuard\Constraints;

use function League\JsonGuard\strlen;
use function League\JsonGuard\as_string;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class MaxLength implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_string($value) || strlen($value) <= $parameter) {
            return null;
        }

        $message = sprintf('String is not at most "%s" characters long', as_string($parameter));
        return new ValidationError(
            $message,
            ErrorCode::INVALID_MAX_LENGTH,
            $value,
            $pointer,
            ['max_length' => $parameter]
        );
    }
}
