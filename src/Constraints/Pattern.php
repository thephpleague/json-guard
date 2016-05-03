<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class Pattern implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $pattern, $pointer = null)
    {
        if (!is_string($value)) {
            return null;
        }

        if (preg_match(JsonGuard\delimitPattern($pattern), $value) === 1) {
            return null;
        }

        $message = sprintf('Value "%s" does not match the given pattern.', JsonGuard\asString($value));
        return new ValidationError($message, ErrorCode::INVALID_PATTERN, $value, $pointer, ['pattern' => $pattern]);
    }
}
