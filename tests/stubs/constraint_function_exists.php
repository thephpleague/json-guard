<?php
/**
 * This stub allows us to mock function_exists calls made by constraints.
 * It's in a separate file so it can be scoped within the test function.
 */
namespace League\JsonGuard\Constraints {
    function function_exists()
    {
        return false;
    }
}
