<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard;
use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\ValidationError;

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
            JsonGuard\asString($value),
            implode(', ', array_map('Yuloh\JsonGuard\asString', $parameter))
        );
        return new ValidationError($message, ErrorCode::INVALID_ENUM, $value, $pointer, ['choices' => $parameter]);
    }
}
