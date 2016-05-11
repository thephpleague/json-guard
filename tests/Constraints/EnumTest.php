<?php

namespace League\JsonGuard\Test\Constraints;

use League\JsonGuard\Constraints\Enum;

class EnumTest extends \PHPUnit_Framework_TestCase
{
    public function testNonArrayParameter()
    {
        $this->assertNull(Enum::validate([1,2,3], 'not-array'));
    }
}
