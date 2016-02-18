<?php

namespace Machete\Validation\Test;

use Machete\Validation\Dereferencer\Loaders\FileLoader;
use Machete\Validation\SchemaLoadingException;

class FileLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoadWhenNotFound()
    {
        $this->setExpectedException(SchemaLoadingException::class);
        $loader = new FileLoader();
        $response = $loader->load(__DIR__ . '/not-found.json');
    }
}
