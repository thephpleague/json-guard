<?php

namespace Machete\Validation;

class AssertionFailedException extends \InvalidArgumentException
{
    /**
     * @var string|null
     */
    private $pointer;

    /**
     * @var mixed
     */
    private $value;

    /**
     * @var array
     */
    private $constraints;

    /**
     * @param string      $message
     * @param int         $code
     * @param mixed       $value
     * @param string|null $pointer
     * @param array       $constraints
     */
    public function __construct($message, $code, $value, $pointer = null, array $constraints = [])
    {
        parent::__construct($message, $code);

        $this->pointer     = $pointer;
        $this->value       = $value;
        $this->constraints = $constraints;
    }

    /**
     * Get the path to the property that failed validation.
     *
     * @return string|null
     */
    public function getPointer()
    {
        return $this->pointer;
    }

    /**
     * Get the value that caused the assertion to fail.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the constraints that applied to the failed assertion.
     *
     * @return array
     */
    public function getConstraints()
    {
        return $this->constraints;
    }
}
