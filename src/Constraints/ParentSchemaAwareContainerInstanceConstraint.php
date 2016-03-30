<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard\SubSchemaValidatorFactory;
use Yuloh\JsonGuard\ValidationError;

/**
 * Some constraints for container instances (arrays, objects)
 * also have to be aware of the parent schema to validate.
 *
 * For Example, the "additionalProperties" constraint
 * requires knowing the value of "properties" and
 * "pattern properties" to validate.
 */
interface ParentSchemaAwareContainerInstanceConstraint extends Constraint
{
    /**
     * @param mixed                     $data
     * @param object                    $schema
     * @param mixed                     $parameter
     * @param SubSchemaValidatorFactory $validatorFactory
     * @param string|null               $pointer
     *
     * @return ValidationError|ValidationError[]|null
     */
    public static function validate(
        $data,
        $schema,
        $parameter,
        SubSchemaValidatorFactory $validatorFactory,
        $pointer = null
    );
}
