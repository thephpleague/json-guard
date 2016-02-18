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

/**
 * @param string $json
 * @param bool   $assoc
 * @param int    $depth
 * @param int    $options
 * @return mixed
 * @throws \InvalidArgumentException
 */
function json_decode($json, $assoc = false, $depth = 512, $options = 0)
{
    $data = \json_decode($json, $assoc, $depth, $options);

    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new \InvalidArgumentException(sprintf('Invalid JSON: %s', json_last_error_msg()));
    }

    return $data;
}
