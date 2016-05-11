<?php

namespace League\JsonGuard\Test\Constraints;

use League\JsonGuard\Constraints\AllOf;
use League\JsonGuard\Validator;

class AllOfTest extends \PHPUnit_Framework_TestCase
{
    public function testNonArrayParameter()
    {
        $v = new Validator(json_decode('{}'), json_decode('{}'));
        $this->assertNull(AllOf::validate([1,2,3], 'not-array', $v));
    }
}
