<?php

namespace Yuloh\JsonGuard;

use Yuloh\JsonGuard\Constraints\ContainerInstanceConstraint;
use Yuloh\JsonGuard\Constraints\ParentSchemaAwareContainerInstanceConstraint;
use Yuloh\JsonGuard\Constraints\PropertyConstraint;
use Yuloh\JsonGuard\Exceptions\MaximumDepthExceededException;
use Yuloh\JsonGuard\RuleSets\DraftFour;

class Validator implements SubSchemaValidatorFactory
{
    /**
     * @var array
     */
    private $errors = [];

    /**
     * @var mixed
     */
    private $data;

    /**
     * @var object
     */
    private $schema;

    /**
     * @var string
     */
    private $pointer = '';

    /**
     * The maximum depth the validator should recurse into $data
     * before throwing an exception.
     *
     * @var int
     */
    private $maxDepth = 10;

    /**
     * The depth the validator has reached in the data.
     *
     * @var int
     */
    private $depth = 0;

    /**
     * @var \Yuloh\JsonGuard\FormatExtension[]
     */
    private $formatExtensions = [];

    /**
     * @var \Yuloh\JsonGuard\RuleSet
     */
    private $ruleSet;

    /**
     * @param mixed  $data
     * @param object $schema
     * @param RuleSet|null   $ruleSet
     */
    public function __construct($data, $schema, $ruleSet = null)
    {
        if (!is_object($schema)) {
            throw new \InvalidArgumentException(
                sprintf('The schema should be an object from a json_decode call, got "%s"', gettype($schema))
            );
        }

        if ($schema instanceof Reference) {
            $schema = $schema->resolve();
        }

        $this->data    = $data;
        $this->schema  = $schema;
        $this->ruleSet = $ruleSet ?: new DraftFour();
    }

    /**
     * @return boolean
     */
    public function fails()
    {
        return !$this->passes();
    }

    /**
     * @return boolean
     */
    public function passes()
    {
        return empty($this->errors());
    }

    /**
     * Get a collection of errors.
     *
     * @return ValidationError[]
     */
    public function errors()
    {
        $this->validate();

        return $this->errors;
    }

    /**
     * Set the maximum allowed depth data will be validated until.
     * If the data exceeds the stack depth an exception is thrown.
     *
     * @param int $maxDepth
     * @return $this
     */
    public function setMaxDepth($maxDepth)
    {
        $this->maxDepth = $maxDepth;

        return $this;
    }

    /**
     * Register a custom format validation extension.
     *
     * @param string          $format
     * @param FormatExtension $extension
     */
    public function registerFormatExtension($format, FormatExtension $extension)
    {
        $this->formatExtensions[$format] = $extension;
    }

    /**
     * @internal
     * @param int $depth
     * @return $this
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @internal
     * @return string
     */
    public function getPointer()
    {
        return $this->pointer;
    }

    /**
     * @internal
     * @param string $pointer
     * @return $this
     */
    public function setPointer($pointer)
    {
        $this->pointer = $pointer;

        return $this;
    }

    /**
     * Create a new sub-validator.
     *
     * @param mixed  $data
     * @param object $schema
     * @param string $pointer
     * @return Validator
     */
    public function makeSubSchemaValidator($data, $schema, $pointer)
    {
        return (new Validator($data, $schema))
            ->setPointer($pointer)
            ->setMaxDepth($this->maxDepth)
            ->setDepth($this->depth + 1);
    }

    /**
     * Validate the data and collect the errors.
     */
    private function validate()
    {
        $this->errors = [];

        $this->checkDepth();

        foreach ($this->schema as $rule => $parameter) {

            $errors = $this->validateRule($rule, $parameter);

            $this->mergeErrors($errors);
        }
    }

    /**
     * Keep track of how many levels deep we have validated.
     * This is to prevent a really deeply nested JSON
     * structure from causing the validator to continue
     * validating for an incredibly long time.
     *
     * @throws \Yuloh\JsonGuard\Exceptions\MaximumDepthExceededException
     */
    private function checkDepth()
    {
        if ($this->depth > $this->maxDepth) {
            throw new MaximumDepthExceededException();
        }
    }

    /**
     * Validate the data using the given rule and parameter.
     *
     * @param string $rule
     * @param mixed $parameter
     * @return null|ValidationError|ValidationError[]
     */
    private function validateRule($rule, $parameter)
    {
        if ($this->isSkippedRule($rule)) {
            return null;
        }

        if ($this->isCustomFormatExtension($rule, $parameter)) {
            return $this->validateCustomFormat($parameter);
        }

        if ($this->isExclusive($rule)) {
            $rule = 'exclusive' . ucfirst($rule);
        }

        $constraint = $this->ruleSet->getConstraint($rule);

        return $this->invokeConstraint($constraint, $parameter);
    }

    /**
     * Invoke the given constraint and return the validation errors.
     *
     * @param \Yuloh\JsonGuard\Constraints\Constraint $constraint
     * @param mixed $parameter
     *
     * @return \Yuloh\JsonGuard\ValidationError|\Yuloh\JsonGuard\ValidationError[]|null
     */
    private function invokeConstraint($constraint, $parameter)
    {
        if ($constraint instanceof PropertyConstraint) {
            return $constraint::validate($this->data, $parameter, $this->getPointer());
        } elseif ($constraint instanceof ContainerInstanceConstraint) {
            return $constraint::validate($this->data, $parameter, $this, $this->getPointer());
        } elseif ($constraint instanceof ParentSchemaAwareContainerInstanceConstraint) {
            return $constraint::validate($this->data, $this->schema, $parameter, $this, $this->getPointer());
        }

        throw new \InvalidArgumentException('Invalid constraint.');
    }

    /**
     * Determine if a rule has a custom format extension registered.
     *
     * @param string $rule
     * @param mixed $parameter
     *
     * @return bool
     */
    private function isCustomFormatExtension($rule, $parameter)
    {
        return $rule === 'format' && isset($this->formatExtensions[$parameter]);
    }

    /**
     * Call a custom format extension to validate the data.
     *
     * @param string $format
     *
     * @return ValidationError|null
     */
    private function validateCustomFormat($format)
    {
        /** @var FormatExtension $extension */
        $extension = $this->formatExtensions[$format];

        return $extension->validate($this->data, $this->getPointer());
    }

    /**
     * Determine if a rule is exclusive.  minimum and maximum rules are exclusive if
     * `"exclusive": true` exists in the schema.  Since they are the only scenario where
     * a property constraint needs to check elsewhere in the schema, we check here
     * to simplify implementation of a rule set.
     *
     * @param string $rule
     *
     * @return bool
     */
    private function isExclusive($rule)
    {
        if ($rule !== 'minimum' && $rule !== 'maximum') {
            return false;
        }

        $key = $rule === 'minimum' ? 'exclusiveMinimum' : 'exclusiveMaximum';

        return (isset($this->schema->$key) && $this->schema->$key === true);
    }

    /**
     * Merge the errors with our error collection.
     *
     * @param ValidationError[]|ValidationError|null $errors
     */
    private function mergeErrors($errors)
    {
        if (is_null($errors)) {
            return;
        }

        if (is_array($errors)) {
            $this->errors = array_merge($this->errors, $errors);
            return;
        }

        $this->errors[] = $errors;
    }

    /**
     * Determine if a rule is skipped and not validated.
     *
     * @param string $rule
     *
     * @return bool
     */
    private function isSkippedRule($rule)
    {
        return !$this->ruleSet->has($rule) || $rule === 'exclusiveMinimum' || $rule === 'exclusiveMaximum';
    }
}
