<?php
/**
 * Pop PHP Framework (https://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

/**
 * Pop utils string helper class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    2.1.0
 */
class Str
{

    /**
     * Constants case type for random string generation
     */
    const MIXEDCASE = 0;
    const LOWERCASE = 1;
    const UPPERCASE = 2;

    /**
     * Characters for random string generation (certain characters omitted to eliminate confusion)
     * @var array
     */
    protected static array $randomChars = [
        'abcdefghjkmnpqrstuvwxyz',
        'ABCDEFGHJKLMNPQRSTUVWXYZ',
        '0123456789',
        '!?#$%&@-_+*=,.:;()[]{}',
    ];

    /**
     * Regex patterns & replacements for links
     * @var array
     */
    protected static array $linksRegex = [
        [
            'pattern'     => '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/m',
            'replacement' => '<a href="$0">$0</a>'
        ],
        [
            'pattern'     => '/[a-zA-Z0-9\.\-\_+%]+@[a-zA-Z0-9\-\_\.]+\.[a-zA-Z]{2,4}/m',
            'replacement' => '<a href="mailto:$0">$0</a>'
        ]
    ];

    /**
     * Allowed keywords for converting cases
     * @var array
     */
    protected static array $allowedCases = [
        'titlecase', 'camelcase', 'kebabcase', 'dash', 'snakecase', 'underscore', 'namespace', 'path', 'url', 'uri'
    ];

    /**
     * Convert the string into an SEO-friendly slug.
     *
     * @param  string $string
     * @param  string $separator
     * @return string
     */
    public static function createSlug(string $string, string $separator = '-'): string
    {
        $string = str_replace(' ', $separator, preg_replace('/([^a-zA-Z0-9 \-\/])/', '', strtolower($string)));
        $regex  = '/' . $separator . '*' . $separator .'/';

        return preg_replace($regex, $separator, $string);
    }

    /**
     * Convert any links in the string to HTML links.
     *
     * @param  string $string
     * @param  array  $attributes
     * @return string
     */
    public static function createLinks(string $string, array $attributes = []): string
    {
        foreach (self::$linksRegex as $regex) {
            $replacement = $regex['replacement'];

            if (!empty($attributes)) {
                $attribs = [];
                foreach ($attributes as $attrib => $value) {
                    $attribs[] = $attrib . '="' . $value . '"';
                }
                $replacement = str_replace('<a ', '<a ' . implode(' ', $attribs) . ' ', $replacement);
            }

            $string = preg_replace($regex['pattern'], $replacement, $string);
        }

        return $string;
    }

    /**
     * Generate a random string of a predefined length.
     *
     * @param  int $length
     * @param  int $case
     * @return string
     */
    public static function createRandom(int $length, int $case = self::MIXEDCASE): string
    {
        $chars    = self::$randomChars;
        $charsets = [];

        switch ($case) {
            case 1:
                unset($chars[1]);
                break;
            case 2:
                unset($chars[0]);
                break;
        }

        foreach ($chars as $key => $value) {
            $charsets[] = str_split($value);
        }

        return self::generateRandomString($length, $charsets);
    }

    /**
     * Generate a random alphanumeric string of a predefined length.
     *
     * @param  int $length
     * @param  int $case
     * @return string
     */
    public static function createRandomAlphaNum(int $length, int $case = self::MIXEDCASE): string
    {
        $chars    = self::$randomChars;
        $charsets = [];

        switch ($case) {
            case 1:
                unset($chars[1]);
                break;
            case 2:
                unset($chars[0]);
                break;
        }
        unset($chars[3]);

        foreach ($chars as $key => $value) {
            $charsets[] = str_split($value);
        }

        return self::generateRandomString($length, $charsets);
    }

    /**
     * Generate a random alphabetical string of a predefined length.
     *
     * @param  int $length
     * @param  int $case
     * @return string
     */
    public static function createRandomAlpha(int $length, int $case = self::MIXEDCASE): string
    {
        $chars    = self::$randomChars;
        $charsets = [];

        switch ($case) {
            case 1:
                unset($chars[1]);
                break;
            case 2:
                unset($chars[0]);
                break;
        }
        unset($chars[2]);
        unset($chars[3]);

        foreach ($chars as $key => $value) {
            $charsets[] = str_split($value);
        }

        return self::generateRandomString($length, $charsets);
    }

    /**
     * Generate a random numeric string of a predefined length.
     *
     * @param  int $length
     * @return string
     */
    public static function createRandomNumeric(int $length): string
    {
        return self::generateRandomString($length, [str_split(self::$randomChars[2])]);
    }

    /**
     * Generate characters based on length and character sets provided
     *
     * @param  int   $length
     * @param  array $charsets
     * @return string
     */
    public static function generateRandomString(int $length, array $charsets): string
    {
        $string  = '';
        $indices = array_keys($charsets);

        for ($i = 0; $i < $length; $i++) {
            $index    = $indices[rand(0, (count($indices) - 1))];
            $subIndex = rand(0, (count($charsets[$index]) - 1));
            $string  .= $charsets[$index][$subIndex];
        }

        return $string;
    }

    /**
     * Convert a string from one case to another
     *
     * @param string $name
     * @param array  $arguments
     * @return string
     */
    public static function __callStatic(string $name, array $arguments): string
    {
        [$from, $to]   = explode('to', strtolower($name));
        $string        = $arguments[0] ?? null;
        $preserveCase  = (array_key_exists(1, $arguments) && is_bool($arguments[1])) ? $arguments[1] : null;
        $separator     = null;
        $prevSeparator = null;
        $result        = null;

        switch ($to) {
            case 'titlecase':
            case 'camelcase':
                $preserveCase = true;
                break;
            case 'kebabcase':
            case 'dash':
                $separator = '-';
                if ($preserveCase === null) {
                    $preserveCase = false;
                }
                break;
            case 'snakecase':
            case 'underscore':
                $separator = '_';
                if ($preserveCase === null) {
                    $preserveCase = false;
                }
                break;
            case 'namespace':
                $separator = '\\';
                if ($preserveCase === null) {
                    $preserveCase = true;
                }
                break;
            case 'path':
                $separator = DIRECTORY_SEPARATOR;
                if ($preserveCase === null) {
                    $preserveCase = true;
                }
                break;
            case 'uri':
            case 'url':
                $separator = '/';
                if ($preserveCase === null) {
                    $preserveCase = true;
                }
                break;
        }

        switch ($from) {
            case 'titlecase':
            case 'camelcase':
                $result = self::convertFromCamelCase($string, $separator, $preserveCase);
                if ($to == 'titlecase') {
                    $result = ucfirst($result);
                }
                if ($to == 'camelcase') {
                    $result = lcfirst($result);
                }
                break;
            case 'kebabcase':
            case 'dash':
                $prevSeparator = '-';
                break;
            case 'snakecase':
            case 'underscore':
                $prevSeparator = '_';
                break;
            case 'namespace':
                $prevSeparator = '\\';
                break;
            case 'path':
                $prevSeparator = DIRECTORY_SEPARATOR;
                break;
            case 'url':
            case 'uri':
                $prevSeparator = '/';
                break;
        }

        if ($result === null) {
            switch ($to) {
                case 'titlecase':
                    $result = ucfirst(self::convertToCamelCase($string, $prevSeparator));
                    break;
                case 'camelcase':
                    $result = lcfirst(self::convertToCamelCase($string, $prevSeparator));
                    break;
                default:
                    if ($preserveCase) {
                        $string = implode($prevSeparator, array_map('ucfirst', explode($prevSeparator, $string)));
                    }
                    $result = str_replace($prevSeparator, $separator, $string);
                    if ($preserveCase === false) {
                        $result = strtolower($result);
                    }
            }
        }

        return $result;
    }

    /**
     * Convert a camelCase string using the $separator value passed
     *
     * @param string  $string
     * @param ?string $separator
     * @param bool    $preserveCase
     * @return string
     */
    public static function convertFromCamelCase(string $string, ?string $separator = null, bool $preserveCase = false): string
    {
        $stringAry = str_split($string);
        $converted = null;

        foreach ($stringAry as $i => $char) {
            $converted .= ($i == 0) ?
                $char : ((ctype_upper($char)) ? ($separator . $char) : $char);
        }

        return ($preserveCase) ? $converted : strtolower($converted);
    }

    /**
     * Convert a camelCase string using the $separator value passed
     *
     * @param  string  $string
     * @param  ?string $separator
     * @return string
     */
    public static function convertToCamelCase(string $string, ?string $separator = null): string
    {
        if ($separator === null) {
            $separator = self::detectSeparator($string);
        }
        $stringAry = explode($separator, $string);
        $converted = null;

        foreach ($stringAry as $i => $word) {
            $converted .= ($i == 0) ? $word : ucfirst($word);
        }

        return $converted;
    }

    /**
     * Attempt to detect separator
     *
     * @param  string  $string
     * @return string
     */
    public static function detectSeparator(string $string): string
    {
        $separator  = '';
        $separators = ['-', '_', '\\', '/', DIRECTORY_SEPARATOR];

        foreach ($separators as $s) {
            if (str_contains($string, $s)) {
                return $s;
            }
        }

        return $separator;
    }

}

