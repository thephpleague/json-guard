<?php

namespace League\JsonGuard;

interface FormatExtension
{
    /**
     * @param string      $value   The value to validate
     * @param string|null $pointer A pointer to the value
     *
     * @return null|ValidationError A ValidationError if validation fails, otherwise null.
     */
    public function validate($value, $pointer = null);
}
