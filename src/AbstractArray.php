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

use ArrayIterator;
use ArrayAccess;
use Countable;
use IteratorAggregate;

/**
 * Pop utils abstract array object class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2026 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    2.3.0
 */
abstract class AbstractArray implements ArrayableInterface, ArrayAccess, Countable, IteratorAggregate
{

    /**
     * Array data
     * @var mixed
     */
    protected mixed $data = null;

    /**
     * Method to iterate over the array object
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Method to get the count of data in the collection
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Get the first item of the array
     *
     * @return mixed
     */
    public function first(): mixed
    {
        return reset($this->data);
    }

    /**
     * Get the next item of the array
     *
     * @return mixed
     */
    public function next(): mixed
    {
        return next($this->data);
    }

    /**
     * Get the current item of the array
     *
     * @return mixed
     */
    public function current(): mixed
    {
        return current($this->data);
    }

    /**
     * Get the last item of the array
     *
     * @return mixed
     */
    public function last(): mixed
    {
        return end($this->data);
    }

    /**
     * Get the key of the current item of the array
     *
     * @return mixed
     */
    public function key(): mixed
    {
        return key($this->data);
    }

    /**
     * Determine if an item exists in the array
     *
     * @param  mixed $key
     * @param  bool  $strict
     * @return bool
     */
    public function contains(mixed $key, bool $strict = false): bool
    {
        return in_array($key, $this->data, $strict);
    }

    /**
     * Sort array
     *
     * @param  int  $flags
     * @param  bool $assoc
     * @param  bool $descending
     * @return array
     */
    public function sort(int $flags = SORT_REGULAR, bool $assoc = true, bool $descending = false): static
    {
        if ($descending) {
            $func = ($assoc) ? 'arsort' : 'rsort';
        } else {
            $func = ($assoc) ? 'asort' : 'sort';
        }

        $func($this->data, $flags);

        return $this;
    }

    /**
     * Sort array descending
     *
     * @param  int  $flags
     * @param  bool $assoc
     * @return static
     */
    public function sortDesc(int $flags = SORT_REGULAR, bool $assoc = true): static
    {
        return $this->sort($flags, $assoc, true);
    }

    /**
     * Sort array by keys
     *
     * @param  int  $flags
     * @param  bool $descending
     * @return array
     */
    public function ksort(int $flags = SORT_REGULAR, bool $descending = false): static
    {
        if ($descending) {
            krsort($this->data, $flags);
        } else {
            ksort($this->data, $flags);
        }

        return $this;
    }

    /**
     * Sort array by keys, descending
     *
     * @param  int $flags
     * @return static
     */
    public function ksortDesc(int $flags = SORT_REGULAR): static
    {
        return $this->ksort($flags, true);
    }

    /**
     * Sort array by user-defined callback
     *
     * @param  mixed $callback
     * @param  bool  $assoc
     * @return static
     */
    public function usort(mixed $callback, bool $assoc = true): static
    {
        if ($assoc) {
            uasort($this->data, $callback);
        } else {
            usort($this->data, $callback);
        }

        return $this;
    }

    /**
     * Sort array by user-defined callback using keys
     *
     * @param  mixed $callback
     * @return array
     */
    public function uksort(mixed $callback): static
    {
        uksort($this->data, $callback);
        return $this;
    }

    /**
     * Split a string into an array object
     *
     * @param  string $string
     * @param  string $separator
     * @param  int    $limit
     * @return static
     */
    public static function split(string $string, string $separator, int $limit = PHP_INT_MAX): static
    {
        return new static(explode($separator, $string, $limit));
    }

    /**
     * Join the array values into a string
     *
     * @param  string $glue
     * @param  string $finalGlue
     * @return string
     */
    public function join(string $glue, string $finalGlue = ''): string
    {
        if ($finalGlue === '') {
            return implode($glue, $this->data);
        }

        if (count($this->data) == 0) {
            return '';
        }

        if (count($this->data) == 1) {
            return end($this->data);
        }

        $finalItem = array_pop($this->data);

        return implode($glue, $this->data) . $finalGlue . $finalItem;
    }

    /**
     * Get the values as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        $data = [];

        if (!is_array($this->data)) {
            if (is_object($this->data) && method_exists($this->data, 'toArray')) {
                $data = $this->data->toArray();
            } else if ($this->data instanceof \ArrayObject) {
                $data = (array)$this->data;
            } else if ($this->data instanceof \Traversable) {
                $data = iterator_to_array($this->data);
            }
        } else {
            $data = $this->data;
        }

        foreach ($data as $key => $value) {
            if (is_object($value) && method_exists($value, 'toArray')) {
                $data[$key] = $value->toArray();
            }
        }

        return $data;
    }

    /**
     * Magic method to set the property to the value of $this->data[$name]
     *
     * @param  ?string $name
     * @param  mixed $value
     * @return static
     */
    public function __set(?string $name = null, mixed $value = null)
    {
        if ($name !== null) {
            $this->data[$name] = $value;
        } else {
            $this->data[] = $value;
        }

        return $this;
    }

    /**
     * Get a value
     *
     * @param  string $name
     * @return mixed
     */
    public function __get(string $name): mixed
    {
        return (array_key_exists($name, (array)$this->data)) ? $this->data[$name] : null;
    }

    /**
     * Is value set
     *
     * @param  string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return array_key_exists($name, (array)$this->data);
    }

    /**
     * Unset a value
     *
     * @param  string $name
     * @return void
     */
    public function __unset(string $name): void
    {
        if (array_key_exists($name, (array)$this->data)) {
            unset($this->data[$name]);
        }
    }

    /**
     * ArrayAccess offsetSet
     *
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->__set($offset, $value);
    }

    /**
     * ArrayAccess offsetGet
     *
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get($offset);
    }

    /**
     * ArrayAccess offsetExists
     *
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->__isset($offset);
    }

    /**
     * ArrayAccess offsetUnset
     *
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->__unset($offset);
    }

}
