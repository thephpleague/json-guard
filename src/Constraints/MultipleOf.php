<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\Assert;
use League\JsonGuard\ValidationError;
use League\JsonGuard\Validator;

class MultipleOf implements Constraint
{
    const KEYWORD = 'multipleOf';

    /**
     * {@inheritdoc}
     */
    public function validate($value, $parameter, Validator $validator)
    {
        Assert::type($parameter, 'number', self::KEYWORD, $validator->getPointer());
        Assert::nonNegative($parameter, self::KEYWORD, $validator->getPointer());

        if (!is_numeric($value)) {
            return null;
        }

        // for some reason fmod does not return 0 for cases like fmod(0.0075,0.0001) so I'm doing this manually.
        $quotient = $value / $parameter;
        $mod      = $quotient - floor($quotient);
        if ($mod == 0) {
            return null;
        }

        return new ValidationError(
            'Number {value} is not a multiple of {multiple_of}',
            self::KEYWORD,
            $value,
            $validator->getPointer(),
            ['value' => $value, 'multiple_of' => $parameter]
        );
    }
}
