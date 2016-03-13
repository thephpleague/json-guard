# machete/validation

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

This package is a validator for [JSON Schema](http://json-schema.org/).  It fully supports draft 4 of the specification.

Notable Features:

- Passes the entire [draft 4 JSON Schema Test Suite](https://github.com/json-schema/JSON-Schema-Test-Suite).
- Fully supports remote references.
- Fully supports circular references.
- Unique error codes for every validation error.

## Install

** This package is a WIP so you will need to install manually; it isn't on packagist.**

## Usage

Complete documentation is available at [http://validation.machetephp.com/](http://validation.machetephp.com/).

Pull requests for documentation should be sent to the [gh-pages branch](https://github.com/machete-php/validation/tree/gh-pages).

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

You need to run a web server while testing.  A simple node server is in the tests directory.

```bash
$ node tests/server.js
```

Alternatively, if you want to use the php server:

```bash
$ php -S localhost:1234 -t ./vendor/json-schema/JSON-Schema-Test-Suite/remotes/
```

Once the server is running, you can run the test suite.

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
[ico-travis]: https://img.shields.io/travis/machete-php/validation/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/machete-php/validation.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/machete-php/validation.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/machete/validation.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/machete/validation
[link-travis]: https://travis-ci.org/machete-php/validation
[link-scrutinizer]: https://scrutinizer-ci.com/g/machete-php/validation/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/machete-php/validation
[link-downloads]: https://packagist.org/packages/machete/validation
[link-author]: https://github.com/matthew-james
[link-contributors]: ../../contributors
