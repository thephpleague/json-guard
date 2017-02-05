<?php

namespace League\JsonGuard\Bench;

use League\JsonGuard\Dereferencer;

/**
 * @Groups({"dereference"})
 * @Revs(100)
 */
abstract class DereferenceBenchmark extends Benchmark
{
    protected $schema;

    abstract public function getSchema();

    public function setUp()
    {
        $this->schema = $this->getSchema();
    }

    public function benchJsonGuard()
    {
        (new Dereferencer())->dereference($this->schema);
    }
}
