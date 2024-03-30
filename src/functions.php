<?php

use Pop\App;
use Pop\Utils\Str;

if (!function_exists('app_date')) {
    /**
     * Produce datetime string based on app timezone
     *
     * @param  string $format
     * @param ?int    $timestamp
     * @return string|null
     */
    function app_date(string $format, ?int $timestamp = null): string|null
    {
        $timezone = \Pop\App::env('APP_TIMEZONE');
        $gm       = function_exists('gmdate');

        if ((($timezone == 'UTC') || ($timezone == 0)) && ($gm)) {
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
     * @param  int $lengthe
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
    function str_from_camel(string $string, ?string $separator = null, bool $preserveCase = false): string
    {
        return Str::convertFromCamelCase($string, $separator, $preserveCase);
    }
}

if (!function_exists('str_to_camel')) {
    /**
     * Convert a camelCase string using the $separator value passed
     *
     * @param  string $string
     * @param  string $separator
     * @return string
     */
    function str_to_camel(string $string, string $separator): string
    {
        return Str::convertToCamelCase($string, $separator);
    }
}

if (!function_exists('str_title_case')) {
    /**
     * Convert a string to title case
     *
     * @param  string $string
     * @param  string $separator
     * @return string
     */
    function str_title_case(string $string, string $separator): string
    {
        return ucfirst(Str::convertToCamelCase($string, $separator));
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
