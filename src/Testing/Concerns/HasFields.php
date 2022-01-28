<?php

namespace Aic\Hub\Foundation\Testing\Concerns;

use Closure;
use Illuminate\Testing\Fluent\AssertableJson;

trait HasFields
{
    /**
     * Overwrite this in child classes. All API fields must be accounted
     * for here. Keys are API field names. Values are handled differently
     * depending on their type:
     *
     *  1. Strings are assumed to be type names and used to check type.
     *  2. Closures will have the value passed to them, return boolean.
     *  3. Arrays are assumed to contain subfield definitions.
     *
     * For id and title fields, use the `ref` static functions, ex:
     *
     *     'artwork_ids' => self::refIds(ArtworkTestCase::class)
     *
     * The following type names are valid:
     *
     *     string, integer, double, boolean, array, null
     *
     * You can specify multiple valid types like this:
     *
     *     string|null
     *
     * @link https://laravel.com/docs/8.x/http-tests#asserting-json-types
     */
    protected function fields()
    {
        return [
            'id' => 'integer',
            'title' => 'string',
        ];
    }

    protected function validateFields(AssertableJson &$json, $fields = null)
    {
        if ($fields === null) {
            $fields = $this->fields();
        }

        foreach ($fields as $key => $expected) {
            if (is_string($expected)) {
                $json->whereType($key, $expected);
            }

            if ($expected instanceof Closure) {
                $json->where($key, $expected);
            }

            if (is_array($expected)) {
                $json->has($key, function ($json) use ($expected) {
                    $this->validateFields($json, $expected);
                });
            }

            if ($expected === null) {
                $json->has($key);
            }
        }
    }

    public static function validateId($id)
    {
        return ($this->model)::validateId($id);
    }

    protected function refId($resourceTestClass): Closure
    {
        return function ($id) use ($resourceTestClass) {
            if ($id === null) {
                return true;
            }

            return ($resourceTestClass)::validateId($id);
        };
    }

    protected function refTitle(): Closure
    {
        return function ($title) {
            if ($title === null) {
                return true;
            }

            if (is_string($title)) {
                return true;
            }

            return false;
        };
    }

    protected function refIds($resourceTestClass): Closure
    {
        return function ($ids) use ($resourceTestClass) {
            if (!is_iterable($ids)) {
                return false;
            }

            foreach ($ids as $id) {
                if (!($resourceTestClass)::validateId($id)) {
                    return false;
                }
            }

            return true;
        };
    }

    protected function refTitles(): Closure
    {
        return function ($titles) {
            if (!is_iterable($titles)) {
                return false;
            }

            foreach ($titles as $title) {
                if (!is_string($title)) {
                    return false;
                }
            }

            return true;
        };
    }

}
