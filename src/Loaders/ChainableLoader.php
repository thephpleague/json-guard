<?php

namespace League\JsonGuard\Loaders;

use League\JsonGuard\Exceptions\SchemaLoadingException;
use League\JsonGuard\Loader;

/**
 * This loader takes two other loaders as constructor parameters, and will
 * attempt to load from the first loader before deferring to the second loader.
 * This is useful when you would like to use multiple loaders for the same prefix.
 */
class ChainableLoader implements Loader
{
    /**
     * @var Loader
     */
    private $firstLoader;

    /**
     * @var Loader
     */
    private $secondLoader;

    /**
     * @param \League\JsonGuard\Loader $firstLoader
     * @param \League\JsonGuard\Loader $secondLoader
     */
    public function __construct(Loader $firstLoader, Loader $secondLoader)
    {
        $this->firstLoader  = $firstLoader;
        $this->secondLoader = $secondLoader;
    }

    /**
     * Load the json schema from the given path.
     *
     * @param string $path The path to load, without the protocol.
     *
     * @return object The object resulting from a json_decode of the loaded path.
     */
    public function load($path)
    {
        try {
            return $this->firstLoader->load($path);
        } catch (SchemaLoadingException $e) {
            return $this->secondLoader->load($path);
        }
    }
}
