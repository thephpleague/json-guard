<?php

if (!function_exists('schema_test_suite_path')) {
    function schema_test_suite_path()
    {
        return realpath(__DIR__ . '/../vendor/json-schema/JSON-Schema-Test-Suite/tests');
    }
}