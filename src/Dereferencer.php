<?php

namespace League\JsonGuard;

/**
 * The Dereferencer resolves all external $refs and replaces
 * internal references with Reference objects.
 */
class Dereferencer
{
    /**
     * @var LoaderManager
     */
    private $loaderManager;

    /**
     * Create a new Dereferencer.
     *
     * @param LoaderManager $loaderManager
     */
    public function __construct(LoaderManager $loaderManager = null)
    {
        $this->loaderManager = $loaderManager ?: new LoaderManager();
    }

    /**
     * Return the schema with all references resolved.
     *
     * @param string|object $schema Either a valid path like "http://json-schema.org/draft-03/schema#"
     *                              or the object resulting from a json_decode call.
     *
     * @return object
     */
    public function dereference($schema)
    {
        if (is_string($schema)) {
            $uri    = $schema;
            $schema = $this->loadExternalRef($uri);
            $schema = $this->resolveFragment($uri, $schema);

            return $this->crawl($schema, strip_fragment($uri));
        }

        return $this->crawl($schema);
    }

    /**
     * @return LoaderManager
     */
    public function getLoaderManager()
    {
        return $this->loaderManager;
    }

    /**
     * Crawl the schema and resolve any references.
     *
     * @param object      $schema
     * @param string|null $currentUri
     *
     * @return object
     */
    private function crawl($schema, $currentUri = null)
    {
        $references = schema_extract($schema, function ($keyword, $value) {
            return $this->isRef($keyword, $value);
        });

        foreach ($references as $path => $ref) {
            $this->resolveReference($schema, $path, $ref, $currentUri);
        }

        return $schema;
    }

    /**
     * @param object $schema
     * @param string $path
     * @param string $ref
     * @param string $currentUri
     */
    private function resolveReference($schema, $path, $ref, $currentUri)
    {
        // resolve
        if (!is_internal_ref($ref)) {
            $resolved = new Reference(function () use ($schema, $path, $ref, $currentUri) {
                return $this->resolveExternalReference($schema, $path, $ref, $currentUri);
            }, $ref);
        } else {
            $resolved = new Reference($schema, $ref);
        }

        // handle any fragments
        $resolved = $this->resolveFragment($ref, $resolved);

        // merge
        $this->mergeResolvedReference($schema, $resolved, $path);
    }

    /**
     * Resolve the external reference at the given path.
     *
     * @param  object      $schema     The JSON Schema
     * @param  string      $path       A JSON pointer to the $ref's location in the schema.
     * @param  string      $ref        The JSON reference
     * @param  string|null $currentUri The URI of the schema, or null if the schema was loaded from an object.
     *
     * @return object                  The schema with the reference resolved.
     */
    private function resolveExternalReference($schema, $path, $ref, $currentUri)
    {
        $ref      = $this->makeReferenceAbsolute($schema, $path, $ref, $currentUri);
        $resolved = $this->loadExternalRef($ref);

        return $this->crawl($resolved, strip_fragment($ref));
    }

    /**
     * Merge the resolved reference with the schema, at the given path.
     *
     * @param  object $schema   The schema to merge the resolved reference with
     * @param  object $resolved The resolved schema
     * @param  string $path     A JSON pointer to the path where the reference should be merged.
     *
     * @return void
     */
    private function mergeResolvedReference($schema, $resolved, $path)
    {
        if ($path === '') {
            // Immediately resolve any root references.
            while ($resolved instanceof Reference) {
                $resolved = $resolved->resolve();
            }
            $this->mergeRootRef($schema, $resolved);
        } else {
            $pointer = new Pointer($schema);
            if ($pointer->has($path)) {
                $pointer->set($path, $resolved);
            }
        }
    }

    /**
     * Check if the reference contains a fragment and resolve
     * the pointer.  Otherwise returns the original schema.
     *
     * @param  string $ref
     * @param  object $schema
     *
     * @return object
     */
    private function resolveFragment($ref, $schema)
    {
        $fragment = parse_url($ref, PHP_URL_FRAGMENT);
        if (!is_internal_ref($ref) && is_string($fragment)) {
            if ($schema instanceof Reference) {
                $schema = $schema->resolve();
            }
            $pointer  = new Pointer($schema);
            return $pointer->get($fragment);
        }

        return $schema;
    }

    /**
     * @param string $attribute
     * @param mixed  $attributeValue
     *
     * @return bool
     */
    private function isRef($attribute, $attributeValue)
    {
        return $attribute === '$ref' && is_string($attributeValue);
    }

    /**
     * Load an external ref and return the JSON object.
     *
     * @param string $reference
     *
     * @return object
     */
    private function loadExternalRef($reference)
    {
        list($prefix, $path) = parse_external_ref($reference);
        $loader = $this->loaderManager->getLoader($prefix);
        $schema = $loader->load($path);

        return $schema;
    }

    /**
     * Merge a resolved reference into the root of the given schema.
     *
     * @param object $rootSchema
     * @param object $resolvedRef
     */
    private function mergeRootRef($rootSchema, $resolvedRef)
    {
        $ref = '$ref';
        unset($rootSchema->$ref);
        foreach (get_object_vars($resolvedRef) as $prop => $value) {
            $rootSchema->$prop = $value;
        }
    }

    /**
     * Take a relative reference, and prepend the id of the schema and any
     * sub schemas to get the absolute url.
     *
     * @param object      $schema
     * @param string      $path
     * @param string      $ref
     * @param string|null $currentUri
     *
     * @return string
     */
    private function makeReferenceAbsolute($schema, $path, $ref, $currentUri = null)
    {
        // If the reference is absolute, we can just return it without walking the schema.
        if (!is_relative_ref($ref)) {
            return $ref;
        }

        $scope = $currentUri ?: '';
        $scope = $this->getResolvedResolutionScope($schema, $path, $scope);

        return resolve_uri($ref, $scope);
    }

    /**
     * Get the resolved resolution scope by walking the schema and resolving
     * every `id` against the most immediate parent scope.
     *
     * @see  http://json-schema.org/latest/json-schema-core.html#anchor27
     *
     * @param  object $schema
     * @param  string $path
     * @param  string $scope
     *
     * @return string
     */
    private function getResolvedResolutionScope($schema, $path, $scope)
    {
        $pointer     = new Pointer($schema);
        $currentPath = '';

        foreach (explode('/', $path) as $segment) {
            if (!empty($segment)) {
                $currentPath .= '/' . $segment;
            }
            if ($pointer->has($currentPath . '/id')) {
                $id = $pointer->get($currentPath . '/id');
                if (is_string($id)) {
                    $scope = resolve_uri($id, $scope);
                }
            }
        }

        return $scope;
    }
}
