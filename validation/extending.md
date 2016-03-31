---
layout: default
permalink: validation/extending/
title: Extending
---

# Introduction

JSON Schema allows defining formats like `ipv4` that strings will be validated against.  You can extend the validator with your own formats.

# Usage

The following example shows a simple extension to validate twitter handles.  The extension must take a value and pointer, and return a `ValidationError` if the value is invalid.

```php
<?php

class TwitterHandleFormatExtension implements FormatExtension
{
    /**
     * @param string      $value   The value to validate
     * @param string|null $pointer A pointer to the value
     * @return ValidationError|null
     */
    public function validate($value, $pointer = null)
    {
        if (stripos($value, '@') !== 0) {
            return new ValidationError('A twitter handle must start with "@"', ErrorCode::INVALID_FORMAT, $value, $pointer);
        }
    }
}
```

Once the extension is written, you can register it with the validator.

```php
<?php

$schema = json_decode('{"format": "twitter-handle"}');
$data = '@PHP_CEO';

$validator = new Validator($data, $schema);
$validator->registerFormatExtension('twitter-handle', new TwitterHandleFormatExtension());
```
