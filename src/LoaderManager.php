<?php

namespace League\JsonGuard;

use League\JsonGuard\Loaders\CurlWebLoader;
use League\JsonGuard\Loaders\FileGetContentsWebLoader;
use League\JsonGuard\Loaders\FileLoader;

class LoaderManager
{
    /**
     * @var Loader[]
     */
    private $loaders = [];

    /**
     * @param Loader[] $loaders
     */
    public function __construct(array $loaders = [])
    {
        if (empty($loaders)) {
            $this->registerDefaultFileLoader();
            $this->registerDefaultWebLoaders();
            return;
        }

        foreach ($loaders as $prefix => $loader) {
            $this->registerLoader($prefix, $loader);
        }
    }

    /**
     * Register a Loader for the given prefix.
     *
     * @param string $prefix
     * @param Loader $loader
     */
    public function registerLoader($prefix, Loader $loader)
    {
        $this->loaders[$prefix] = $loader;
    }

    /**
     * Get all registered loaders, keyed by the prefix they are registered to load schemas for.
     *
     * @return Loader[]
     */
    public function getLoaders()
    {
        return $this->loaders;
    }

    /**
     * Get the loader for the given prefix.
     *
     * @param string $prefix
     *
     * @return Loader
     * @throws \InvalidArgumentException
     */
    public function getLoader($prefix)
    {
        if (!$this->hasLoader($prefix)) {
            throw new \InvalidArgumentException(sprintf('A loader is not registered for the prefix "%s"', $prefix));
        }

        return $this->loaders[$prefix];
    }

    /**
     * @param string $prefix
     *
     * @return bool
     */
    public function hasLoader($prefix)
    {
        return isset($this->loaders[$prefix]);
    }

    /**
     * Register the default file loader.
     */
    private function registerDefaultFileLoader()
    {
        $this->loaders['file'] = new FileLoader();
    }

    /**
     * Register the default web loaders.  If the curl extension is loaded,
     * the CurlWebLoader will be used.  Otherwise the FileGetContentsWebLoader
     * will be used.  You can override this by registering your own loader
     * for the 'http' and 'https' protocols.
     */
    private function registerDefaultWebLoaders()
    {
        if (function_exists('curl_init')) {
            $this->loaders['https'] = new CurlWebLoader('https://');
            $this->loaders['http']  = new CurlWebLoader('http://');
        } else {
            $this->loaders['https'] = new FileGetContentsWebLoader('https://');
            $this->loaders['http']  = new FileGetContentsWebLoader('http://');
        }
    }
}
