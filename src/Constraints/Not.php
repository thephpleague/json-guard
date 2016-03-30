<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\SubSchemaValidatorFactory;
use Yuloh\JsonGuard\ValidationError;

class Not implements ContainerInstanceConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($data, $parameter, SubSchemaValidatorFactory $validatorFactory, $pointer = null)
    {
        $validator = $validatorFactory->makeSubSchemaValidator($data, $parameter, $pointer);
        if ($validator->passes()) {
            return new ValidationError(
                'Data should not match the schema.',
                ErrorCode::NOT_SCHEMA,
                $data,
                $pointer,
                ['not_schema' => $parameter]
            );
        }
        return null;
    }
}
