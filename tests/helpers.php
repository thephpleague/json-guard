<?php

if (!function_exists('schema_test_suite_path')) {
    function schema_test_suite_path()
    {
        return realpath(__DIR__ . '/../vendor/json-schema/JSON-Schema-Test-Suite/tests');
    }
}

if (!function_exists('dd')) {
    function dd()
    {
        die(var_dump(...func_get_args()));
    }
}