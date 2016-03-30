# yuloh/json-guard

This package is a validator for [JSON Schema](http://json-schema.org/).  It fully supports draft 4 of the specification.

Notable Features:

- Passes the entire [draft 4 JSON Schema Test Suite](https://github.com/json-schema/JSON-Schema-Test-Suite).
- Fully supports remote references.
- Fully supports circular references.
- Unique error codes for every validation error.

## Install

** This package is a WIP so you will need to install manually; it isn't on packagist.**

## Usage

Complete documentation is available at [http://yuloh.github.io/json-guard](http://yuloh.github.io/json-guard).

Pull requests for documentation should be sent to the [gh-pages branch](https://github.com/yuloh/json-guard/tree/gh-pages).

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
