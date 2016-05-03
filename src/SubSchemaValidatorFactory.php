<?php

namespace League\JsonGuard;

interface SubSchemaValidatorFactory
{
    /**
     * Create a new validator for the given schema.
     *
     * @param mixed  $data
     * @param object $schema
     * @param string $pointer
     *
     * @return Validator
     */
    public function makeSubSchemaValidator($data, $schema, $pointer);
}
