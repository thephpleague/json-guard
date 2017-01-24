<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\Assert;
use League\JsonGuard\ValidationError;
use League\JsonGuard\Validator;

class Dependencies implements Constraint
{
    const KEYWORD = 'dependencies';

    /**
     * {@inheritdoc}
     */
    public function validate($value, $parameter, Validator $validator)
    {
        Assert::type($parameter, ['object', 'array'], self::KEYWORD, $validator->getPointer());

        $errors = [];
        foreach ($parameter as $property => $dependencies) {
            if (!is_object($value) || !property_exists($value, $property)) {
                continue;
            }

            if (is_array($dependencies)) {
                $errors = array_merge(
                    $errors,
                    self::validatePropertyDependency($value, $dependencies, $validator->getPointer())
                );
            } elseif (is_object($dependencies)) {
                $errors = array_merge(
                    $errors,
                    self::validateSchemaDependency($value, $dependencies, $validator)
                );
            }
        }

        return $errors;
    }

    /**
     * @param object $data
     * @param array  $dependencies
     * @param string $pointer
     *
     * @return array
     */
    protected static function validatePropertyDependency($data, $dependencies, $pointer)
    {
        $errors = [];
        foreach ($dependencies as $dependency) {
            if (!in_array($dependency, array_keys(get_object_vars($data)), true)) {
                $errors[] = new ValidationError(
                    'Unmet dependency {dependency}',
                    self::KEYWORD,
                    $data,
                    $pointer,
                    ['dependency' => $dependency]
                );
            }
        }

        return $errors;
    }

    /**
     * @param object    $data
     * @param object    $dependencies
     *
     * @param Validator $validator
     *
     * @return array
     */
    protected static function validateSchemaDependency($data, $dependencies, Validator $validator)
    {
        $subValidator = $validator->makeSubSchemaValidator(
            $data,
            $dependencies,
            $validator->getPointer()
        );

        return $subValidator->errors();
    }
}
