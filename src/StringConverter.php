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
     * Convert a string from one case to another
     *
     * @param string $name
     * @param array  $arguments
     * @return string
     */
    public static function __callStatic($name, $arguments)
    {
        [$from, $to] = explode('to', strtolower($name));
        $string      = $arguments[0] ?? null;
        $separator   = $arguments[1] ?? null;
        $result      = null;

        switch ($from) {
            case 'titlecase':
            case 'camelcase':
                switch ($to) {
                    case 'kebabcase':
                    case 'dash':
                        $separator = '-';
                        break;
                    case 'snakecase':
                    case 'underscore':
                        $separator = '_';
                        break;
                    case 'namespace':
                        $separator = '\\';
                        break;
                    case 'path':
                        $separator = '/';
                        break;
                }
                $result = strtolower(self::convertFromCamelCase($string, $separator));
                break;
            case 'kebabcase':
            case 'dash':
                $separator = '-';
                break;
            case 'snakecase':
            case 'underscore':
                $separator = '_';
                break;
            case 'namespace':
                $separator = '\\';
                break;
            case 'path':
                $separator = '/';
                break;
        }

        if ((null === $result) && (null !== $separator)) {
            switch ($from) {
                case 'titlecase':
                    $result = ucfirst(self::convertToCamelCase($string, $separator));
                    break;
                case 'camelcase':
                    $result = self::convertToCamelCase($string, $separator);
                    break;
            }
        }

        return $result;
    }

    /**
     * Convert a camelCase string using the $separator value passed
     *
     * @param string $string
     * @param string $separator
     * @return string
     */
    public static function convertFromCamelCase($string, $separator)
    {
        $stringAry = str_split($string);
        $converted = null;

        foreach ($stringAry as $i => $char) {
            $converted .= ($i == 0) ?
                strtolower($char) : ((ctype_upper($char)) ? ($separator . strtolower($char)) : $char);
        }

        return $converted;
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

