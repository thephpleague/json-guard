<?php

namespace League\JsonGuard\Bench;

class MetaSchemaValidationBench extends ValidationBenchmark
{
    public function getData()
    {
        return $this->getSchema();
    }

    public function getSchema()
    {
        return json_decode(file_get_contents(__DIR__ . '/../fixtures/draft4-schema.json'));
    }
}
