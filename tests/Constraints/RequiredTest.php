<?php

namespace League\JsonGuard\Test\Constraints;

use League\JsonGuard\Constraints\Required;
use League\JsonGuard\Validator;

class RequiredTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionMessageContainsPropertyName()
    {
        $required = new Required();
        $error = $required->validate(new \stdClass(), ['shouldBeHere'], new Validator([], new \stdClass()));
        $this->assertRegExp('/shouldBeHere/', $error->getMessage());
    }
}

