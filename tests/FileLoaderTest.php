<?php

namespace Yuloh\JsonGuard\Test;

use Yuloh\JsonGuard\Dereferencer\Loaders\FileLoader;
use Yuloh\JsonGuard\SchemaLoadingException;

class FileLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadWhenNotFound()
    {
        $this->setExpectedException(SchemaLoadingException::class);
        $loader = new FileLoader();
        $response = $loader->load(__DIR__ . '/not-found.json');
    }
}
