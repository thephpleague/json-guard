---
layout: default
permalink: validation/overview/
title: Overview
---

# Overview

## Creating Validators

Validators are created by instantiating a new instance of the `Validator` class.  The first argument is the data under validation.  The second argument is the schema that should be applied to the data.

The JSON data we are validating needs to be the object resulting from a `json_decode` call.  The validator **will not work** with the array returned from `json_decode($data, true)`.  For example:

{% highlight php %}
$data = json_decode('{ "id": "https://machete.dev/schema#" }');
{% endhighlight %}

The schema is also an object from a `json_decode` call.  A simple schema would look like this:

{% highlight php %}
$schema = json_decode('{ "properties": { "id": { "type": "string", "format": "uri" } } }');
{% endhighlight %}

If your schema uses the `$ref` keyword, you will need to dereference it first.  Please review the documentation on [dereferencing](/dereferencing/overview/) for an overview of how to dereference your schema first.

## Validating Data

Once your validator is created, you can easily see if validation passes by calling the `passes` method on the validator:

{% highlight php %}
if ($validator->passes()) {
	// all good!
}
{% endhighlight %}

Conversely, you can see if validation failed by calling the `fails` method:

{% highlight php %}
if ($validator->fails()) {
	// uh oh :(
}
{% endhighlight %}

Errors for failing validation are retrieved by calling the `errors` method.  For an overview of the errors format, check out the documentation on [errors](/validation/errors/).

## Limiting Depth

Because circular references are allowed and sometimes neccessary, the validator will continue recursively validating the JSON data until it runs out of data.

This means a payload like this with a cooresponding schema could cause the validator to continue to run for a _very_ long time:

{% highlight json %}
// data:
{ "foo": { "foo": { "foo": { "foo": { "foo": { "foo": { "foo": { "foo": { "foo": { "foo": { "foo": ....
// schema:
{"properties": {"foo": {"$ref": "#"}}, "additionalProperties": false}
{% endhighlight %}

To prevent this, the validator has a `maxDepth`.  If the max depth is exceeded, the validator will throw a `MaximumDepthExceededException`.

The default depth is 10.  If you find it necessary to validate recursive structures deeper than this, you can use the `setMaxDepth` method to set a higher limit.