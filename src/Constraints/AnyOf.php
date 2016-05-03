<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\ErrorCode;
use League\JsonGuard\SubSchemaValidatorFactory;
use League\JsonGuard\ValidationError;

class AnyOf implements ContainerInstanceConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($data, $parameter, SubSchemaValidatorFactory $validatorFactory, $pointer = null)
    {
        if (!is_array($parameter)) {
            return null;
        }

        foreach ($parameter as $schema) {
            $validator = $validatorFactory->makeSubSchemaValidator($data, $schema, $pointer);
            if ($validator->passes()) {
                return null;
            }
        }
        return new ValidationError(
            'Failed matching any of the provided schemas.',
            ErrorCode::ANY_OF_SCHEMA,
            $data,
            $pointer,
            ['any_of' => $parameter]
        );
    }
}
