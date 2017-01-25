<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\Assert;
use League\JsonGuard\ValidationError;
use League\JsonGuard\Validator;

class AnyOf implements Constraint
{
    const KEYWORD = 'anyOf';

    /**
     * {@inheritdoc}
     */
    public function validate($value, $parameter, Validator $validator)
    {
        Assert::type($parameter, 'array', self::KEYWORD, $validator->getPointer());
        Assert::notEmpty($parameter, self::KEYWORD, $validator->getPointer());

        foreach ($parameter as $schema) {
            $validator = $validator->makeSubSchemaValidator($value, $schema, $validator->getPointer());
            if ($validator->passes()) {
                return null;
            }
        }
        return new ValidationError(
            'Failed matching any of the provided schemas.',
            self::KEYWORD,
            $value,
            $validator->getPointer(),
            ['any_of' => $parameter]
        );
    }
}
