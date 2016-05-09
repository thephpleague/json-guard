<?php

namespace League\JsonGuard;

final class ErrorCode
{
    const INVALID_NUMERIC         = 22;
    const INVALID_NULL            = 23;
    const INVALID_INTEGER         = 24;
    const INVALID_STRING          = 25;
    const INVALID_BOOLEAN         = 26;
    const INVALID_ARRAY           = 27;
    const INVALID_OBJECT          = 28;
    const INVALID_ENUM            = 29;
    const INVALID_MIN             = 30;
    const INVALID_EXCLUSIVE_MIN   = 31;
    const INVALID_MAX             = 32;
    const INVALID_EXCLUSIVE_MAX   = 33;
    const INVALID_MIN_COUNT       = 34;
    const MAX_ITEMS_EXCEEDED      = 35;
    const INVALID_MIN_LENGTH      = 36;
    const INVALID_MAX_LENGTH      = 37;
    const INVALID_MULTIPLE        = 38;
    const NOT_UNIQUE_ITEM         = 39;
    const INVALID_PATTERN         = 40;
    const INVALID_TYPE            = 41;
    const NOT_SCHEMA              = 42;
    const MISSING_REQUIRED        = 43;
    const ONE_OF_SCHEMA           = 44;
    const ANY_OF_SCHEMA           = 45;
    const ALL_OF_SCHEMA           = 46;
    const NOT_ALLOWED_PROPERTY    = 47;
    const INVALID_EMAIL           = 48;
    const INVALID_URI             = 49;
    const INVALID_IPV4            = 50;
    const INVALID_IPV6            = 51;
    const INVALID_DATE_TIME       = 52;
    const INVALID_HOST_NAME       = 53;
    const INVALID_FORMAT          = 54;
    const NOT_ALLOWED_ITEM        = 55;
    const UNMET_DEPENDENCY        = 56;
    const MAX_PROPERTIES_EXCEEDED = 57;
}
