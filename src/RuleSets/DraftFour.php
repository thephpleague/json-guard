<?php

namespace Yuloh\JsonGuard\RuleSets;

use Yuloh\JsonGuard\Constraints\AdditionalItems;
use Yuloh\JsonGuard\Constraints\AdditionalProperties;
use Yuloh\JsonGuard\Constraints\AllOf;
use Yuloh\JsonGuard\Constraints\AnyOf;
use Yuloh\JsonGuard\Constraints\Dependencies;
use Yuloh\JsonGuard\Constraints\Enum;
use Yuloh\JsonGuard\Constraints\ExclusiveMax;
use Yuloh\JsonGuard\Constraints\ExclusiveMin;
use Yuloh\JsonGuard\Constraints\Format;
use Yuloh\JsonGuard\Constraints\Items;
use Yuloh\JsonGuard\Constraints\Max;
use Yuloh\JsonGuard\Constraints\MaxItems;
use Yuloh\JsonGuard\Constraints\MaxLength;
use Yuloh\JsonGuard\Constraints\MaxProperties;
use Yuloh\JsonGuard\Constraints\Min;
use Yuloh\JsonGuard\Constraints\MinItems;
use Yuloh\JsonGuard\Constraints\MinLength;
use Yuloh\JsonGuard\Constraints\MinProperties;
use Yuloh\JsonGuard\Constraints\MultipleOf;
use Yuloh\JsonGuard\Constraints\Not;
use Yuloh\JsonGuard\Constraints\OneOf;
use Yuloh\JsonGuard\Constraints\Pattern;
use Yuloh\JsonGuard\Constraints\PatternProperties;
use Yuloh\JsonGuard\Constraints\Properties;
use Yuloh\JsonGuard\Constraints\Required;
use Yuloh\JsonGuard\Constraints\Type;
use Yuloh\JsonGuard\Constraints\UniqueItems;
use Yuloh\JsonGuard\RuleSet;

/**
 * The default rule set for JSON Schema Draft 4.
 * @see http://tools.ietf.org/html/draft-zyp-json-schema-04
 */
class DraftFour implements RuleSet
{
    private $rules = [
        'additionalItems'      => AdditionalItems::class,
        'additionalProperties' => AdditionalProperties::class,
        'allOf'                => AllOf::class,
        'anyOf'                => AnyOf::class,
        'dependencies'         => Dependencies::class,
        'enum'                 => Enum::class,
        'format'               => Format::class,
        'items'                => Items::class,
        'maximum'              => Max::class,
        'maxItems'             => MaxItems::class,
        'maxLength'            => MaxLength::class,
        'maxProperties'        => MaxProperties::class,
        'minimum'              => Min::class,
        'minItems'             => MinItems::class,
        'minLength'            => MinLength::class,
        'minProperties'        => MinProperties::class,
        'multipleOf'           => MultipleOf::class,
        'not'                  => Not::class,
        'oneOf'                => OneOf::class,
        'pattern'              => Pattern::class,
        'patternProperties'    => PatternProperties::class,
        'properties'           => Properties::class,
        'required'             => Required::class,
        'type'                 => Type::class,
        'uniqueItems'          => UniqueItems::class,
    ];

    /**
     * Determine if the rule set has a registered constraint for $rule.
     *
     * @param string $rule
     *
     * @return bool
     */
    public function has($rule)
    {
        return array_key_exists($rule, $this->rules);
    }

    /**
     * Get the registered constraint for $rule.
     *
     * @param string $rule
     *
     * @return \Yuloh\JsonGuard\Constraints\Constraint|null
     */
    public function getConstraint($rule)
    {
        if (!$this->has($rule)) {
            return null;
        }

        return new $this->rules[$rule];
    }
}
