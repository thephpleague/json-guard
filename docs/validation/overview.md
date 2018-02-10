---
layout: default
permalink: validation/overview
title: Overview
---

# Overview

## Creating Validators

Validators are created by instantiating a new instance of the `Validator` class.  The first argument is the data under validation.  The second argument is the schema that should be applied to the data.

The JSON data we are validating needs to be the **object** resulting from a `json_decode` call.  The validator **will not work** with the **array** returned from `json_decode($data, true)`.  For example:

```php
<?php

$data = json_decode('{ "id": "https://json-guard.dev/schema#" }');
```

The schema is also an object from a `json_decode` call.  A simple schema would look like this:

```php
<?php

$schema = json_decode('{ "properties": { "id": { "type": "string", "format": "uri" } } }');
```

Now you can create your validator:

```php
$validator = new League\JsonGuard\Validator($data, $schema);
```

## Validating Data

Once your validator is created, you can easily see if validation passes by calling the `passes` method on the validator:

```php
<?php

if ($validator->passes()) {
	// all good!
}
```

Conversely, you can see if validation failed by calling the `fails` method:

```php
<?php

if ($validator->fails()) {
	// uh oh :(
}
```

Errors for failing validation are retrieved by calling the `errors` method.  For an overview of the errors format, check out the documentation on [errors](/validation/errors).

## Dereferencing

It's common for JSON Schemas to use [JSON references](https://tools.ietf.org/html/draft-pbryan-zyp-json-ref-03).  A reference lets you reuse schemas by referencing it instead of having to copy it.  A reference looks like `{"$ref": "/somewhere/else"` and resolves to the schema it points to when accessed.

To use references you need to install the [league/json-reference](//json-reference.thephpleague.com) package. Create a `League\JsonReference\Dereferencer` and dereference the schema, which will replace every `ref` with a proxy object.  Once the schema is dereferenced the validator can use the schema and the references will be lazily resolved as they are accessed.

```php
<?php

$dereferencer  = League\JsonReference\Dereferencer::draft4();
$schema        = $dereferencer->dereference('http://json-schema.org/draft-04/schema#');
$data          = json_decode('{ "id": "json-guard.dev/schema#" }');

$validator     = new League\JsonGuard\Validator($data, $schema);

if ($validator->fails()) {
    $errors = $validator->errors();
}
```

## Limiting Depth

Because circular references are allowed and sometimes necessary, the validator will continue recursively validating the JSON data until it runs out of data.

This means a payload like this with a corresponding schema could cause the validator to continue to run for a very long time:

```json
// data:
{ "foo": { "foo": { "foo": { "foo": { "foo": { "foo": { "foo": { "foo": { "foo": { "foo": { "foo": ....
// schema:
{"properties": {"foo": {"$ref": "#"}}, "additionalProperties": false}
```

To prevent this, the validator has a `maxDepth`.  If the max depth is exceeded, the validator will throw a `MaximumDepthExceededException`.

The default depth is 50.  If you find it necessary to validate recursive structures deeper than this, you can use the `setMaxDepth` method to set a higher limit.
