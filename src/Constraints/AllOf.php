<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard\SubSchemaValidatorFactory;

class AllOf implements ContainerInstanceConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($data, $parameter, SubSchemaValidatorFactory $validatorFactory, $pointer = null)
    {
        if (!is_array($parameter)) {
            return null;
        }

        $errors = array_merge(array_filter(array_map(function ($schema) use ($data, $validatorFactory, $pointer) {
            $validator = $validatorFactory->makeSubSchemaValidator($data, $schema, $pointer);
            if ($validator->passes()) {
                return null;
            }
            return $validator->errors();
        }, $parameter)));

        return $errors ?: null;
    }
}
