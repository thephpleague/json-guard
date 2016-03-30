<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\ValidationError;
use Yuloh\JsonGuard;

class MinLength implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_string($value) || JsonGuard\strlen($value) >= $parameter) {
            return null;
        }

        $message = sprintf('String is not at least "%s" characters long', JsonGuard\asString($parameter));

        return new ValidationError(
            $message,
            ErrorCode::INVALID_MIN_LENGTH,
            $value,
            $pointer,
            ['min_length' => $parameter]
        );
    }
}
