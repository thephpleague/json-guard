<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard\SubSchemaValidatorFactory;
use Yuloh\JsonGuard\ValidationError;

/**
 * A container instance constraint validates a container instance
 * as defined in the Json Schema Validation draft. A container
 * instance is defined as "both array and object instances".
 */
interface ContainerInstanceConstraint extends Constraint
{
    /**
     * @param mixed $data
     * @param mixed $parameter
     * @param SubSchemaValidatorFactory $validatorFactory
     * @param string|null $pointer
     *
     * @return ValidationError|ValidationError[]|null
     */
    public static function validate($data, $parameter, SubSchemaValidatorFactory $validatorFactory, $pointer = null);
}
