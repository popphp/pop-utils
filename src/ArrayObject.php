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
 * Pop utils array object class
 *
 * @category   Pop
 * @package    Pop\Utils
 * @author     Nick Sagona, III <dev@noladev.com>
 * @copyright  Copyright (c) 2009-2025 NOLA Interactive, LLC.
 * @license    https://www.popphp.org/license     New BSD License
 * @version    2.2.0
 */
class ArrayObject extends AbstractArray implements SerializableInterface, JsonableInterface
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
        if (($data !== null) && !is_array($data) && !($data instanceof self) && !($data instanceof \ArrayObject) &&
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

}
