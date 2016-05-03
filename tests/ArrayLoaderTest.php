<?php

namespace Yuloh\JsonGuard\Test;

use Yuloh\JsonGuard\Loaders\ArrayLoader;

class ArrayLoaderTest extends \PHPUnit_Framework_TestCase
{
    public function testLoad()
    {
        $schemas = [
            'some/schema'   => json_decode('{"hello": "world"}'),
            'string/schema' => '{"hello": "world"}',
        ];
        $loader  = new ArrayLoader($schemas);

        $this->assertEquals($schemas['some/schema'], $loader->load('some/schema'));
        $this->assertEquals(json_decode($schemas['string/schema']), $loader->load('string/schema'));
    }

    public function testLoadThrowsWhenNotFound()
    {
        $this->setExpectedException('Yuloh\JsonGuard\Exceptions\SchemaLoadingException');
        $loader = new ArrayLoader([]);
        $loader->load('missing/path');
    }

    public function testLoadThrowsWhenSchemaIsInvalidType()
    {
        $this->setExpectedException('Yuloh\JsonGuard\Exceptions\SchemaLoadingException');
        $loader = new ArrayLoader([
            'bad/type' => []
        ]);
        $loader->load('bad/type');
    }
}
