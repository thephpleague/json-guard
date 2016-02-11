<?php

namespace Machete\Validation;

class Assert
{
    // regex from http://www.pelagodesign.com/blog/2009/05/20/iso-8601-date-validation-that-doesnt-suck/
    // @codingStandardsIgnoreStart
    const DATE_TIME_PATTERN = '/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/';
    // @codingStandardsIgnoreEnd

    const HOST_NAME_PATTERN = '/^[_a-z]+\.([_a-z]+\.?)+$/i';

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function numeric($value, $propertyPath = null)
    {
        if (is_numeric($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not numeric.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_NUMERIC, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function null($value, $propertyPath = null)
    {
        if (is_null($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not null', self::asString($value));
        throw new AssertionFailedException($message, INVALID_NULL, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function integer($value, $propertyPath = null)
    {
        if (is_int($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not an integer', self::asString($value));
        throw new AssertionFailedException($message, INVALID_INTEGER, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function string($value, $propertyPath = null)
    {
        if (is_string($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not a string', self::asString($value));
        throw new AssertionFailedException($message, INVALID_STRING, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function boolean($value, $propertyPath = null)
    {
        if (is_bool($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not boolean', self::asString($value));
        throw new AssertionFailedException($message, INVALID_BOOLEAN, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function isArray($value, $propertyPath = null)
    {
        if (is_array($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not an array.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_ARRAY, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function isObject($value, $propertyPath = null)
    {
        if (is_object($value)) {
            return;
        }

        $message = sprintf('Value "%s" is not an object.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_OBJECT, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function isCountable($value, $propertyPath = null)
    {
        if (is_array($value) || $value instanceof \Countable) {
            return;
        }

        $message = sprintf('Value "%s" is not an array and does not implement Countable.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_ARRAY, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function isTraversable($value, $propertyPath = null)
    {
        if (is_array($value) || $value instanceof \Traversable) {
            return;
        }

        $message = sprintf('Value "%s" is not an array and does not implement Traversable.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_ARRAY, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param array       $choices
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function inArray($value, array $choices, $propertyPath = null)
    {
        if (in_array($value, $choices, true)) {
            return;
        }

        $message = sprintf(
            'Value "%s" is not one of: %s',
            static::asString($value),
            implode(', ', array_map([Assert::class, 'asString'], $choices))
        );
        throw new AssertionFailedException($message, INVALID_ENUM, $value, $propertyPath, compact('choices'));
    }

    /**
     * @param mixed       $value
     * @param array       $choices
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function allInArray($value, array $choices, $propertyPath = null)
    {
        static::isTraversable($value, $propertyPath);

        foreach ($value as $element) {
            static::inArray($element, $choices, $propertyPath);
        }
    }

    /**
     * @param mixed       $value
     * @param int         $min
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function min($value, $min, $propertyPath = null)
    {
        static::numeric($value, $propertyPath);

        if ($value >= $min) {
            return;
        }

        $message = sprintf('Number "%s" is not at least "%d"', self::asString($value), self::asString($min));
        throw new AssertionFailedException($message, INVALID_MIN, $value, $propertyPath, compact('min'));
    }

    /**
     * @param mixed       $value
     * @param int         $min
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function exclusiveMin($value, $min, $propertyPath = null)
    {
        static::numeric($value, $propertyPath);

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
            $propertyPath,
            ['exclusive_min' => $min]
        );
    }

    /**
     * @param mixed       $value
     * @param int         $max
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function max($value, $max, $propertyPath = null)
    {
        static::numeric($value, $propertyPath);

        if ($value <= $max) {
            return;
        }

        $message = sprintf('Number "%s" is not at most "%d"', self::asString($value), self::asString($max));
        throw new AssertionFailedException($message, INVALID_MAX, $value, $propertyPath, compact('max'));
    }

    /**
     * @param mixed       $value
     * @param int         $max
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function exclusiveMax($value, $max, $propertyPath = null)
    {
        static::numeric($value, $propertyPath);

        if ($value < $max) {
            return;
        }

        $message = sprintf('Number "%s" is not less than "%d"', self::asString($value), self::asString($max));
        throw new AssertionFailedException(
            $message,
            INVALID_EXCLUSIVE_MAX,
            $value,
            $propertyPath,
            ['exclusive_max' => $max]
        );
    }

    /**
     * @param mixed       $value
     * @param int         $min
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function minItems($value, $min, $propertyPath = null)
    {
        static::isCountable($value, $propertyPath);

        if (count($value) >= $min) {
            return;
        }

        $message = sprintf('Array does not contain more than "%d" items', self::asString($min));
        throw new AssertionFailedException($message, INVALID_MIN_COUNT, $value, $propertyPath, ['min_items' => $min]);
    }

    /**
     * @param mixed       $value
     * @param int         $max
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function maxItems($value, $max, $propertyPath = null)
    {
        static::isCountable($value, $propertyPath);

        if (count($value) <= $max) {
            return;
        }

        $message = sprintf('Array does not contain less than "%d" items', self::asString($max));
        throw new AssertionFailedException($message, INVALID_MAX_COUNT, $value, $propertyPath, ['max_items' => $max]);
    }

    /**
     * @param mixed       $value
     * @param int         $min
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function minLength($value, $min, $propertyPath = null)
    {
        static::string($value, $propertyPath);

        if (static::strlen($value) >= $min) {
            return;
        }

        $message = sprintf('String is not at least "%s" characters long', self::asString($min));
        throw new AssertionFailedException($message, INVALID_MIN_LENGTH, $value, $propertyPath, ['min_length' => $min]);
    }

    /**
     * @param mixed       $value
     * @param int         $max
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function maxLength($value, $max, $propertyPath = null)
    {
        static::string($value, $propertyPath);

        if (static::strlen($value) <= $max) {
            return;
        }

        $message = sprintf('String is not at most "%s" characters long', self::asString($max));
        throw new AssertionFailedException($message, INVALID_MAX_LENGTH, $value, $propertyPath, ['max_length' => $max]);
    }

    /**
     * @param mixed       $value
     * @param int|float   $multiple
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function multipleOf($value, $multiple, $propertyPath = null)
    {
        static::numeric($value, $propertyPath);

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
            $propertyPath,
            ['multiple_of' => $multiple]
        );
    }

    /**
     * @param mixed       $value
     * @param int         $max
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function maxProperties($value, $max, $propertyPath = null)
    {
        static::isObject($value, $propertyPath);

        if (count(get_object_vars($value)) <= $max) {
            return;
        }

        $message = sprintf('Object does not contain less than "%d" properties', self::asString($max));
        throw new AssertionFailedException(
            $message,
            INVALID_MAX_COUNT,
            $value,
            $propertyPath,
            ['max_properties' => $max]
        );
    }

    /**
     * @param mixed       $value
     * @param int         $min
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function minProperties($value, $min, $propertyPath = null)
    {
        static::isObject($value, $propertyPath);

        if (count(get_object_vars($value)) >= $min) {
            return;
        }

        $message = sprintf('Object does not contain at least "%d" properties', self::asString($min));
        throw new AssertionFailedException(
            $message,
            INVALID_MIN_COUNT,
            $value,
            $propertyPath,
            ['min_properties' => $min]
        );
    }

    /**
     * @param array       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function unique(array $value, $propertyPath = null)
    {
        if (count($value) === count(array_unique(array_map('serialize', $value)))) {
            return;
        }

        $message = sprintf('Array "%s" is not unique.', self::asString($value));
        throw new AssertionFailedException($message, VALUE_NOT_UNIQUE, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string      $pattern
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function regex($value, $pattern, $propertyPath = null)
    {
        static::string($value, $propertyPath);

        if (preg_match($pattern, $value) === 1) {
            return;
        }

        $message = sprintf('Value "%s" does not match the given pattern.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_REGEX, $value, $propertyPath, compact('pattern'));
    }

    /**
     * @param mixed       $value
     * @param array       $choices
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function anyType($value, array $choices, $propertyPath = null)
    {
        foreach ($choices as $type) {
            try {
                Assert::type($value, $type, $propertyPath);

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
        throw new AssertionFailedException($message, INVALID_TYPE, $value, $propertyPath, compact('choices'));
    }

    /**
     * @param mixed       $value
     * @param string      $format
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function format($value, $format, $propertyPath = null)
    {
        switch ($format) {
            case 'date-time':
                Assert::dateTime($value, $propertyPath);
                break;
            case 'uri':
                Assert::uri($value, $propertyPath);
                break;
            case 'email':
                Assert::email($value, $propertyPath);
                break;
            case 'ipv4':
                Assert::ipv4($value, $propertyPath);
                break;
            case 'ipv6':
                Assert::ipv6($value, $propertyPath);
                break;
            case 'hostname':
                Assert::hostname($value, $propertyPath);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unknown format %s', static::asString($format)));
        }
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function dateTime($value, $propertyPath = null)
    {
        static::string($value, $propertyPath);

        if (preg_match(self::DATE_TIME_PATTERN, $value) === 1) {
            return;
        }

        $message = sprintf('"%s" is not a valid date-time string.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_DATE_TIME, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function hostname($value, $propertyPath = null)
    {
        static::string($value, $propertyPath);

        if (preg_match(self::HOST_NAME_PATTERN, $value) === 1) {
            return;
        }

        $message = sprintf('"%s" is not a valid hostname.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_HOST_NAME, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function ipv4($value, $propertyPath = null)
    {
        static::string($value, $propertyPath);

        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) !== false) {
            return;
        }

        $message = sprintf('"%s" is not a valid ipv4 address.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_IPV4, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function ipv6($value, $propertyPath = null)
    {
        static::string($value, $propertyPath);

        if (filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
            return;
        }

        $message = sprintf('"%s" is not a valid ipv6 address.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_IPV6, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function uri($value, $propertyPath = null)
    {
        static::string($value, $propertyPath);

        if (filter_var($value, FILTER_VALIDATE_URL) !== false) {
            return;
        }

        $message = sprintf('"%s" is not a valid uri.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_URI, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function email($value, $propertyPath = null)
    {
        static::string($value, $propertyPath);

        if (filter_var($value, FILTER_VALIDATE_EMAIL) !== false) {
            return;
        }

        $message = sprintf('"%s" is not a valid email.', self::asString($value));
        throw new AssertionFailedException($message, INVALID_EMAIL, $value, $propertyPath);
    }

    /**
     * @param mixed       $value
     * @param string      $type
     * @param string|null $propertyPath
     * @throws AssertionFailedException
     */
    public static function type($value, $type, $propertyPath = null)
    {
        switch ($type) {
            case 'integer':
                Assert::integer($value, $propertyPath);
                break;
            case 'number':
                Assert::numeric($value, $propertyPath);
                break;
            case 'string':
                Assert::string($value, $propertyPath);
                break;
            case 'object':
                Assert::isObject($value, $propertyPath);
                break;
            case 'array':
                Assert::isArray($value, $propertyPath);
                break;
            case 'boolean':
                Assert::boolean($value, $propertyPath);
                break;
            case 'null':
                Assert::null($value, $propertyPath);
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
