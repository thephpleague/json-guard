<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard;
use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\ValidationError;

class ExclusiveMin implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_numeric($value) || $value > $parameter) {
            return null;
        }

        $message = sprintf(
            'Number "%s" is not at least greater than "%d"',
            JsonGuard\asString($value),
            JsonGuard\asString($parameter)
        );
        return new ValidationError(
            $message,
            ErrorCode::INVALID_EXCLUSIVE_MIN,
            $value,
            $pointer,
            ['exclusive_min' => $parameter]
        );
    }
}
