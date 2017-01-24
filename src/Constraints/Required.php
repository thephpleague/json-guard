<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\Assert;
use League\JsonGuard\ValidationError;
use League\JsonGuard\Validator;

class Required implements Constraint
{
    const KEYWORD = 'required';

    /**
     * {@inheritdoc}
     */
    public function validate($value, $parameter, Validator $validator)
    {
        Assert::type($parameter, 'array', self::KEYWORD, $validator->getPointer());
        Assert::notEmpty($parameter, self::KEYWORD, $validator->getPointer());

        if (!is_object($value)) {
            return null;
        }

        $actualProperties = array_keys(get_object_vars($value));
        $missing          = array_diff($parameter, $actualProperties);
        if (count($missing)) {
            return new ValidationError(
                'Required properties missing: {missing}',
                self::KEYWORD,
                $value,
                $validator->getPointer(),
                ['missing' => array_values($missing)]
            );
        }

        return null;
    }
}
