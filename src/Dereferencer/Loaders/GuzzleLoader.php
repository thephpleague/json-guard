<?php

namespace Machete\Validation\Dereferencer\Loaders;

use Guzzle\Http\Client;
use Machete\Validation\Dereferencer\Loader;

class GuzzleLoader implements Loader
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

    public function load($path)
    {
        $client = new Client();
        $res = $client->get($this->prefix . $path)->send();
        $body = (string) $res->getBody();
        return json_decode($body);
    }
}
