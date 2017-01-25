<?php

namespace League\JsonGuard\Test\Constraints;

use League\JsonGuard\Constraints\Enum;
use League\JsonGuard\Exceptions\InvalidSchemaException;
use League\JsonGuard\Validator;

class EnumTest extends \PHPUnit_Framework_TestCase
{
    public function testNonArrayParameter()
    {
        $this->setExpectedException(InvalidSchemaException::class);
        (new Enum())->validate([1,2,3], 'not-array', new Validator([], new \stdClass()));
    }
}
