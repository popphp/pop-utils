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

/**
 * Pop utils arrayable interface
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
interface ArrayableInterface
{

    /**
     * Split a string into an array object
     *
     * @param  string $string
     * @param  string $separator
     * @param  int    $limit
     * @return static
     */
    public static function split(string $string, string $separator, int $limit = PHP_INT_MAX): static;

    /**
     * Join the array values into a string
     *
     * @param  string $glue
     * @param  string $finalGlue
     * @return string
     */
    public function join(string $glue, string $finalGlue = ''): string;

    /**
     * Get the array object as an array.
     *
     * @return array
     */
    public function toArray(): array;

}
