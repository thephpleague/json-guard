<?php

namespace League\JsonGuard\Constraints;

use function League\JsonGuard\asString;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class Max implements ParentSchemaAwarePropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($value, $schema, $parameter, $pointer = null)
    {
        if (isset($schema->exclusiveMaximum) && $schema->exclusiveMaximum === true) {
            return self::validateExclusiveMax($value, $parameter, $pointer);
        }

        return self::validateMax($value, $parameter, $pointer);
    }

    /**
     * @param mixed       $value
     * @param mixed       $parameter
     * @param string|null $pointer
     *
     * @return \League\JsonGuard\ValidationError|null
     */
    public static function validateMax($value, $parameter, $pointer = null)
    {
        if (!is_numeric($value) || $value <= $parameter) {
            return null;
        }

        $message = sprintf(
            'Number "%s" is not at most "%d"',
            asString($value),
            asString($parameter)
        );
        return new ValidationError($message, ErrorCode::INVALID_MAX, $value, $pointer, ['max' => $parameter]);
    }

    /**
     * @param mixed       $value
     * @param mixed       $parameter
     * @param string|null $pointer
     *
     * @return \League\JsonGuard\ValidationError|null
     */
    public static function validateExclusiveMax($value, $parameter, $pointer = null)
    {
        if (!is_numeric($value) || $value < $parameter) {
            return null;
        }

        $message = sprintf(
            'Number "%s" is not less than "%d"',
            asString($value),
            asString($parameter)
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
