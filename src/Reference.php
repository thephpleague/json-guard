<?php

namespace League\JsonGuard;

/**
 * A Reference object represents an internal $ref in a JSON object.
 * Because JSON references can be circular, in-lining the reference is
 * impossible.  This object can be substituted for the $ref instead,
 * allowing lazy resolution of the $ref when needed.
 */
class Reference implements \JsonSerializable
{
    /**
     * A JSON object resulting from a json_decode call.
     *
     * @var object
     */
    private $schema;

    /**
     * A valid JSON reference.  The reference should point to a location in $schema.
     * @see https://tools.ietf.org/html/draft-pbryan-zyp-json-ref-03
     *
     * @var string
     */
    private $ref;

    /**
     * @param object $schema
     * @param string $ref
     */
    public function __construct($schema, $ref)
    {
        $this->schema = $schema;
        $this->ref    = $ref;
    }

    /**
     * Specify data which should be serialized to JSON.
     * Because a reference can be circular, references are always
     * re-serialized as the reference property instead of attempting
     * to inline the data.
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return ['$ref' => $this->ref];
    }

    /**
     * Resolve the reference and return the data.
     *
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
