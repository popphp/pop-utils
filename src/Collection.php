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

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Pop utils array collection class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
class Collection extends AbstractArray implements ArrayAccess, Countable, IteratorAggregate
{

    /**
     * Constructor
     *
     * Instantiate the collection object
     *
     * @param mixed $data
     */
    public function __construct(mixed $data = [])
    {
        $this->data = $this->getDataAsArray($data);
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
     * Get the first item of the collection
     *
     * @return mixed
     */
    public function first(): mixed
    {
        return reset($this->data);
    }

    /**
     * Get the next item of the collection
     *
     * @return mixed
     */
    public function next(): mixed
    {
        return next($this->data);
    }

    /**
     * Get the current item of the collection
     *
     * @return mixed
     */
    public function current(): mixed
    {
        return current($this->data);
    }

    /**
     * Get the last item of the collection
     *
     * @return mixed
     */
    public function last(): mixed
    {
        return end($this->data);
    }

    /**
     * Get the key of the current item of the collection
     *
     * @return mixed
     */
    public function key(): mixed
    {
        return key($this->data);
    }

    /**
     * Determine if an item exists in the collection
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
     * Execute a callback over each item
     *
     * @param  callable $callback
     * @return Collection
     */
    public function each(callable $callback): Collection
    {
        foreach ($this->data as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Create a new collection from every n-th element
     *
     * @param  int $step
     * @param  int $offset
     * @return Collection
     */
    public function every(int $step, int $offset = 0): Collection
    {
        $new      = [];
        $position = 0;

        foreach ($this->data as $item) {
            if (($position % $step) === $offset) {
                $new[] = $item;
            }
            $position++;
        }

        return new static($new);
    }

    /**
     * Apply filter to the collection
     *
     * @param  ?callable $callback
     * @param  int       $flag
     * @return Collection
     */
    public function filter(?callable $callback = null, int $flag = 0): Collection
    {
        return new static(array_filter($this->data, $callback, $flag));
    }

    /**
     * Apply map to the collection
     *
     * @param  callable $callback
     * @return Collection
     */
    public function map(callable $callback): Collection
    {
        return new static(array_map($callback, $this->data));
    }

    /**
     * Flip the data in the collection
     *
     * @return Collection
     */
    public function flip(): Collection
    {
        foreach ($this->data as $i => $item) {
            $this->data[$i] = array_flip($item);
        }
        return new static($this->data);
    }

    /**
     * Determine if the key exists
     *
     * @param  mixed $key
     * @return bool
     */
    public function has(mixed $key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Determine if the collection is empty or not
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * Get the keys of the collection data
     *
     * @return Collection
     */
    public function keys(): Collection
    {
        return new static(array_keys($this->data));
    }

    /**
     * Get the values of a column
     *
     * @return Collection
     */
    public function column(string $column): Collection
    {
        return new static(array_column($this->data, $column));
    }

    /**
     * Get the values of the collection data
     *
     * @return Collection
     */
    public function values(): Collection
    {
        return new static(array_values($this->data));
    }

    /**
     * Merge the collection with the passed data
     *
     * @param  mixed $data
     * @param  bool  $recursive
     * @return Collection
     */
    public function merge(mixed $data, $recursive = false): Collection
    {
        return ($recursive) ?
            new static(array_merge_recursive($this->data, $this->getDataAsArray($data))) :
            new static(array_merge($this->data, $this->getDataAsArray($data)));
    }

    /**
     * Slice the collection for a page
     *
     * @param  int  $page
     * @param  int  $perPage
     * @return Collection
     */
    public function forPage(int $page, int $perPage): Collection
    {
        return $this->slice(($page - 1) * $perPage, $perPage);
    }

    /**
     * Get and remove the last item from the collection
     *
     * @return mixed
     */
    public function pop(): mixed
    {
        return array_pop($this->data);
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param  mixed $value
     * @return Collection
     */
    public function push(mixed $value): Collection
    {
        $this->offsetSet(null, $value);
        return $this;
    }

    /**
     * Get and remove the first item from the collection
     *
     * @return mixed
     */
    public function shift(): mixed
    {
        return array_shift($this->data);
    }

    /**
     * Slice the collection
     *
     * @param  int $offset
     * @param  int $length
     * @return Collection
     */
    public function slice(int $offset, int $length = null): Collection
    {
        return new static(array_slice($this->data, $offset, $length, true));
    }

    /**
     * Splice a portion of the collection
     *
     * @param  int   $offset
     * @param  ?int  $length
     * @param  mixed $replacement
     * @return Collection
     */
    public function splice(int $offset, ?int $length = null, mixed $replacement = []): Collection
    {
        return (($length === null) && (count($replacement) == 0)) ?
            new static(array_splice($this->data, $offset)) :
            new static(array_splice($this->data, $offset, $length, $replacement));
    }

    /**
     * Sort data
     *
     * @param  ?callable $callback
     * @param  int       $flags
     * @return Collection
     */
    public function sort(?callable $callback = null, int $flags = SORT_REGULAR): Collection
    {
        $data = $this->data;

        if ($callback !== null) {
            uasort($data, $callback);
        } else {
            asort($data, $flags);
        }

        return new static($data);
    }

    /**
     * Sort the collection ascending
     *
     * @param  int $flags
     * @return Collection
     */
    public function sortByAsc(int $flags = SORT_REGULAR): Collection
    {
        $results = $this->data;
        asort($results, $flags);
        return new static($results);
    }

    /**
     * Sort the collection descending
     *
     * @param  int $flags
     * @return Collection
     */
    public function sortByDesc(int $flags = SORT_REGULAR): Collection
    {
        $results = $this->data;
        arsort($results, $flags);
        return new static($results);
    }

    /**
     * Method to get collection object as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->data;
    }

    /**
     * Method to iterate over the collection
     *
     * @return ArrayIterator
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Method to get data as an array
     *
     * @param  mixed $data
     * @return array
     */
    protected function getDataAsArray(mixed $data): array
    {
        if ($data instanceof self) {
            $data = $data->toArray();
        } else if ($data instanceof \ArrayObject) {
            $data = (array)$data;
        } else if (is_object($data) && method_exists($data, 'toArray')) {
            $data = $data->toArray();
        } else if ($data instanceof \Traversable) {
            $data = iterator_to_array($data);
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
     * Magic method to return the value of $this->data[$name]
     *
     * @param  string|int $name
     * @return mixed
     */
    public function __get(string|int $name): mixed
    {
        return (isset($this->data[$name])) ? $this->data[$name] : null;
    }

    /**
     * Magic method to return the isset value of $this->data[$name]
     *
     * @param  string|int $name
     * @return bool
     */
    public function __isset(string|int $name): bool
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Magic method to unset $this->data[$name]
     *
     * @param  string|int $name
     * @return void
     */
    public function __unset(string|int $name): void
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }
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
     * ArrayAccess offsetSet
     *
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset = null, mixed $value = null): void
    {
        $this->__set($offset, $value);
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
