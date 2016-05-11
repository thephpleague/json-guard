<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;
use function League\JsonGuard\asString;

class Enum implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        if (!is_array($parameter)) {
            return null;
        }

        if (in_array($value, $parameter, true)) {
            return null;
        }

        $message = sprintf(
            'Value "%s" is not one of: %s',
            asString($value),
            implode(', ', array_map('League\JsonGuard\asString', $parameter))
        );
        return new ValidationError($message, ErrorCode::INVALID_ENUM, $value, $pointer, ['choices' => $parameter]);
    }
}
