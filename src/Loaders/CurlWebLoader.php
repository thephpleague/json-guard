<?php

namespace Yuloh\JsonGuard\Loaders;

use Yuloh\JsonGuard;
use Yuloh\JsonGuard\Loader;

class CurlWebLoader implements Loader
{
    /**
     * @var string
     */
    private $prefix;

    /**
     * @var array
     */
    private $curlOptions;

    /**
     * @param string $prefix
     * @param array  $curlOptions
     */
    public function __construct($prefix, array $curlOptions = null)
    {
        $this->prefix      = $prefix;
        $this->setCurlOptions($curlOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function load($path)
    {
        $uri = $this->prefix . $path;
        $ch = curl_init($uri);
        curl_setopt_array($ch, $this->curlOptions);
        list($response, $statusCode) = $this->getResponseBodyAndStatusCode($ch);
        curl_close($ch);

        if ($statusCode === 404 || !$response) {
            throw JsonGuard\Exceptions\SchemaLoadingException::create($uri);
        }

        return JsonGuard\json_decode($response);
    }

    /**
     * @param resource $ch
     *
     * @return array
     */
    private function getResponseBodyAndStatusCode($ch)
    {
        $response   = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        return [$response, $statusCode];
    }

    /**
     * @return array
     */
    private function getDefaultCurlOptions()
    {
        return [
            CURLOPT_RETURNTRANSFER => true,
        ];
    }

    /**
     * @param array|null $curlOptions
     */
    private function setCurlOptions($curlOptions)
    {
        if (is_array($curlOptions)) {
            $this->curlOptions = $curlOptions + $this->getDefaultCurlOptions();
            return;
        }

        $this->curlOptions = $this->getDefaultCurlOptions();
    }
}
