<?php

namespace Yuloh\JsonGuard;

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
     * @return \Yuloh\JsonGuard\Constraints\Constraint|null
     */
    public function getConstraint($rule);
}
