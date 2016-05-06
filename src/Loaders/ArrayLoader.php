<?php

namespace League\JsonGuard\Loaders;

use League\JsonGuard;
use League\JsonGuard\Exceptions\SchemaLoadingException;
use League\JsonGuard\Loader;

class ArrayLoader implements Loader
{
    /**
     * @var array
     */
    private $schemas;

    /**
     * @param array $schemas A map of schemas where path => schema.  The schema should be a string or the
     *                       object resulting from a json_decode call.
     */
    public function __construct(array $schemas)
    {
        $this->schemas = $schemas;
    }

    /**
     * {@inheritdoc}
     */
    public function load($path)
    {
        if (!array_key_exists($path, $this->schemas)) {
            throw SchemaLoadingException::notFound($path);
        }

        $schema = $this->schemas[$path];

        if (is_string($schema)) {
            return JsonGuard\json_decode($schema, false, 512, JSON_BIGINT_AS_STRING);
        } elseif (is_object($schema)) {
            return $schema;
        } else {
            throw SchemaLoadingException::create($path);
        }
    }
}
