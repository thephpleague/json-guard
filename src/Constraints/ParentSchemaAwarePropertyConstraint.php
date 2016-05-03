<?php

namespace League\JsonGuard\Constraints;

/**
 * This interface describes a property constraint that needs to check the parent schema
 * to properly validate.  The only constraints defined in draft 4 meeting this interface
 * are minimum and maximum, which need to check the schema for the exclusive property
 * before validating.
 */
interface ParentSchemaAwarePropertyConstraint extends Constraint
{
    /**
     * @param mixed       $value
     * @param object      $schema
     * @param mixed       $parameter
     * @param string|null $pointer
     *
     * @return \League\JsonGuard\ValidationError|null
     */
    public static function validate($value, $schema, $parameter, $pointer = null);
}
