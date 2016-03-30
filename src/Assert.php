<?php

namespace Yuloh\JsonGuard;

class Assert
{
    // regex from http://www.pelagodesign.com/blog/2009/05/20/iso-8601-date-validation-that-doesnt-suck/
    // @codingStandardsIgnoreStart
    const DATE_TIME_PATTERN = '/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/';
    // @codingStandardsIgnoreEnd

    const HOST_NAME_PATTERN = '/^[_a-z]+\.([_a-z]+\.?)+$/i';

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function numeric($value, $pointer = null)
    {
        if (is_numeric($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not numeric.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_NUMERIC, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function null($value, $pointer = null)
    {
        if (is_null($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not null', self::asString($value));
        throw new AssertionFailedException($message, INVALID_NULL, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function integer($value, $pointer = null)
    {
        if (is_int($value)) {
            return;
        }

        // when json decoding numbers larger than PHP_INT_MAX,
        // it's possible to receive a valid int as a string.
        if (is_string($value) && ctype_digit($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not an integer', self::asString($value));
        throw new AssertionFailedException($message, INVALID_INTEGER, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function string($value, $pointer = null)
    {
        if (is_string($value)) {
            // Make sure the string isn't actually a number that was too large
            // to be cast to an int on this platform.
            if (!(ctype_digit($value) && bccomp($value, PHP_INT_MAX, 0) === 1)) {
                return;
            }
        }

        $message = sprintf('Value "%s" is not a string', self::asString($value));
        throw new AssertionFailedException($message, INVALID_STRING, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function boolean($value, $pointer = null)
    {
        if (is_bool($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not boolean', self::asString($value));
        throw new AssertionFailedException($message, INVALID_BOOLEAN, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function isArray($value, $pointer = null)
    {
        if (is_array($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not an array.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_ARRAY, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function isObject($value, $pointer = null)
    {
        if (is_object($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not an object.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_OBJECT, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function isCountable($value, $pointer = null)
    {
        if (is_array($value) || $value instanceof \Countable) {
            return;
        }

        $message = sprintf('Value "%s" is not an array and does not implement Countable.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_ARRAY, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function isTraversable($value, $pointer = null)
    {
        if (is_array($value) || $value instanceof \Traversable) {
            return;
        }

        $message = sprintf('Value "%s" is not an array and does not implement Traversable.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_ARRAY, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param array       $choices
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function inArray($value, array $choices, $pointer = null)
    {
        if (in_array($value, $choices, true)) {
            return;
        }

        $message = sprintf(
            'Value "%s" is not one of: %s',
            static::asString($value),
            implode(', ', array_map([Assert::class, 'asString'], $choices))
        );
        throw new AssertionFailedException($message, INVALID_ENUM, $value, $pointer, compact('choices'));
    }

    /**
     * @param mixed       $value
     * @param array       $choices
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function allInArray($value, array $choices, $pointer = null)
    {
        static::isTraversable($value, $pointer);

        foreach ($value as $element) {
            static::inArray($element, $choices, $pointer);
        }
    }

    /**
     * @param mixed       $value
     * @param int         $min
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function min($value, $min, $pointer = null)
    {
        static::numeric($value, $pointer);

        if ($value >= $min) {
            return;
        }

        $message = sprintf('Number "%s" is not at least "%d"', self::asString($value), self::asString($min));
        throw new AssertionFailedException($message, INVALID_MIN, $value, $pointer, compact('min'));
    }

    /**
     * @param mixed       $value
     * @param int         $min
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function exclusiveMin($value, $min, $pointer = null)
    {
        static::numeric($value, $pointer);

        if ($value > $min) {
            return;
        }

        $message = sprintf(
            'Number "%s" is not at least greater than "%d"',
            self::asString($value),
            self::asString($min)
        );
        throw new AssertionFailedException(
            $message,
            INVALID_EXCLUSIVE_MIN,
            $value,
            $pointer,
            ['exclusive_min' => $min]
        );
    }

    /**
     * @param mixed       $value
     * @param int         $max
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function max($value, $max, $pointer = null)
    {
        static::numeric($value, $pointer);

        if ($value <= $max) {
            return;
        }

        $message = sprintf('Number "%s" is not at most "%d"', self::asString($value), self::asString($max));
        throw new AssertionFailedException($message, INVALID_MAX, $value, $pointer, compact('max'));
    }

    /**
     * @param mixed       $value
     * @param int         $max
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function exclusiveMax($value, $max, $pointer = null)
    {
        static::numeric($value, $pointer);

        if ($value < $max) {
            return;
        }

        $message = sprintf('Number "%s" is not less than "%d"', self::asString($value), self::asString($max));
        throw new AssertionFailedException(
            $message,
            INVALID_EXCLUSIVE_MAX,
            $value,
            $pointer,
            ['exclusive_max' => $max]
        );
    }

    /**
     * @param mixed       $value
     * @param int         $min
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function minItems($value, $min, $pointer = null)
    {
        static::isCountable($value, $pointer);

        if (count($value) >= $min) {
            return;
        }

        $message = sprintf('Array does not contain more than "%d" items', self::asString($min));
        throw new AssertionFailedException($message, INVALID_MIN_COUNT, $value, $pointer, ['min_items' => $min]);
    }

    /**
     * @param mixed       $value
     * @param int         $max
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function maxItems($value, $max, $pointer = null)
    {
        static::isCountable($value, $pointer);

        if (count($value) <= $max) {
            return;
        }

        $message = sprintf('Array does not contain less than "%d" items', self::asString($max));
        throw new AssertionFailedException($message, INVALID_MAX_COUNT, $value, $pointer, ['max_items' => $max]);
    }

    /**
     * @param mixed       $value
     * @param int         $min
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function minLength($value, $min, $pointer = null)
    {
        static::string($value, $pointer);

        if (static::strlen($value) >= $min) {
            return;
        }

        $message = sprintf('String is not at least "%s" characters long', self::asString($min));
        throw new AssertionFailedException($message, INVALID_MIN_LENGTH, $value, $pointer, ['min_length' => $min]);
    }

    /**
     * @param mixed       $value
     * @param int         $max
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function maxLength($value, $max, $pointer = null)
    {
        static::string($value, $pointer);

        if (static::strlen($value) <= $max) {
            return;
        }

        $message = sprintf('String is not at most "%s" characters long', self::asString($max));
        throw new AssertionFailedException($message, INVALID_MAX_LENGTH, $value, $pointer, ['max_length' => $max]);
    }

    /**
     * @param mixed       $value
     * @param int|float   $multiple
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function multipleOf($value, $multiple, $pointer = null)
    {
        static::numeric($value, $pointer);

        // for some reason fmod does not return 0 for cases like fmod(0.0075,0.0001) so I'm doing this manually.
        $quotient = $value / $multiple;
        $mod      = $quotient - floor($quotient);
        if ($mod == 0) {
            return;
        }

        $message = sprintf('Number "%d" is not a multiple of "%d"', self::asString($value), self::asString($multiple));
        throw new AssertionFailedException(
            $message,
            INVALID_MULTIPLE,
            $value,
            $pointer,
            ['multiple_of' => $multiple]
        );
    }

    /**
     * @param mixed       $value
     * @param int         $max
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function maxProperties($value, $max, $pointer = null)
    {
        static::isObject($value, $pointer);

        if (count(get_object_vars($value)) <= $max) {
            return;
        }

        $message = sprintf('Object does not contain less than "%d" properties', self::asString($max));
        throw new AssertionFailedException(
            $message,
            INVALID_MAX_COUNT,
            $value,
            $pointer,
            ['max_properties' => $max]
        );
    }

    /**
     * @param mixed       $value
     * @param int         $min
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function minProperties($value, $min, $pointer = null)
    {
        static::isObject($value, $pointer);

        if (count(get_object_vars($value)) >= $min) {
            return;
        }

        $message = sprintf('Object does not contain at least "%d" properties', self::asString($min));
        throw new AssertionFailedException(
            $message,
            INVALID_MIN_COUNT,
            $value,
            $pointer,
            ['min_properties' => $min]
        );
    }

    /**
     * @param array       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function unique(array $value, $pointer = null)
    {
        if (count($value) === count(array_unique(array_map('serialize', $value)))) {
            return;
        }

        $message = sprintf('Array "%s" is not unique.', self::asString($value));
        throw new AssertionFailedException($message, VALUE_NOT_UNIQUE, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string      $pattern
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function regex($value, $pattern, $pointer = null)
    {
        static::string($value, $pointer);

        if (preg_match($pattern, $value) === 1) {
            return;
        }

        $message = sprintf('Value "%s" does not match the given pattern.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_REGEX, $value, $pointer, compact('pattern'));
    }

    /**
     * @param mixed       $value
     * @param array       $choices
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function anyType($value, array $choices, $pointer = null)
    {
        foreach ($choices as $type) {
            try {
                Assert::type($value, $type, $pointer);

                // If any of them match we can return.
                return;
            } catch (AssertionFailedException $e) {
                // Ignore failing assertions so we can continue iterating.
            }
        }

        $message = sprintf(
            'Value "%s" is not one of: %s',
            static::asString($value),
            implode(', ', array_map([Assert::class, 'asString'], $choices))
        );
        throw new AssertionFailedException($message, INVALID_TYPE, $value, $pointer, compact('choices'));
    }

    /**
     * @param mixed       $value
     * @param string      $format
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function format($value, $format, $pointer = null)
    {
        switch ($format) {
            case 'date-time':
                Assert::dateTime($value, $pointer);
                break;
            case 'uri':
                Assert::uri($value, $pointer);
                break;
            case 'email':
                Assert::email($value, $pointer);
                break;
            case 'ipv4':
                Assert::ipv4($value, $pointer);
                break;
            case 'ipv6':
                Assert::ipv6($value, $pointer);
                break;
            case 'hostname':
                Assert::hostname($value, $pointer);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unknown format %s', static::asString($format)));
        }
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function dateTime($value, $pointer = null)
    {
        static::string($value, $pointer);

        if (preg_match(self::DATE_TIME_PATTERN, $value) === 1) {
            return;
        }

        $message = sprintf('"%s" is not a valid date-time string.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_DATE_TIME, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function hostname($value, $pointer = null)
    {
        static::string($value, $pointer);

        if (preg_match(self::HOST_NAME_PATTERN, $value) === 1) {
            return;
        }

        $message = sprintf('"%s" is not a valid hostname.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_HOST_NAME, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function ipv4($value, $pointer = null)
    {
        static::string($value, $pointer);

        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
            return;
        }

        $message = sprintf('"%s" is not a valid ipv4 address.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_IPV4, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function ipv6($value, $pointer = null)
    {
        static::string($value, $pointer);

        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
            return;
        }

        $message = sprintf('"%s" is not a valid ipv6 address.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_IPV6, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function uri($value, $pointer = null)
    {
        static::string($value, $pointer);

        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
            return;
        }

        $message = sprintf('"%s" is not a valid uri.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_URI, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function email($value, $pointer = null)
    {
        static::string($value, $pointer);

        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
            return;
        }

        $message = sprintf('"%s" is not a valid email.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_EMAIL, $value, $pointer);
    }

    /**
     * @param mixed       $value
     * @param string      $type
     * @param string|null $pointer
     * @throws AssertionFailedException
     */
    public static function type($value, $type, $pointer = null)
    {
        switch ($type) {
            case 'integer':
                Assert::integer($value, $pointer);
                break;
            case 'number':
                Assert::numeric($value, $pointer);
                break;
            case 'string':
                Assert::string($value, $pointer);
                break;
            case 'object':
                Assert::isObject($value, $pointer);
                break;
            case 'array':
                Assert::isArray($value, $pointer);
                break;
            case 'boolean':
                Assert::boolean($value, $pointer);
                break;
            case 'null':
                Assert::null($value, $pointer);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unknown type %s', static::asString($type)));
        }
    }

    /**
     * @param $string
     * @return int
     */
    protected static function strlen($string)
    {
        if (extension_loaded('intl')) {
            return grapheme_strlen($string);
        }

        if (extension_loaded('mbstring')) {
            return mb_strlen($string, mb_detect_encoding($string));
        }

        return strlen($string);
    }

    /**
     * @param mixed $value
     * @return string
     */
    protected static function asString($value)
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_int($value)) {
            return (string)$value;
        }

        if (is_bool($value)) {
            return $value ? '<TRUE>' : '<FALSE>';
        }

        if (is_object($value)) {
            return get_class($value);
        }

        if (is_array($value)) {
            return '<ARRAY>';
        }

        if (is_resource($value)) {
            return '<RESOURCE>';
        }

        if (is_null($value)) {
            return '<NULL>';
        }

        return '<UNKNOWN>';
    }
}
