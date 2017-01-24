<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\Assert;
use League\JsonGuard\Validator;

class AllOf implements Constraint
{
    const KEYWORD = 'allOf';

    /**
     * {@inheritdoc}
     */
    public function validate($value, $parameter, Validator $validator)
    {
        Assert::type($parameter, 'array', self::KEYWORD, $validator->getPointer());
        Assert::notEmpty($parameter, self::KEYWORD, $validator->getPointer());

        $errors = [];

        foreach ($parameter as $schema) {
            $validator = $validator->makeSubSchemaValidator($value, $schema, $validator->getPointer());
            $errors = array_merge($errors, $validator->errors());
        }

        return $errors;
    }
}
