---
layout: default
---

# Introduction

machete/validation lets you validate JSON data using [json schema](http://json-schema.org/).

## Notable Features:

- Passes the entire [draft 4 JSON Schema Test Suite](https://github.com/json-schema/JSON-Schema-Test-Suite), except for the optional [bignum](https://github.com/json-schema/JSON-Schema-Test-Suite/blob/develop/tests/draft4/optional/bignum.json) test.
- Fully supports remote references.
- Fully supports circular references.
- Really helpful error messages, with error codes and JSON pointers to the failing data.