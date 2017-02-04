<?php

namespace League\JsonGuard\Test;

use League\JsonGuard\Loader;
use League\JsonGuard\LoaderManager;
use League\JsonGuard\Loaders\ArrayLoader;

class LoaderManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetLoaders()
    {
        $manager = new LoaderManager();
        $loaders = $manager->getLoaders();
        $this->assertArrayHasKey('file', $loaders);
        $this->assertInstanceOf(Loader::class, $loaders['file']);
        $this->assertArrayHasKey('http', $loaders);
        $this->assertInstanceOf(Loader::class, $loaders['http']);
        $this->assertArrayHasKey('https', $loaders);
        $this->assertInstanceOf(Loader::class, $loaders['https']);
    }

    public function testGetLoaderThrowsIfTheLoaderDoesNotExist()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $manager = new LoaderManager();
        $manager->getLoader('couchdb');
    }

    public function testRegisterLoader()
    {
        $loader  = new ArrayLoader([]);
        $manager = new LoaderManager();
        $manager->registerLoader('http', $loader);
        $this->assertSame($loader, $manager->getLoader('http'));
    }

    public function testDoesNotUseDefaultsIfLoadersAreSpecified()
    {
        $loaders  = [
            'array' => new ArrayLoader([])
        ];

        $manager = new LoaderManager($loaders);

        $this->assertFalse($manager->hasLoader('file'));
        $this->assertFalse($manager->hasLoader('http'));
        $this->assertFalse($manager->hasLoader('https'));
        $this->assertTrue($manager->hasLoader('array'));
    }
}
