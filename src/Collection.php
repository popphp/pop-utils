<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/popphp/popphp-framework
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2021 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Utils;

use ReturnTypeWillChange;

/**
 * Pop utils array object class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2021 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.3.0
 */
class Collection extends AbstractArray implements \ArrayAccess, \Countable, \IteratorAggregate
{

    /**
     * Constructor
     *
     * Instantiate the collection object
     *
     * @param mixed $data
     */
    public function __construct($data = [])
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
    public function first()
    {
        return reset($this->data);
    }

    /**
     * Get the next item of the collection
     *
     * @return mixed
     */
    public function next()
    {
        return next($this->data);
    }

    /**
     * Get the current item of the collection
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->data);
    }

    /**
     * Get the last item of the collection
     *
     * @return mixed
     */
    public function last()
    {
        return end($this->data);
    }

    /**
     * Get the key of the current item of the collection
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * Determine if an item exists in the collection
     *
     * @param  mixed   $key
     * @param  boolean $strict
     * @return boolean
     */
    public function contains($key, $strict = false)
    {
        return in_array($key, $this->data, $strict);
    }

    /**
     * Execute a callback over each item
     *
     * @param  callable $callback
     * @return Collection
     */
    public function each(callable $callback)
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
    public function every($step, $offset = 0)
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
     * @param  callable $callback
     * @param  int      $flag
     * @return Collection
     */
    public function filter(callable $callback = null, $flag = 0)
    {
        return new static(array_filter($this->data, $callback, $flag));
    }

    /**
     * Apply map to the collection
     *
     * @param  callable $callback
     * @param  int       $flag
     * @return Collection
     */
    public function map(callable $callback, $flag = 0)
    {
        return new static(array_map($callback, $this->data));
    }

    /**
     * Flip the data in the collection
     *
     * @return Collection
     */
    public function flip()
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
     * @return boolean
     */
    public function has($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Determine if the collection is empty or not
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return empty($this->data);
    }

    /**
     * Get the keys of the collection data
     *
     * @return Collection
     */
    public function keys()
    {
        return new static(array_keys($this->data));
    }

    /**
     * Get the values of the collection data
     *
     * @return Collection
     */
    public function values()
    {
        return new static(array_values($this->data));
    }

    /**
     * Merge the collection with the passed data
     *
     * @param  mixed   $data
     * @param  boolean $recursive
     * @return Collection
     */
    public function merge($data, $recursive = false)
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
    public function forPage($page, $perPage)
    {
        return $this->slice(($page - 1) * $perPage, $perPage);
    }

    /**
     * Get and remove the last item from the collection
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->data);
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param  mixed $value
     * @return Collection
     */
    public function push($value)
    {
        $this->offsetSet(null, $value);
        return $this;
    }

    /**
     * Get and remove the first item from the collection
     *
     * @return mixed
     */
    public function shift()
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
    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->data, $offset, $length, true));
    }

    /**
     * Splice a portion of the collection
     *
     * @param  int      $offset
     * @param  int|null $length
     * @param  mixed    $replacement
     * @return Collection
     */
    public function splice($offset, $length = null, $replacement = [])
    {
        return ((null === $length) && (count($replacement) == 0)) ?
            new static(array_splice($this->data, $offset)) :
            new static(array_splice($this->data, $offset, $length, $replacement));
    }

    /**
     * Sort data
     *
     * @param  callable|null $callback
     * @param  int           $flags
     * @return Collection
     */
    public function sort(callable $callback = null, $flags = SORT_REGULAR)
    {
        $data = $this->data;

        if (null !== $callback) {
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
    public function sortByAsc($flags = SORT_REGULAR)
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
    public function sortByDesc($flags = SORT_REGULAR)
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
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * Method to get data as an array
     *
     * @param  mixed $data
     * @return array
     */
    protected function getDataAsArray($data)
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
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        if (null !== $name) {
            $this->data[$name] = $value;
        } else {
            $this->data[] = $value;
        }
    }

    /**
     * Magic method to return the value of $this->data[$name]
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        return (isset($this->data[$name])) ? $this->data[$name] : null;
    }

    /**
     * Magic method to return the isset value of $this->data[$name]
     *
     * @param  string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Magic method to unset $this->data[$name]
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }
    }

    /**
     * ArrayAccess offsetExists
     *
     * @param  mixed $offset
     * @return boolean
     */
    public function offsetExists($offset): bool
    {
        return $this->__isset($offset);
    }

    /**
     * ArrayAccess offsetGet
     *
     * @param  mixed $offset
     * @return mixed
     */
    #[ReturnTypeWillChange]
    public function offsetGet($offset)
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
    public function offsetSet($offset, $value): void
    {
        $this->__set($offset, $value);
    }

    /**
     * ArrayAccess offsetUnset
     *
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        $this->__unset($offset);
    }

}