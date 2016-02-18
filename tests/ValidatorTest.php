<?php

namespace Machete\Validation\Test;

use Machete\Validation\Dereferencer;
use Machete\Validation\MaximumDepthExceededException;
use const Machete\Validation\NOT_ALLOWED_PROPERTY;
use Machete\Validation\Validator;
use Symfony\Component\Process\Process;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Process
     */
    private static $server;

    public static function setUpBeforeClass()
    {
        if (defined('HHVM_VERSION')) {
            $cmd = 'hhvm -m server -p 1234';
        } else {
            $cmd = 'php -S localhost:1234';
        }

        $cwd            = realpath(schema_test_suite_path() . '/../remotes');
        static::$server = new Process($cmd, $cwd);
        static::$server->start();
    }

    public static function tearDownAfterClass()
    {
        static::$server->stop();
    }

    public function draft4Tests()
    {
        $required = glob(schema_test_suite_path() . '/draft4/*.json');
        $optional = glob(schema_test_suite_path() . '/draft4/optional/*.json');
        $ours     = [
            __DIR__ . '/fixtures/additional-item-no-items.json',
        ];
        $files    = array_merge($required, $optional, $ours);

        return array_map(function ($file) {
            return [$file];
        }, $files);
    }

    /**
     * @dataProvider draft4Tests
     *
     * @param string $file
     */
    public function testDraft4($file)
    {
        // We need to use the option that treats big numbers as a
        // string value so that the 'bignum.json' test will pass.
        $test = json_decode(file_get_contents($file), false, 512, JSON_BIGINT_AS_STRING);

        foreach ($test as $testCase) {
            $schema      = $testCase->schema;
            $description = $testCase->description;
            $refResolver = new Dereferencer();
            $schema      = $refResolver->dereference($schema);

            foreach ($testCase->tests as $test) {
                $validator = new Validator($test->data, $schema);
                $msg       = $description . ' : ' . $test->description;
                if ($test->valid) {
                    $this->assertTrue($validator->passes(), $msg);
                } else {
                    $this->assertTrue($validator->fails(), $msg);
                }
            }
        }
    }

    public function testErrorMessages()
    {
        $data   = json_decode(file_get_contents(__DIR__ . '/fixtures/invalid.json'));
        $schema = json_decode(file_get_contents(__DIR__ . '/fixtures/schema.json'));

        $deref  = new Dereferencer();
        $schema = $deref->dereference($schema);

        $v = new Validator($data, $schema);

        $errors = $v->errors();
        $this->assertCount(2, $errors);
        $this->assertSame(\Machete\Validation\INVALID_STRING, $errors[0]['code']);
        $this->assertSame('/name', $errors[0]['path']);

        $this->assertSame(\Machete\Validation\INVALID_STRING, $errors[1]['code']);
        $this->assertSame('/sub-product/sub-product/tags/1', $errors[1]['path']);
    }

    public function testDeeplyNestedDataWithinReason()
    {
        $schema = json_decode('{"properties": {"foo": {"$ref": "#"}}, "additionalProperties": false}');
        $deref  = new Dereferencer();
        $schema = $deref->dereference($schema);

        $data = json_decode('{"foo": {"foo": {"foo": {"foo": {"foo": {"foo": {"foo": {"foo": {"foo": {"bar": {}}}}}}}}}}}');
        $v = new Validator($data, $schema);
        $this->assertTrue($v->fails());
        $error = $v->errors()[0];
        $this->assertSame('/foo/foo/foo/foo/foo/foo/foo/foo/foo', $error['path']);
        $this->assertSame(NOT_ALLOWED_PROPERTY, $error['code']);
    }

    public function testStackAttack()
    {
        $this->setExpectedException(MaximumDepthExceededException::class);
        $schema = json_decode('{"properties": {"foo": {"$ref": "#"}}, "additionalProperties": false}');
        $deref  = new Dereferencer();
        $schema = $deref->dereference($schema);

        $data = json_decode(file_get_contents(__DIR__ . '/fixtures/stack-attack.json'));

        $v = new Validator($data, $schema);
        $v->passes();
    }

    public function testMaxDepth()
    {
        $schema = json_decode('{"properties": {"foo": {"$ref": "#"}}, "additionalProperties": false}');
        $deref  = new Dereferencer();
        $schema = $deref->dereference($schema);

        $data = json_decode('{"foo": {"foo": {}}}');

        $v = new Validator($data, $schema);
        $v->setMaxDepth(2);
        $v->passes(); // should not throw an exception.

        $this->setExpectedException(MaximumDepthExceededException::class);
        $v->setMaxDepth(1);
        $v->passes();
    }

    public function testMaxDepthIsReset()
    {
        $schema = json_decode('{"properties": {"foo": {"$ref": "#"}}, "additionalProperties": false}');
        $deref  = new Dereferencer();
        $schema = $deref->dereference($schema);

        $data = json_decode('{"foo": {"foo": {}}}');

        $v = new Validator($data, $schema);
        $v->setMaxDepth(3);
        $v->passes();
        $v->passes();
        $v->passes();
    }
}
