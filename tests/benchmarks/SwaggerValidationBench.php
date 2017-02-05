<?php

namespace League\JsonGuard\Bench;

class SwaggerValidationBench extends ValidationBenchmark
{
    public function getData()
    {
        return json_decode(file_get_contents(__DIR__ . '/../fixtures/swagger2.json'));
    }

    public function getSchema()
    {
        return json_decode(file_get_contents(__DIR__ . '/../fixtures/draft4-schema.json'));
    }
}
