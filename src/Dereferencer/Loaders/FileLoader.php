<?php

namespace Machete\Validation\Dereferencer\Loaders;

use Machete\Validation\Dereferencer\Loader;

class FileLoader implements Loader
{
    public function load($path)
    {
        return json_decode(file_get_contents($path));
    }
}
