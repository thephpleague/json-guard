<?php

namespace Yuloh\JsonGuard;

interface FormatExtension
{
    /**
     * @param string      $value   The value to validate
     * @param string|null $pointer A pointer to the value
     * @return null
     * @throws AssertionFailedException If validation fails
     */
    public function validate($value, $pointer = null);
}
