<?php

namespace Machete\Validation\Test;

use Machete\Validation\Dereferencer;
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

        $cwd = realpath(schema_test_suite_path() . '/../remotes');
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
        $ours = [
            __DIR__ . '/fixtures/additional-item-no-items.json'
        ];
        $files = array_merge($required, $optional, $ours);

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
        // We are skipping the optional "bignum" test since json_decode
        // immediately casts a big number to a float and it's
        // impossible to figure out if it's an int after that.
        if (strpos($file, 'bignum.json') !== false) {
            return;
        }

        foreach (json_decode(file_get_contents($file)) as $testCase) {

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

        $v = new Validator($data, $schema);

        $errors = $v->errors();
        $this->assertCount(1, $errors);
        $this->assertSame(\Machete\Validation\INVALID_STRING, $errors[0]['code']);
        $this->assertSame('/name', $errors[0]['path']);

        // todo: test deeply nested paths return pointers.
    }
}
