# machete/validation

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This package is a validator for [JSON Schema](http://json-schema.org/).  It fully supports draft 4 of the specification.

Notable Features:

- Passes the entire [draft 4 JSON Schema Test Suite](https://github.com/json-schema/JSON-Schema-Test-Suite), except for the optional [bignum](https://github.com/json-schema/JSON-Schema-Test-Suite/blob/develop/tests/draft4/optional/bignum.json) test.
- Fully supports remote references.
- Fully supports circular references.
- Unique error codes for every validation error.

## Install

Via Composer

``` bash
$ composer require machete/validation
```

## Usage

### Dereferencing

If your schema uses the '$ref' keyword anywhere or you are not sure, you should dereference the schema first.  This will allow the validator to resolve any internal or external references.

The JSON should be provided as the object resulting from a `json_decode` call, not a string or array.

```php
$deref  = new Machete\Validation\Dereferencer();
$schema = $deref->dereference(json_decode($json));
```

Alternatively, you can provide a path to load the schema from.  By default `file://`, `http://`, and `https://` paths are supported.

```php
$deref  = new Machete\Validation\Dereferencer();
$schema = $deref->dereference('http://json-schema.org/draft-04/schema#');
```

### Validating

To validate data, construct a new validator instance with the data and the resolved schema.

``` php
$schema = json_decode('{ "properties": { "id": { "type": "string", "format": "uri" } } }');
$data = json_decode('{ "id": "machete.dev/schema#" }');

$validator = new Validator($data, $schema);

if ($validator->fails()) {
    $errors = $validator->errors();
}
```

Each validation error will contain a unique code, a message, and a path.

```php
[
 [
   "code" => 50,
   "message" => ""machete.dev/schema#" is not a valid uri.",
   "path" => "/id",
 ],
]
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email matthew.james.allan@gmail.com instead of using the issue tracker.

## Credits

- [Matt Allan][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/machete/validation.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/machete/validation/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/machete/validation.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/machete/validation.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/machete/validation.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/machete/validation
[link-travis]: https://travis-ci.org/machete-php/validation
[link-scrutinizer]: https://scrutinizer-ci.com/g/machete-php/validation/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/machete-php/validation
[link-downloads]: https://packagist.org/packages/machete/validation
[link-author]: https://github.com/matthew-james
[link-contributors]: ../../contributors
