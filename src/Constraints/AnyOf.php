<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\SubSchemaValidatorFactory;
use Yuloh\JsonGuard\ValidationError;

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
