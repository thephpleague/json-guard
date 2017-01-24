<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\Assert;
use League\JsonGuard\ValidationError;
use League\JsonGuard\Validator;

class Enum implements Constraint
{
    const KEYWORD = 'enum';

    /**
     * {@inheritdoc}
     */
    public function validate($value, $parameter, Validator $validator)
    {
        Assert::type($parameter, 'array', self::KEYWORD, $validator->getPointer());

        if (in_array($value, $parameter, true)) {
            return null;
        }

        return new ValidationError(
            'Value {value} is not one of: {choices}',
            self::KEYWORD,
            $value,
            $validator->getPointer(),
            ['choices' => $parameter, 'value' => $value]
        );
    }
}
