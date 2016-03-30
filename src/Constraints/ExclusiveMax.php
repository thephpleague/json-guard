<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard;
use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\ValidationError;

class ExclusiveMax implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_numeric($value) || $value < $parameter) {
            return null;
        }

        $message = sprintf(
            'Number "%s" is not less than "%d"',
            JsonGuard\asString($value),
            JsonGuard\asString($parameter)
        );
        return new ValidationError(
            $message,
            ErrorCode::INVALID_EXCLUSIVE_MAX,
            $value,
            $pointer,
            ['exclusive_max' => $parameter]
        );
    }
}
