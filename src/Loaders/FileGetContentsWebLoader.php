<?php

namespace Yuloh\JsonGuard\Loaders;

use Yuloh\JsonGuard;
use Yuloh\JsonGuard\Exceptions\SchemaLoadingException;
use Yuloh\JsonGuard\Loader;

class FileGetContentsWebLoader implements Loader
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @param string $prefix
     */
    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * {@inheritdoc}
     */
    public function load($path)
    {
        $uri = $this->prefix . $path;
        set_error_handler(function () use ($uri) {
            throw new SchemaLoadingException($uri);
        });
        $response = file_get_contents($uri);
        restore_error_handler();

        if (!$response) {
            throw SchemaLoadingException::create($uri);
        }

        return JsonGuard\json_decode($response);
    }
}
