<?php

namespace Machete\Validation;

class SchemaLoadingException extends \RuntimeException
{
    public function __construct($path)
    {
        $message = sprintf('The schema "%s" could not be loaded.', $path);
        parent::__construct($message);
    }

    public static function notFound($path)
    {
        return new static(sprintf('The schema "%s" could not be found.', $path));
    }
}
