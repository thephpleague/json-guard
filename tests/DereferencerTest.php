<?php

namespace Yuloh\JsonGuard\Test;

use Yuloh\JsonGuard\Dereferencer;

class DereferencerTest extends \PHPUnit_Framework_TestCase
{
    public function testInline()
    {
        $deref  = new Dereferencer();
        $path   = 'file://' . __DIR__ . '/fixtures/inline-ref.json';
        $result = $deref->dereference($path);

        $this->assertSame(json_encode($result->definitions->address), json_encode($result->properties->billing_address->resolve()));
        $this->assertSame(json_encode($result->definitions->address), json_encode($result->properties->shipping_address->resolve()));
    }

    public function testRemote()
    {
        $deref  = new Dereferencer();
        $path   = 'http://json-schema.org/draft-04/schema#';
        $result = $deref->dereference($path);
        $this->assertSame($result->definitions->positiveIntegerDefault0, $result->properties->minItems->resolve());
    }

    public function testRecursiveRootPointer()
    {
        $deref  = new Dereferencer();
        $path   = 'file://' . __DIR__ . '/fixtures/recursive-root-pointer.json';
        $result = $deref->dereference($path);
        $this->assertSame($result->properties->foo, $result->properties->foo->properties->foo->properties->foo);
    }

    public function testCircularReferenceToSelf()
    {
        $deref  = new Dereferencer();
        $path   = 'file://' . __DIR__ . '/fixtures/circular-ref-self.json';
        $result = $deref->dereference($path);
        $this->assertSame(
            '{"$ref":"#\/definitions\/thing"}',
            json_encode($result->definitions->thing)
        );
        $this->assertSame(
            $result->definitions->thing,
            $result->definitions->thing->resolve()->resolve()->resolve()->resolve(),
            'You should be able to resolve recursive definitions to any depth'
        );
    }

    public function testCircularReferenceToParent()
    {
        $deref  = new Dereferencer();
        $path   = 'file://' . __DIR__ . '/fixtures/circular-ref-parent.json';
        $result = $deref->dereference($path);
        $ref    = $result
            ->definitions
            ->person
            ->properties
            ->spouse
            ->type
            ->resolve();

        $this->assertSame(json_encode($result->definitions->person), json_encode($ref));
    }

    public function testReferenceInArray()
    {
        $deref  = new Dereferencer();
        $path   = 'file://' . __DIR__ . '/fixtures/array-ref.json';
        $result = $deref->dereference($path);
        $this->assertSame($result->items[0], $result->items[1]->resolve());
    }
}
