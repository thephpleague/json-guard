<?php

namespace League\JsonGuard\Pointer;

/**
 * Parses a JSON Pointer as defined in the specification.
 * @see https://tools.ietf.org/html/rfc6901#section-4
 */
class Parser
{
    /**
     * @var array
     */
    private $pointer;

    /**
     * @param $pointer
     */
    public function __construct($pointer)
    {
        $this->validate($pointer);
        $this->pointer = $this->parse($pointer);
    }

    /**
     * @return array
     */
    public function get()
    {
        return $this->pointer;
    }

    /**
     * @param string $pointer
     *
     * @return array
     */
    private function parse($pointer)
    {
        return $this
            ->explode($pointer)
            ->urlDecode()
            ->untilde()
            ->get();
    }

    /**
     * @param string $pointer
     *
     * @return $this
     */
    private function explode($pointer)
    {
        $this->pointer = array_slice(explode('/', $pointer), 1);

        return $this;
    }

    /**
     * @return $this
     */
    private function urlDecode()
    {
        $this->pointer = array_map('urldecode', $this->pointer);

        return $this;
    }

    /**
     * @return $this
     */
    private function untilde()
    {
        $this->pointer = array_map(function ($segment) {
            $segment = str_replace('~1', '/', $segment);

            return str_replace('~0', '~', $segment);
        }, $this->pointer);

        return $this;
    }

    /**
     * @param string $pointer
     *
     * @throws InvalidPointerException
     */
    private function validate($pointer)
    {
        if ($pointer === '') {
            return;
        }

        if (!is_string($pointer)) {
            throw InvalidPointerException::invalidType(gettype($pointer));
        }

        if (strpos($pointer, '/') !== 0) {
            throw InvalidPointerException::invalidFirstCharacter(substr($pointer, 0, 1));
        }
    }
}
