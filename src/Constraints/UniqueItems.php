<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard;
use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\ValidationError;

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

        $message = sprintf('Array "%s" is not unique.', JsonGuard\asString($value));
        return new ValidationError($message, ErrorCode::VALUE_NOT_UNIQUE, $value, $pointer);
    }
}
