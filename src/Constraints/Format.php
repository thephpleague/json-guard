<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard;
use function League\JsonGuard\asString;
use League\JsonGuard\ErrorCode;
use League\JsonGuard\ValidationError;

class Format implements PropertyConstraint
{
    // regex from http://www.pelagodesign.com/blog/2009/05/20/iso-8601-date-validation-that-doesnt-suck/
    // @codingStandardsIgnoreStart
    const DATE_TIME_PATTERN = '/^([\+-]?\d{4}(?!\d{2}\b))((-?)((0[1-9]|1[0-2])(\3([12]\d|0[1-9]|3[01]))?|W([0-4]\d|5[0-2])(-?[1-7])?|(00[1-9]|0[1-9]\d|[12]\d{2}|3([0-5]\d|6[1-6])))([T\s]((([01]\d|2[0-3])((:?)[0-5]\d)?|24\:?00)([\.,]\d+(?!:))?)?(\17[0-5]\d([\.,]\d+)?)?([zZ]|([\+-])([01]\d|2[0-3]):?([0-5]\d)?)?)?)?$/';
    // @codingStandardsIgnoreEnd

    const HOST_NAME_PATTERN = '/^[_a-z]+\.([_a-z]+\.?)+$/i';

    /**
     * {@inheritdoc}
     */
    public static function validate($value, $parameter, $pointer = null)
    {
        switch ($parameter) {
            case 'date-time':
                return self::validateRegex(
                    $parameter,
                    $value,
                    self::DATE_TIME_PATTERN,
                    ErrorCode::INVALID_DATE_TIME,
                    $pointer
                );
            case 'uri':
                return self::validateFilter(
                    $parameter,
                    $value,
                    FILTER_VALIDATE_URL,
                    null,
                    ErrorCode::INVALID_URI,
                    $pointer
                );
            case 'email':
                return self::validateFilter(
                    $parameter,
                    $value,
                    FILTER_VALIDATE_EMAIL,
                    null,
                    ErrorCode::INVALID_EMAIL,
                    $pointer
                );
            case 'ipv4':
                return self::validateFilter(
                    $parameter,
                    $value,
                    FILTER_VALIDATE_IP,
                    FILTER_FLAG_IPV4,
                    ErrorCode::INVALID_IPV4,
                    $pointer
                );
            case 'ipv6':
                return self::validateFilter(
                    $parameter,
                    $value,
                    FILTER_VALIDATE_IP,
                    FILTER_FLAG_IPV6,
                    ErrorCode::INVALID_IPV6,
                    $pointer
                );
            case 'hostname':
                return self::validateRegex(
                    $parameter,
                    $value,
                    self::HOST_NAME_PATTERN,
                    ErrorCode::INVALID_HOST_NAME,
                    $pointer
                );
        }
    }

    /**
     * @param string $format
     * @param mixed $value
     * @param string $pattern
     * @param int $errorCode
     * @param string $pointer
     *
     * @return \League\JsonGuard\ValidationError|null
     */
    private static function validateRegex($format, $value, $pattern, $errorCode, $pointer)
    {
        if (preg_match($pattern, $value) === 1) {
            return null;
        }

        return new ValidationError(self::invalidFormatMessage($format, $value), $errorCode, $value, $pointer);
    }

    /**
     * @param string $format
     * @param mixed  $value
     * @param int    $filter
     * @param mixed  $options
     * @param int    $errorCode
     * @param string $pointer
     *
     * @return \League\JsonGuard\ValidationError|null
     */
    private static function validateFilter($format, $value, $filter, $options, $errorCode, $pointer)
    {
        if (filter_var($value, $filter, $options) !== false) {
            return null;
        }

        return new ValidationError(self::invalidFormatMessage($format, $value), $errorCode, $value, $pointer);
    }

    /**
     * @param string $format
     * @param mixed  $value
     *
     * @return string
     */
    private static function invalidFormatMessage($format, $value)
    {
        return sprintf('"%s" is not a valid %s.', asString($value), $format);
    }
}
