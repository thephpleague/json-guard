<?php

namespace League\JsonGuard\Bench;

class MetaSchemaDereferenceBench extends DereferenceBenchmark
{
    public function getSchema()
    {
        return json_decode(file_get_contents(__DIR__ . '/../fixtures/draft4-schema.json'));
    }
}
