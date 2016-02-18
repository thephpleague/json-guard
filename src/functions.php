<?php

namespace Machete\Validation;

/**
 * Format an AssertionFailedException as an error array.
 *
 * @param AssertionFailedException $e
 * @return array
 */
function exceptionToError(AssertionFailedException $e)
{
    return [
        'code'        => $e->getCode(),
        'message'     => $e->getMessage(),
        'pointer'     => $e->getPointer(),
        'value'       => $e->getValue(),
        'constraints' => $e->getConstraints(),
    ];
}
