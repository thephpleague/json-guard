<?php

namespace Machete\Validation\Dereferencer\Loaders;

use Machete\Validation\Dereferencer\Loader;
use Machete\Validation\SchemaLoadingException;
use Machete\Validation;

class FileLoader implements Loader
{
    public function load($path)
    {
        if (!file_exists($path)) {
            throw SchemaLoadingException::notFound($path);
        }

        return Validation\json_decode(file_get_contents($path));
    }
}
