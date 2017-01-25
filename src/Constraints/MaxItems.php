<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\Assert;
use League\JsonGuard\ValidationError;
use League\JsonGuard\Validator;

class MaxItems implements Constraint
{
    const KEYWORD = 'maxItems';

    /**
     * {@inheritdoc}
     */
    public function validate($value, $parameter, Validator $validator)
    {
        Assert::type($parameter, 'integer', self::KEYWORD, $validator->getPointer());
        Assert::nonNegative($parameter, self::KEYWORD, $validator->getPointer());

        if (!is_array($value) || count($value) <= $parameter) {
            return null;
        }

        return new ValidationError(
            'Array does not contain less than {max_items} items',
            self::KEYWORD,
            $value,
            $validator->getPointer(),
            ['max_items' => $parameter]
        );
    }
}
