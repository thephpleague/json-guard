<?php

namespace League\JsonGuard;

interface RuleSet
{
    /**
     * Determine if the ruleset has a registered constraint for $rule.
     *
     * @param string $rule
     *
     * @return bool
     */
    public function has($rule);

    /**
     * Get the registered constraint for $rule.
     *
     * @param string $rule
     *
     * @return \League\JsonGuard\Constraints\Constraint
     * @throws \League\JsonGuard\Exceptions\ConstraintNotFoundException
     */
    public function getConstraint($rule);
}
