<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard;
use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\ValidationError;

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

        $message = sprintf('Array does not contain less than "%d" items', JsonGuard\asString($parameter));
        return new ValidationError(
            $message,
            ErrorCode::INVALID_MAX_COUNT,
            $value,
            $pointer,
            ['max_items' => $parameter]
        );
    }
}
