<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard;
use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\ValidationError;

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
