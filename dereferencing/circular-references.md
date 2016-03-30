---
layout: default
permalink: dereferencing/circular-references/
title: Circular References
---

# Circular References

This package fully supports recursive references.  Consider the following example [^n]:

```json
{
  "person": {
    "properties": {
        "name": {
          "type": "string"
        },
        "spouse": {
          "type": {
            "$ref": "#/person"        // circular reference
          }
        }
    }
  }
}
```

## Resolving

If the dereferencer attempted to fully resolve this reference, the dereferencer would continue looping infintely.  Instead of resolving references immediately, the $ref is replaced with a [reference object](https://github.com/yuloh/json-guard/blob/master/src/Reference.php).

The reference object is resolved lazily by the validator.  The validator will stop resolving once it runs out of data to validate or the maximum depth has been exceeded.

## Serializing

Because a $ref may be circular, attempting to inline the $ref would be impossible.

When serialized, all references are transformed into the original `{ "$ref": "#/some/reference" }` format instead of attempting to inline them.

[^n]: The example is from the [json-schema-ref-parser](https://github.com/BigstickCarpet/json-schema-ref-parser/blob/master/docs/README.md#circular-refs) docs.
