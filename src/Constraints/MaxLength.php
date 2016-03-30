<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\ValidationError;
use Yuloh\JsonGuard;

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

        $message = sprintf('String is not at most "%s" characters long', JsonGuard\asString($parameter));
        return new ValidationError(
            $message,
            ErrorCode::INVALID_MAX_LENGTH,
            $value,
            $pointer,
            ['max_length' => $parameter]
        );
    }
}
