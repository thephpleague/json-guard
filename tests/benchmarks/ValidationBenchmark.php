<?php

namespace League\JsonGuard\Bench;

use League\JsonGuard\Dereferencer;
use League\JsonGuard\Validator;

/**
 * @Groups({"validation"})
 * @Revs(100)
 */
abstract class ValidationBenchmark extends Benchmark
{
    protected $data;

    protected $schema;

    abstract public function getData();

    abstract public function getSchema();

    public function setUp()
    {
        $this->data   = $this->getData();
        $this->schema = (new Dereferencer())->dereference($this->getSchema());
    }

    public function benchJsonGuard()
    {
        (new Validator($this->data, $this->schema))->errors();
    }
}
