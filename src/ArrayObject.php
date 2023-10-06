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
use Countable;
use IteratorAggregate;

/**
 * Pop utils array object class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@nolainteractive.com>
 * @copyright  Copyright (c) 2009-2024 NOLA Interactive, LLC. (http://www.nolainteractive.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    2.0.0
 */
class ArrayObject extends AbstractArray implements ArrayAccess, Countable, IteratorAggregate, SerializableInterface, JsonableInterface
{

    /**
     * Constructor
     *
     * Instantiate the array object
     *
     * @param mixed|null $data
     * @throws Exception
     */
    public function __construct(mixed $data = null)
    {
        if ((null !== $data) && !is_array($data) && !($data instanceof self) && !($data instanceof \ArrayObject) &&
            !($data instanceof \ArrayAccess) && !($data instanceof \Countable) && !($data instanceof \IteratorAggregate)) {
            throw new Exception('Error: The data passed must be an array or an array-like object.');
        }
        $this->data = $data;
    }

    /**
     * Create array object from JSON string
     *
     * @param  string $jsonString
     * @param  int    $depth
     * @param  int    $options
     * @return ArrayObject
     */
    public static function createFromJson(string $jsonString, int $depth = 512, int $options = 0): ArrayObject
    {
        return (new self())->jsonUnserialize($jsonString, $depth, $options);
    }

    /**
     * Create array object from serialized string
     *
     * @param string $string
     * @return ArrayObject
     * @throws Exception
     */
    public static function createFromSerialized(string $string): ArrayObject
    {
        return (new self())->unserialize($string);
    }

    /**
     * Method to get the count of the array object
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Method to iterate over the array object
     *
     * @return \ArrayIterator
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->data);
    }

    /**
     * JSON serialize the array object
     *
     * @param  int $options
     * @param  int $depth
     * @return string
     */
    public function jsonSerialize(int $options = 0, int $depth = 512): string
    {
        return json_encode($this->toArray(), $options, $depth);
    }

    /**
     * Unserialize a JSON string
     *
     * @param  string $jsonString
     * @param  int    $depth
     * @param  int    $options
     * @return ArrayObject
     */
    public function jsonUnserialize(string $jsonString, int $depth = 512, int $options = 0): ArrayObject
    {
        $this->data = json_decode($jsonString, true, $depth, $options);
        return $this;
    }

    /**
     * Serialize the array object
     *
     * @param  bool $self
     * @return string
     */
    public function serialize(bool $self = false): string
    {
        return ($self)? serialize($this) : serialize($this->toArray());
    }

    /**
     * Unserialize a string
     *
     * @param  string $string
     * @throws Exception
     * @return ArrayObject
     */
    public function unserialize(string $string): ArrayObject
    {
        $data = @unserialize($string);
        if ($data instanceof ArrayObject) {
            return $data;
        } else if (is_array($data)) {
            $this->data = $data;
            return $this;
        } else {
            throw new Exception('Error: The string was not able to be correctly unserialized.');
        }
    }

    /**
     * Serialize magic method
     *
     * @return mixed
     */
    public function __serialize()
    {
        return $this->data;
    }

    /**
     * Unserialize magic method
     *
     * @param  array $data
     * @return ArrayObject
     */
    public function __unserialize(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set a value
     *
     * @param  string $name
     * @param  mixed $value
     * @return ArrayObject
     */
    public function __set(string $name, mixed $value)
    {
        $this->data[$name] = $value;
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
