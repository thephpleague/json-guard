<?php

namespace Yuloh\JsonGuard\Loaders;

use Yuloh\JsonGuard;
use Yuloh\JsonGuard\Exceptions\SchemaLoadingException;
use Yuloh\JsonGuard\Loader;

class FileLoader implements Loader
{
    /**
     * {@inheritdoc}
     */
    public function load($path)
    {
        if (!file_exists($path)) {
            throw SchemaLoadingException::notFound($path);
        }

        return JsonGuard\json_decode(file_get_contents($path));
    }
}
