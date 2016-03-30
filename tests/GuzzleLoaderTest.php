<?php

namespace Yuloh\JsonGuard\Test;

use Guzzle\Http\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use Yuloh\JsonGuard\Dereferencer\Loaders\GuzzleLoader;
use Yuloh\JsonGuard\SchemaLoadingException;

class GuzzleLoaderTest extends \PHPUnit_Framework_testCase
{
    public function testLoad()
    {
        $loader = new GuzzleLoader('http://');
        $response = $loader->load('localhost:1234/integer.json');
        $this->assertSame('{"type":"integer"}', json_encode($response));
    }

    public function testNotFound()
    {
        $this->setExpectedException(SchemaLoadingException::class);
        $loader = new GuzzleLoader('http://');
        $loader->load('localhost:1234/unknown');
    }

    public function testRequestException()
    {
        $this->setExpectedException(SchemaLoadingException::class);

        $mock = new MockHandler([
            new RequestException('Error Communicating with Server', new Request('GET', 'test'))
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $loader = new GuzzleLoader('http://');
        $loader->load('test');
    }
}
