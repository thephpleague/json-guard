<?php

namespace Yuloh\JsonGuard\Constraints;

use Yuloh\JsonGuard\ErrorCode;
use Yuloh\JsonGuard\ValidationError;

class Required implements PropertyConstraint
{
    /**
     * {@inheritdoc}
     */
    public static function validate($data, $parameter, $pointer = null)
    {
        $actualProperties = array_keys(get_object_vars($data));
        $missing          = array_diff($parameter, $actualProperties);
        if (count($missing)) {
            return new ValidationError(
                'Required properties missing.',
                ErrorCode::MISSING_REQUIRED,
                $data,
                $pointer,
                ['required' => $parameter]
            );
        }

        return null;
    }
}
