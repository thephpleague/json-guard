<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard;
use League\JsonGuard\Assert;
use League\JsonGuard\ValidationError;
use League\JsonGuard\Validator;

class Max implements Constraint
{
    const KEYWORD           = 'maximum';
    const EXCLUSIVE_KEYWORD = 'exclusiveMaximum';

    /**
     * {@inheritdoc}
     */
    public function validate($value, $parameter, Validator $validator)
    {
        Assert::type($parameter, 'number', self::KEYWORD, $validator->getPointer());

        if (isset($validator->getSchema()->exclusiveMaximum) && $validator->getSchema()->exclusiveMaximum === true) {
            return self::validateExclusiveMax($value, $parameter, $validator->getPointer());
        }

        return self::validateMax($value, $parameter, $validator->getPointer());
    }

    /**
     * @param mixed       $value
     * @param mixed       $parameter
     * @param string|null $pointer
     *
     * @return \League\JsonGuard\ValidationError|null
     */
    public static function validateMax($value, $parameter, $pointer)
    {
        if (!is_numeric($value) ||
            JsonGuard\compare($value, $parameter) === -1 || JsonGuard\compare($value, $parameter) === 0) {
            return null;
        }

        return new ValidationError(
            'Value {value} is not at most {max}',
            self::KEYWORD,
            $value,
            $pointer,
            ['value' => $value, 'max' => $parameter]
        );
    }

    /**
     * @param mixed       $value
     * @param mixed       $parameter
     * @param string|null $pointer
     *
     * @return \League\JsonGuard\ValidationError|null
     */
    public static function validateExclusiveMax($value, $parameter, $pointer)
    {
        if (!is_numeric($value) || JsonGuard\compare($value, $parameter) === -1) {
            return null;
        }

        return new ValidationError(
            'Value {value} is not less than {exclusive_max}',
            self::EXCLUSIVE_KEYWORD,
            $value,
            $pointer,
            ['value' => $value, 'exclusive_max' => $parameter]
        );
    }
}
