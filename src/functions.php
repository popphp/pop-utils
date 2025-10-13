<?php

use Pop\App;
use Pop\Utils\AbstractArray;
use Pop\Utils\Str;
use Pop\Utils\Arr;

if (!function_exists('app_date')) {
    /**
     * Produce datetime string based on app timezone
     *
     * @param  string $format
     * @param  ?int   $timestamp
     * @param  string $env
     * @param  mixed  $envDefault
     * @return string|null
     */
    function app_date(string $format, ?int $timestamp = null, string $env = 'APP_TIMEZONE', mixed $envDefault = null): string|null
    {
        $timezone = App::env($env, $envDefault);
        $gm       = function_exists('gmdate');

        if ((($timezone == 'UTC') || (is_numeric($timezone) && ($timezone == 0))) && ($gm)) {
            return gmdate($format, $timestamp);
        } else {
            if (is_numeric($timezone) && ($gm)) {
                $timestamp = strtotime(gmdate($format, $timestamp)) + ($timezone * 3600);
            }
            return date($format, $timestamp);
        }
    }
}

if (!function_exists('str_slug')) {
    /**
     * Convert the string into an SEO-friendly slug.
     *
     * @param  string $string
     * @param  string $separator
     * @return string
     */
    function str_slug(string $string, string $separator = '-'): string
    {
        return Str::createSlug($string, $separator);
    }
}

if (!function_exists('str_random')) {
    /**
     * Generate a random string of a predefined length.
     *
     * @param  int $length
     * @param  int $case
     * @return string
     */
    function str_random(int $length, int $case = Str::MIXEDCASE): string
    {
        return Str::createRandom($length, $case);
    }
}

if (!function_exists('str_random_alpha')) {
    /**
     * Generate a random alphabetical string of a predefined length.
     *
     * @param  int $length
     * @param  int $case
     * @return string
     */
    function str_random_alpha(int $length, int $case = Str::MIXEDCASE): string
    {
        return Str::createRandomAlpha($length, $case);
    }
}

if (!function_exists('str_random_num')) {
    /**
     * Generate a random numeric string of a predefined length.
     *
     * @param  int $length
     * @return string
     */
    function str_random_num(int $length): string
    {
        return Str::createRandomNumeric($length);
    }
}

if (!function_exists('str_random_alphanum')) {
    /**
     * Generate a random alphanumeric string of a predefined length.
     *
     * @param  int $length
     * @param  int $case
     * @return string
     */
    function str_random_alphanum(int $length, int $case = Str::MIXEDCASE): string
    {
        return Str::createRandomAlphaNum($length, $case);
    }
}

if (!function_exists('str_from_camel')) {
    /**
     * Convert a camelCase string using the $separator value passed
     *
     * @param  string  $string
     * @param  ?string $separator
     * @param  bool    $preserveCase
     * @return string
     */
    function str_from_camel(string $string, ?string $separator = '-', bool $preserveCase = false): string
    {
        return Str::convertFromCamelCase($string, $separator, $preserveCase);
    }
}

if (!function_exists('str_to_camel')) {
    /**
     * Convert a camelCase string using the $separator value passed
     *
     * @param  string $string
     * @return string
     */
    function str_to_camel(string $string): string
    {
        return Str::convertToCamelCase($string);
    }
}

if (!function_exists('str_title_case')) {
    /**
     * Convert a string to title case
     *
     * @param  string $string
     * @return string
     */
    function str_title_case(string $string): string
    {
        return ucfirst(Str::convertToCamelCase($string));
    }
}

if (!function_exists('str_snake_case')) {
    /**
     * Convert a string to snake case
     *
     * @param  string $string
     * @param  bool   $preserveCase
     * @return string
     */
    function str_snake_case(string $string, bool $preserveCase = false): string
    {
        return Str::convertFromCamelCase($string, '_', $preserveCase);
    }
}

if (!function_exists('str_kebab_case')) {
    /**
     * Convert a string to snake case
     *
     * @param  string $string
     * @param  bool   $preserveCase
     * @return string
     */
    function str_kebab_case(string $string, bool $preserveCase = false): string
    {
        return Str::convertFromCamelCase($string, '-', $preserveCase);
    }
}

if (!function_exists('array_collapse')) {
    /**
     * Collapse an array of arrays
     *
     * @param  array|AbstractArray $array
     * @return array
     */
    function array_collapse(array|AbstractArray $array): array
    {
        return Arr::collapse($array);
    }
}

if (!function_exists('array_flatten')) {
    /**
     * Flatten a multi-dimensional array
     *
     * @param  array|AbstractArray $array
     * @param  int|float           $depth
     * @return array
     */
    function array_flatten(array|AbstractArray $array, int|float $depth = INF): array
    {
        return Arr::flatten($array, $depth);
    }
}

if (!function_exists('array_divide')) {
    /**
     * Divide the array in an array of keys and values
     *
     * @param  array|AbstractArray $array
     * @return array
     */
    function array_divide(array|AbstractArray $array): array
    {
        return Arr::divide($array);
    }
}

if (!function_exists('array_join')) {
    /**
     * Join the array values into a string
     *
     * @param  array|AbstractArray $array
     * @param  string              $glue
     * @param  string              $finalGlue
     * @return string
     */
    function array_join(array|AbstractArray $array, string $glue, string $finalGlue = ''): string
    {
        return Arr::join($array, $glue, $finalGlue);
    }
}

if (!function_exists('array_prepend')) {
    /**
     * Prepend value to the array
     *
     * @param  array|AbstractArray $array
     * @param  mixed               $value
     * @param  mixed               $key
     * @return array
     */
    function array_prepend(array|AbstractArray $array, mixed $value, mixed $key = null): array
    {
        return Arr::prepend($array, $value, $key);
    }
}

if (!function_exists('array_pull')) {
    /**
     * Pull value from the array and remove it
     *
     * @param  array $array
     * @param  mixed $key
     * @return mixed
     */
    function array_pull(array &$array, mixed $key): mixed
    {
        return Arr::pull($array, $key);
    }
}

if (!function_exists('array_sort')) {
    /**
     * Sort array
     *
     * @param  array|AbstractArray $array
     * @param  int                 $flags
     * @param  bool                $assoc
     * @param  bool                $descending
     * @return array
     */
    function array_sort(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $assoc = true, bool $descending = false): array
    {
        return Arr::sort($array, $flags, $assoc, $descending);
    }
}

if (!function_exists('array_sort_desc')) {
    /**
     * Sort array descending
     *
     * @param  array|AbstractArray $array
     * @param  int                 $flags
     * @param  bool                $assoc
     * @return array
     */
    function array_sort_desc(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $assoc = true): array
    {
        return Arr::sortDesc($array, $flags, $assoc);
    }
}

if (!function_exists('array_ksort')) {
    /**
     * Sort array by keys
     *
     * @param  array|AbstractArray $array
     * @param  int                 $flags
     * @param  bool                $descending
     * @return array
     */
    function array_ksort(array|AbstractArray $array, int $flags = SORT_REGULAR, bool $descending = false): array
    {
        return Arr::ksort($array, $flags, $descending);
    }
}

if (!function_exists('array_ksort_desc')) {
    /**
     * Sort array by keys, descending
     *
     * @param  array|AbstractArray $array
     * @param  int                 $flags
     * @return array
     */
    function array_ksort_desc(array|AbstractArray $array, int $flags = SORT_REGULAR): array
    {
        return Arr::ksortDesc($array, $flags);
    }
}

if (!function_exists('array_usort')) {
    /**
     * Sort array by user-defined callback
     *
     * @param  array|AbstractArray $array
     * @param  mixed               $callback
     * @param  bool                $assoc
     * @return array
     */
    function array_usort(array|AbstractArray $array, mixed $callback, bool $assoc = true): array
    {
        return Arr::usort($array, $callback, $assoc);
    }
}

if (!function_exists('array_uksort')) {
    /**
     * Sort array by user-defined callback using keys
     *
     * @param  array|AbstractArray $array
     * @param  mixed               $callback
     * @return array
     */
    function array_uksort(array|AbstractArray $array, mixed $callback): array
    {
        return Arr::uksort($array, $callback);
    }
}

if (!function_exists('array_make')) {
    /**
     * Force value to be any array (if it is not one already)
     *
     * @param  mixed $value
     * @return array
     */
    function array_make(mixed $value): array
    {
        return Arr::make($value);
    }
}

if (!function_exists('is_json')) {
    /**
     * Check if string is valid JSON
     *
     * @param  mixed $json
     * @return bool
     */
    function is_json(mixed $json): bool
    {
        return (((is_string($json) && (json_decode($json) !== false)) &&
            (json_last_error() == JSON_ERROR_NONE)));
    }
}
