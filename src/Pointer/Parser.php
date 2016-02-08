<?php

namespace Machete\Validation\Pointer;

class Parser
{
    /**
     * @param $pointer
     */
    public function __construct($pointer)
    {
        $this->validate($pointer);
        $this->pointer = $pointer;
    }

    /**
     * @return array
     */
    public function parse()
    {
        return $this
            ->explode()
            ->urlDecode()
            ->untilde()
            ->get();
    }

    private function explode()
    {
        $this->pointer = array_slice(explode('/', $this->pointer), 1);

        return $this;
    }

    private function urlDecode()
    {
        $this->pointer = array_map('urldecode', $this->pointer);

        return $this;
    }

    private function untilde()
    {
        $this->pointer = array_map(function ($segment) {
            $segment = str_replace('~1', '/', $segment);

            return str_replace('~0', '~', $segment);
        }, $this->pointer);

        return $this;
    }

    private function get()
    {
        return $this->pointer;
    }

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
