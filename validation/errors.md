---
layout: default
permalink: validation/errors/
title: Errors
---

# Introduction

The validator returns detailed errors for each validation failure.  Calling the `errors` method on the validator will return an array of errors:

{% highlight php %}
[
 [
   "code" => 50,
   "message" => "'machete.dev/schema#' is not a valid uri.",
   "path" => "/id",
 ],
 [
   "code" => 25,
   "message" => "Value '2192191' is not a string.",
   "path" => "/name",
 ]
]
{% endhighlight %}

## Error Format

### Code

The code is a unique identifier for this error type.  You can view the complete list of error codes [here](https://github.com/machete-php/validation/blob/master/src/codes.php).

### Message

The message is a developer friendly explanation of what caused the error.

### Path

The path is a [JSON Pointer](https://tools.ietf.org/html/rfc6901) to the attribute that caused the error.

## Localization

The `message` is intended for developers and is not localized.  Error messages can be easily localized for your application using the error codes and the [symfony/translation](http://symfony.com/doc/current/components/translation/usage.html) component or a similar library.