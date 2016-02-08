<?php

namespace Machete\Validation;

class Reference implements \JsonSerializable
{
    /**
     * @var mixed
     */
    private $schema;

    /**
     * @var string
     */
    private $ref;

    /**
     * @param mixed $schema
     * @param string $ref
     */
    public function __construct($schema, $ref)
    {
        $this->schema = $schema;
        $this->ref    = $ref;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return ['$ref' => $this->ref];
    }

    /**
     * @return mixed
     */
    public function resolve()
    {
        $path    = trim($this->ref, '#');
        $pointer = new Pointer($this->schema);
        return $pointer->get($path);
    }

    /**
     * Proxies property access to the underlying schema.
     *
     * @param string $property
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public function __get($property)
    {
        $schema = $this->resolve();
        if (isset($schema->$property)) {
            return $schema->$property;
        }

        throw new \InvalidArgumentException(sprintf('Unknown property "%s"', $property));
    }
}
