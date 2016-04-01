<?php

namespace Yuloh\JsonGuard\Test;

use Yuloh\JsonGuard\Exceptions\SchemaLoadingException;
use Yuloh\JsonGuard\Loaders\FileLoader;

class FileLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadWhenNotFound()
    {
        $this->setExpectedException(SchemaLoadingException::class);
        $loader = new FileLoader();
        $response = $loader->load(__DIR__ . '/not-found.json');
    }
}
