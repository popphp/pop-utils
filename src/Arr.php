<?php
declare(strict_types=1);
/**
 * Pop PHP Framework (https://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
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
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2026 Nick Sagona, III
 * @license    https://www.popphp.org/license     New BSD License
 * @version    3.0.0
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
     * Resolve an array-like value into a plain array
     *
     * @param  mixed $value
     * @return array
     */
    public static function toArray(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        } else if ($value instanceof AbstractArray) {
            return $value->toArray();
        } else if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        } else if ($value instanceof \ArrayObject) {
            return (array)$value;
        } else if ($value instanceof \Traversable) {
            return iterator_to_array($value);
        }

        return [];
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
     * Return the key in the array based on the first position of the value
     *
     * @param  array|AbstractArray $array
     * @param  string|int          $value
     * @param  bool                $strict
     * @return mixed
     */
    public static function key(array|AbstractArray $array, string|int $value, bool $strict = false): mixed
    {
        if ($array instanceof ArrayAccess) {
            $array = $array->toArray();
        }

        return array_search($value, $array, $strict);
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
     * @param  int|float           $depth
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
        $array = static::toArray($array);
        return [array_keys($array), array_values($array)];
    }

    /**
     * Return a slice of the array
     *
     * @param  array|AbstractArray $array
     * @param  int                 $limit
     * @param  int                 $offset
     * @return array
     */
    public static function slice(array|AbstractArray $array, int $limit, int $offset = 0): array
    {
        $array = static::toArray($array);

        return (($limit < 0) && ($offset == 0)) ?
            array_slice($array, $limit, abs($limit)) : array_slice($array, $offset, $limit);
    }

    /**
     * Split a string into an array
     *
     * @param  string $string
     * @param  string $separator
     * @param  ?int    $limit
     * @return array
     */
    public static function split(string $string, string $separator = '', ?int $limit = null): array
    {
        if (empty($separator)) {
            if ($limit === null) {
                $limit = 1;
            }
            return str_split($string, $limit);
        } else {
            if ($limit === null) {
                $limit = PHP_INT_MAX;
            }
            return explode($separator, $string, $limit);
        }
    }

    /**
     * Join the array values into a string
     *
     * @param  array|AbstractArray $array
     * @param  string              $glue
     * @param  string              $finalGlue
     * @return string
     */
    public static function join(array|AbstractArray $array, string $glue, string $finalGlue = ''): string
    {
        $array = static::toArray($array);

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
     * @param  array|AbstractArray $array
     * @param  mixed               $value
     * @param  mixed               $key
     * @return array
     */
    public static function prepend(array|AbstractArray $array, mixed $value, mixed $key = null): array
    {
        $array = static::toArray($array);

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
     * @return mixed
     */
    public static function pull(array &$array, mixed $key): mixed
    {
        $value = $array[$key] ?? null;
        unset($array[$key]);

        return $value;
    }

    /**
     * Sort array
     *
     * @param  array|AbstractArray $array
     * @param  int                 $flags
     * @param  bool                $assoc
     * @param  bool                $descending
     * @return array
     */
    public static function sort(
        array|AbstractArray $array, int $flags = SORT_REGULAR, bool $assoc = true, bool $descending = false
    ): array
    {
        $array = static::toArray($array);
        if ($descending) {
            $func = ($assoc) ? 'arsort' : 'rsort';
        } else {
            $func = ($assoc) ? 'asort' : 'sort';
        }

        $func($array, $flags);
        return $array;
    }

    /**
     * Sort array descending
     *
     * @param  array|AbstractArray $array
     * @param  int                 $flags
     * @param  bool                $assoc
     * @return array
     */
    public static function sortDesc(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $assoc = true): array
    {
        return static::sort($array, $flags, $assoc, true);
    }

    /**
     * Sort array by keys
     *
     * @param  array|AbstractArray $array
     * @param  int                 $flags
     * @param  bool                $descending
     * @return array
     */
    public static function ksort(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $descending = false): array
    {
        $array = static::toArray($array);

        if ($descending) {
            krsort($array, $flags);
        } else {
            ksort($array, $flags);
        }

        return $array;
    }

    /**
     * Sort array by keys, descending
     *
     * @param  array|AbstractArray $array
     * @param  int                 $flags
     * @return array
     */
    public static function ksortDesc(array|AbstractArray $array, int $flags = SORT_REGULAR): array
    {
        return static::ksort($array, $flags, true);
    }

    /**
     * Sort array by user-defined callback
     *
     * @param  array|AbstractArray $array
     * @param  mixed               $callback
     * @param  bool                $assoc
     * @return array
     */
    public static function usort(array|AbstractArray $array, mixed $callback, bool $assoc = true): array
    {
        $array = static::toArray($array);

        if ($assoc) {
            uasort($array, $callback);
        } else {
            usort($array, $callback);
        }

        return $array;
    }

    /**
     * Sort array by user-defined callback using keys
     *
     * @param  array|AbstractArray $array
     * @param  mixed               $callback
     * @return array
     */
    public static function uksort(array|AbstractArray $array, mixed $callback): array
    {
        $array = static::toArray($array);

        uksort($array, $callback);

        return $array;
    }

    /**
     * Execute a callable over the values of the array
     *
     * @param  array|AbstractArray $array
     * @param  mixed               $callback
     * @return array
     */
    public static function map(array|AbstractArray $array, mixed $callback): array
    {
        $array = static::toArray($array);

        return array_map($callback, $array);
    }

    /**
     * Trim extra whitespace in the array values
     *
     * @param  array|AbstractArray $array
     * @return array
     */
    public static function trim(array|AbstractArray $array): array
    {
        $array = static::toArray($array);

        return array_map('trim', $array);
    }

    /**
     * Execute a filter callback over the values of the array
     *
     * @param  array|AbstractArray $array
     * @param  mixed               $callback
     * @param  int   $mode
     * @return array
     */
    public static function filter(array|AbstractArray $array, mixed $callback = null, int $mode = ARRAY_FILTER_USE_BOTH): array
    {
        $array = static::toArray($array);

        return array_filter($array, $callback, $mode);
    }

    /**
     * Merge two arrays, preferring $primary's value at each key unless it is null or an empty
     * string, in which case $fallback's value at that key is used instead. If $recursive is true,
     * keys where both arrays hold an array value are coalesced recursively instead of compared directly
     *
     * @param  array|AbstractArray $primary
     * @param  array|AbstractArray $fallback
     * @param  bool                $recursive
     * @return array
     */
    public static function coalesce(array|AbstractArray $primary, array|AbstractArray $fallback, bool $recursive = false): array
    {
        $primary  = static::toArray($primary);
        $fallback = static::toArray($fallback);

        if (!$recursive) {
            return array_replace($fallback, array_filter($primary, fn($value) => $value !== null && $value !== ''));
        }

        $result = $fallback;

        foreach ($primary as $key => $value) {
            if (is_array($value) && isset($result[$key]) && is_array($result[$key])) {
                $result[$key] = static::coalesce($value, $result[$key], true);
            } else if ($value !== null && $value !== '') {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Force value to be any array (if it is not one already)
     *
     * @param  mixed $value
     * @return array
     */
    public static function make(mixed $value): array
    {
        return is_array($value) ? $value : [$value];
    }

}
