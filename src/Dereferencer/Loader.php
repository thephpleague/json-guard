<?php

namespace Machete\Validation\Dereferencer;

interface Loader
{
    /**
     * Load the json schema from the given path.
     *
     * @param string $path The path to load, without the protocol.
     * @return object The object resulting from a json_decode of the loaded path.
     */
    public function load($path);
}
