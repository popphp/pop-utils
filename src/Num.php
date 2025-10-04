<?php
/**
 * Pop PHP Framework (https://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

/**
 * Pop utils number helper class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
class Num
{

    /**
     * Return a formatted float
     *
     * @param  mixed  $number
     * @param  string $separator
     * @param  string $decimal
     * @param  int    $precision
     * @return string
     */
    public static function float(mixed $number, string $separator = '', string $decimal = '.', int $precision = 2): string
    {
        return number_format((float)$number, $precision, $decimal, $separator);
    }

    /**
     * Return a currency-formatted string
     *
     * @param  mixed  $number
     * @param  string $currency
     * @param  string $separator
     * @param  string $decimal
     * @param  int    $precision
     * @return string
     */
    public static function currency(
        mixed $number, string $currency = '$', string $separator = ',', string $decimal = '.', int $precision = 2
    ): string
    {
        return $currency . number_format((float)$number, $precision, $decimal, $separator);
    }

    /**
     * Return a percentage-formatted string
     *
     * @param  mixed  $number
     * @param  int    $precision
     * @param  string $decimal
     * @return string
     */
    public static function percentage(mixed $number, int $precision = 2, string $decimal = '.'): string
    {
        return number_format((float)$number, $precision, $decimal, '') . '%';
    }

    /**
     * Convert a numeric float to percentage-formatted string
     *
     * @param  mixed  $number
     * @param  int    $precision
     * @param  string $decimal
     * @return string
     */
    public static function convertPercentage(mixed $number, int $precision = 2, string $decimal = '.'): string
    {
        return self::percentage(((float)$number * 100), $precision, $decimal);
    }

    /**
     * Abbreviate number
     *
     * @param  mixed  $number
     * @param  int    $precision
     * @param  bool   $uppercase
     * @param  string $space
     * @return string
     */
    public static function abbreviate(mixed $number, int $precision = 2, bool $uppercase = true, string $space = ''): string
    {
        $unit = '';

        if ($number >= 1000000) {
            $unit     = ($uppercase) ? 'M' : 'm';
            $formatted = round($number / 1000000, $precision);
        } else if (($number < 1000000) && ($number >= 1000)) {
            $unit     = ($uppercase) ? 'K' : 'k';
            $formatted = round($number / 1000, $precision);
        } else {
            $formatted = $number;
        }

        return $formatted . $space . $unit;
    }

    /**
     * Format number to human-readable value
     *
     * @param  mixed $number
     * @param  bool  $case
     * @return string
     */
    public static function readable(mixed $number,  bool $case = true): string
    {
        $unit = '';

        if ($number >= 1000000) {
            $unit    = ($case) ? ' Million' : ' million';
            $formatted = round($number / 1000000);
        } else if (($number < 1000000) && ($number >= 1000)) {
            $unit    = ($case) ? ' Thousand' : ' thousand';
            $formatted = round($number / 1000);
        } else {
            $formatted = $number;
        }

        return $formatted . $unit;
    }

}
