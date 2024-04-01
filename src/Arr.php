<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

use ArrayAccess;
/**
 * Pop utils array helper class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
class Arr
{

    /**
     * Check if the value is an array-like (array-accessible) value
     *
     * @param  mixed $value
     * @return bool
     */
    public static function isArray(mixed $value): bool
    {
        return (is_array($value) || ($value instanceof ArrayAccess));
    }

    /**
     * Check if the value is a numeric (non-associative) array
     *
     * @param  array $array
     * @return bool
     */
    public static function isNumeric(array $array): bool
    {
        return array_is_list($array);
    }

    /**
     * Check if the value is an associative (non-numeric) array
     *
     * @param  array $array
     * @return bool
     */
    public static function isAssoc(array $array): bool
    {
        return !array_is_list($array);
    }

    /**
     * Check if the key value exists in the array
     *
     * @param  array|ArrayAccess $array
     * @param  string|int        $key
     * @return bool
     */
    public static function exists(array|ArrayAccess $array, string|int $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        } else {
            return array_key_exists($key, $array);
        }
    }

    /**
     * Return the value of the key in the array
     *
     * @param  array|ArrayAccess $array
     * @param  string|int        $key
     * @return mixed
     */
    public static function search(array|ArrayAccess $array, string|int $key): mixed
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetGet($key);
        } else {
            return array_search($key, $array);
        }
    }

    /**
     * Collapse an array of arrays
     *
     * @param  array|AbstractArray $array
     * @return array
     */
    public static function collapse(array|AbstractArray $array): array
    {
        $collapsed = [];

        foreach ($array as $values) {
            if ($values instanceof AbstractArray) {
                $values = $values->toArray();
            } else if (!is_array($values)) {
                continue;
            }
            $collapsed[] = $values;
        }

        return array_merge([], ...$collapsed);
    }

    /**
     * Flatten a multi-dimensional array
     *
     * @param  array|AbstractArray $array
     * @param  int|float        $depth
     * @return array
     */
    public static function flatten(array|AbstractArray $array, int|float $depth = INF): array
    {
        $flattened = [];

        foreach ($array as $value) {
            if ($value instanceof AbstractArray) {
                $value = $value->toArray();
            }

            if (!is_array($value)) {
                $flattened[] = $value;
            } else {
                $values = ($depth === 1) ? array_values($value) : static::flatten($value, $depth - 1);
                foreach ($values as $val) {
                    $flattened[] = $val;
                }
            }
        }

        return $flattened;
    }

    /**
     * Divide the array in an array of keys and values
     *
     * @param  array|AbstractArray $array
     * @return array
     */
    public static function divide(array|AbstractArray $array): array
    {
        if ($array instanceof AbstractArray) {
            $array = $array->toArray();
        }
        return [array_keys($array), array_values($array)];
    }

    /**
     * Return a slice of the array
     *
     * @param  array $array
     * @param  int   $limit
     * @return array
     */
    public static function slice(array $array, int $limit): array
    {
        if ($limit < 0) {
            return array_slice($array, $limit, abs($limit));
        } else {
            return array_slice($array, 0, $limit);
        }
    }

    /**
     * Split a string into an array
     *
     * @param  string $string
     * @param  string $separator
     * @param  int    $limit
     * @return array
     */
    public static function split(string $string, string $separator, int $limit = PHP_INT_MAX): array
    {
        return explode($separator, $string, $limit);
    }

    /**
     * Join the array values into a string
     *
     * @param  array  $array
     * @param  string $glue
     * @param  string $finalGlue
     * @return string
     */
    public static function join(array $array, string $glue, string $finalGlue = ''): string
    {
        if ($finalGlue === '') {
            return implode($glue, $array);
        }

        if (count($array) == 0) {
            return '';
        }

        if (count($array) == 1) {
            return end($array);
        }

        $finalItem = array_pop($array);

        return implode($glue, $array) . $finalGlue . $finalItem;
    }

    /**
     * Prepend value to the array
     *
     * @param  array $array
     * @param  mixed $value
     * @param  mixed $key
     * @return array
     */
    public static function prepend(array $array, mixed $value, mixed $key = null): array
    {
        if ($key === null) {
            array_unshift($array, $value);
        } else {
            $array = [$key => $value] + $array;
        }

        return $array;
    }

    /**
     * Pull value from the array and remove it
     *
     * @param  array $array
     * @param  mixed $key
     * @return array
     */
    public static function pull(array &$array, mixed $key): array
    {
        $value = $array[$key] ?? null;
        unset($array[$key]);

        return $value;
    }

}
