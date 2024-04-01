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

use ArrayIterator;

/**
 * Pop utils array collection class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.1.0
 */
class Collection extends AbstractArray
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

}
