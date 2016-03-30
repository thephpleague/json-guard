<?php

namespace Yuloh\JsonGuard\Dereferencer\Loaders;

use Yuloh\JsonGuard;
use Yuloh\JsonGuard\Dereferencer\Loader;
use Yuloh\JsonGuard\Exceptions\SchemaLoadingException;

class FileLoader implements Loader
{
    public function load($path)
    {
        if (!file_exists($path)) {
            throw SchemaLoadingException::notFound($path);
        }

        return JsonGuard\json_decode(file_get_contents($path));
    }
}
