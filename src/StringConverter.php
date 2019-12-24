<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2020 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

/**
 * Pop utils string converter object class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2020 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.0.0
 */
class StringConverter
{

    /**
     * Allowed keywords for converting cases
     * @var array
     */
    protected static $allowedCases = [
        'titlecase', 'camelcase', 'kebabcase', 'dash', 'snakecase', 'underscore', 'namespace', 'path', 'url', 'uri'
    ];

    /**
     * Convert a string from one case to another
     *
     * @param string $name
     * @param array  $arguments
     * @return string
     */
    public static function __callStatic($name, $arguments)
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
                if (null === $preserveCase) {
                    $preserveCase = false;
                }
                break;
            case 'snakecase':
            case 'underscore':
                $separator = '_';
                if (null === $preserveCase) {
                    $preserveCase = false;
                }
                break;
            case 'namespace':
                $separator = '\\';
                if (null === $preserveCase) {
                    $preserveCase = true;
                }
                break;
            case 'path':
                $separator = DIRECTORY_SEPARATOR;
                if (null === $preserveCase) {
                    $preserveCase = true;
                }
                break;
            case 'uri':
            case 'url':
                $separator = '/';
                if (null === $preserveCase) {
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

        if (null === $result) {
            switch ($from) {
                case 'titlecase':
                    $result = ucfirst(self::convertToCamelCase($string, $separator));
                    break;
                case 'camelcase':
                    $result = self::convertToCamelCase($string, $separator);
                    break;
            }

            if (null === $result) {
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
        }

        return $result;
    }

    /**
     * Convert a camelCase string using the $separator value passed
     *
     * @param string  $string
     * @param string  $separator
     * @param boolean $preserveCase
     * @return string
     */
    public static function convertFromCamelCase($string, $separator, $preserveCase = false)
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
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function convertToCamelCase($string, $separator)
    {
        $stringAry = explode($separator, $string);
        $converted = null;

        foreach ($stringAry as $i => $word) {
            $converted .= ($i == 0) ? $word : ucfirst($word);
        }

        return $converted;
    }

}

