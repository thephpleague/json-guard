---
layout: default
permalink: simple-example
title: Simple example
---

# Simple Example

To demonstrate how to use this package, lets write a schema that validates a 'Hello World' greeting.  To validate data against a schema you need to create a `League\JsonGuard\Validator`.  The first argument is the decoded JSON string of the data you want to validate.  The second argument is the decoded JSON string of the Schema you want to validate against.  Once your validator is created you can check the result with `$validator->passes()`.  If it fails you can get the errors by checking `$validator->errors()`.

```php
$data      = json_decode('"Hello World"');
$schema    = json_decode('{"type": "string"}');
$validator = new League\JsonGuard\Validator($data, $schema);

assert($validator->passes());
assert(empty($validator->errors()));
```
