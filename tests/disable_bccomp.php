<?php
/**
 * This stub allows us to mock function_exists calls to check for bccomp.
 * It's in a separate file so it can be scoped within the test function.
 */
namespace League\JsonGuard {
    function function_exists($name)
    {
        if ($name === 'bccomp') {
            return false;
        }

        return \function_exists($name);
    }
}
