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
 * Pop utils arrayable interface
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    3.0.0
 */
interface ArrayableInterface
{

    /**
     * Get the first item of the array
     *
     * @return mixed
     */
    public function first(): mixed;

    /**
     * Get the next item of the array
     *
     * @return mixed
     */
    public function next(): mixed;

    /**
     * Get the current item of the array
     *
     * @return mixed
     */
    public function current(): mixed;

    /**
     * Get the last item of the array
     *
     * @return mixed
     */
    public function last(): mixed;

    /**
     * Get the key of the current item of the array
     *
     * @return mixed
     */
    public function key(): mixed;

    /**
     * Determine if an item exists in the array
     *
     * @param  mixed $key
     * @param  bool  $strict
     * @return bool
     */
    public function contains(mixed $key, bool $strict = false): bool;

    /**
     * Sort array
     *
     * @param  int  $flags
     * @param  bool $assoc
     * @param  bool $descending
     * @return array
     */
    public function sort(int $flags = SORT_REGULAR, bool $assoc = true, bool $descending = false): static;

    /**
     * Sort array descending
     *
     * @param  int  $flags
     * @param  bool $assoc
     * @return static
     */
    public function sortDesc(int $flags = SORT_REGULAR, bool $assoc = true): static;

    /**
     * Sort array by keys
     *
     * @param  int  $flags
     * @param  bool $descending
     * @return array
     */
    public function ksort(int $flags = SORT_REGULAR, bool $descending = false): static;

    /**
     * Sort array by keys, descending
     *
     * @param  int $flags
     * @return static
     */
    public function ksortDesc(int $flags = SORT_REGULAR): static;

    /**
     * Sort array by user-defined callback
     *
     * @param  mixed $callback
     * @param  bool  $assoc
     * @return static
     */
    public function usort(mixed $callback, bool $assoc = true): static;

    /**
     * Sort array by user-defined callback using keys
     *
     * @param  mixed $callback
     * @return array
     */
    public function uksort(mixed $callback): static;

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
