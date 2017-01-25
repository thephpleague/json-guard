<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\Validator;

interface Constraint
{
    /**
     * @param mixed $value
     * @param mixed $parameter
     * @param Validator $validator
     *
     * @return \League\JsonGuard\ValidationError|null
     */
    public function validate($value, $parameter, Validator $validator);
}
