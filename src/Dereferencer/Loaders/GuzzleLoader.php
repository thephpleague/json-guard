<?php

namespace Yuloh\JsonGuard\Dereferencer\Loaders;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;
use Yuloh\JsonGuard;
use Yuloh\JsonGuard\Dereferencer\Loader;

class GuzzleLoader implements Loader
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var Client
     */
    private $client;

    /**
     * @param string $prefix
     * @param Client $client
     */
    public function __construct($prefix, Client $client = null)
    {
        $this->prefix = $prefix;
        $this->client = $client ?: new Client();
    }

    public function load($path)
    {
        try {
            $res = $this->client->get($this->prefix . $path)->send();
        } catch (RequestException $e) {
            throw new JsonGuard\Exceptions\SchemaLoadingException($path);
        }

        $body = (string) $res->getBody();
        return JsonGuard\json_decode($body);
    }
}
