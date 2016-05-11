<?php

namespace League\JsonGuard\Constraints;

use function League\JsonGuard\as_string;
use function League\JsonGuard\delimit_pattern;
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

        if (preg_match(delimit_pattern($pattern), $value) === 1) {
            return null;
        }

        $message = sprintf('Value "%s" does not match the given pattern.', as_string($value));
        return new ValidationError($message, ErrorCode::INVALID_PATTERN, $value, $pointer, ['pattern' => $pattern]);
    }
}
