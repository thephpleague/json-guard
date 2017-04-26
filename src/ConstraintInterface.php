<?php

namespace League\JsonGuard;

interface ConstraintInterface
{
    /**
     * @param mixed $value
     * @param mixed $parameter
     * @param Validator $validator
     *
     * @return \League\JsonGuard\ValidationError|null
     *
     * @throws \League\JsonGuard\Exception\InvalidSchemaException
     */
    public function validate($value, $parameter, Validator $validator);
}
